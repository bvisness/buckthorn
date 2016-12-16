<?php
    require_once 'utilities/buckthorn.php';
    require_once 'utilities/mysql.php';
    require_once 'utilities/output_helpers.php';
    require_once 'utilities/session.php';
    require_once 'utilities/user.php';
?>

<?php
    // Set up session variables if not already done
    if (empty($_SESSION['create_observation'])) {
        $_SESSION['create_observation'] = [];
    }
    $_SESSION['create_observation'] += [
        'observation' => [],
        'bio_counts' => [],
        'competitions' => [],
        'notes' => [
            'n_habitat' => '',
            'n_general' => '',
            'n_biodiversity' => '',
            'n_competition' => '',
        ],
    ];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // authenticate the user?

        // Get fields that are always present
        $valid_fields = array_intersect_key($_POST, array_flip($insert_observation_fields));

        // Validate fields
        if (false) {
            $_POST['error'] = 'You messed up!';
            goto output;
        }

        // Save all valid vields in the session
        $_SESSION['create_observation']['observation'] = $valid_fields;
        $notes_fields = array_keys($_SESSION['create_observation']['notes']);
        foreach ($notes_fields as $field) {
            if (isset($_POST[$field])) {
                $_SESSION['create_observation']['notes'][$field] = $_POST[$field];
            }
        }

        // Get fields and do stuff
        switch ($_GET['action']) {
            case 'add_bio_count': {
                // Validate bc_count
                if (!isset($_POST['bc_count'])) {
                    goto output;
                }
                $bc_count = (int) $_POST['bc_count'];
                if ($bc_count < 0) {
                    $_POST['error'] = 'Please provide a valid specimen count (greater than or equal to zero)';
                    goto output;
                }

                // Remove other buckthorn entries if necessary
                if (!empty($_POST['bc_is_buckthorn'])) {
                    $bio_counts = $_SESSION['create_observation']['bio_counts'];
                    $_SESSION['create_observation']['bio_counts'] = array_filter($bio_counts, function ($bio_count) {
                        return !$bio_count['bc_is_buckthorn'];
                    });
                }

                $_SESSION['create_observation']['bio_counts'][] = [
                    'bc_is_buckthorn' => !empty($_POST['bc_is_buckthorn']),
                    'bc_count' => $bc_count,
                ];
            } break;
            case 'reset_bio_counts': {
                $_SESSION['create_observation']['bio_counts'] = [];
            } break;
            case 'add_competition': {
                $valid_fields = array_intersect_key($_POST, array_flip($insert_competition_fields));
                // validate?
                $_SESSION['create_observation']['competitions'][] = $valid_fields;
            } break;
            case 'reset_competitions': {
                $_SESSION['create_observation']['competitions'] = [];
            } break;
            case 'commit': {
                $con = get_db_connection();
                $did_start_transaction = $con->begin_transaction();

                if (!$did_start_transaction) {
                    $_POST['error'] = 'Failed to start database transaction.';
                    goto output;
                }

                try {
                    $observation = $_SESSION['create_observation']['observation'];
                    $insert_observation_result = query(generate_insert_query('observation', $insert_observation_fields), [
                        't_id' => $_SESSION['t_id'],
                        'o_date' => $observation['o_date'],
                        'o_latitude' => $observation['o_latitude'],
                        'o_longitude' => $observation['o_longitude'],
                        'o_quadrantsize' => $observation['o_quadrantsize'],
                        'o_numstems' => $observation['o_numstems'],
                        'o_foliar' => $observation['o_foliar'],
                        'o_circumference' => $observation['o_circumference'],
                        'o_photos' => $observation['o_photos'],
                    ]);

                    if (!$insert_observation_result) {
                        throw new MySqlException('Failed to insert observation.');
                    }

                    $o_id = $con->insert_id;

                    foreach ($_SESSION['create_observation']['bio_counts'] as $bio_count) {
                        $insert_bio_count_result = query(generate_insert_query('bio_count', $insert_bio_count_fields), [
                            'o_id' => $o_id,
                            'bc_count' => $bio_count['bc_count'],
                            'bc_is_buckthorn' => $bio_count['bc_is_buckthorn'],
                        ]);

                        if (!$insert_bio_count_result) {
                            throw new MySqlException('Failed to insert biodiversity record.');
                        }
                    }

                    foreach ($_SESSION['create_observation']['competitions'] as $competition) {
                        $insert_competition_result = query(generate_insert_query('competition', $insert_competition_fields), [
                            'o_id' => $o_id,
                            'c_dbh_buckthorn' => $competition['c_dbh_buckthorn'],
                            'c_dbh_neighbor_b' => $competition['c_dbh_neighbor_b'],
                            'c_dbh_neighbor_nb' => $competition['c_dbh_neighbor_nb'],
                            'c_distance_b' => $competition['c_distance_b'],
                            'c_distance_nb' => $competition['c_distance_nb'],
                        ]);

                        if (!$insert_competition_result) {
                            throw new MySqlException('Failed to insert competition record.');
                        }
                    }

                    $notes = $_SESSION['create_observation']['notes'];
                    $insert_notes_result = query(generate_insert_query('notes', $insert_notes_fields), [
                        'o_id' => $o_id,
                        'n_habitat' => $notes['n_habitat'],
                        'n_general' => $notes['n_general'],
                        'n_biodiversity' => $notes['n_biodiversity'],
                        'n_competition' => $notes['n_competition'],
                    ]);

                    if (!$insert_notes_result) {
                        throw new MySqlException('Failed to insert notes');
                    }

                    $con->commit();

                    $_SESSION['create_observation'] = [];
                    redirect("view_observation.php?id=$o_id");
                }
                catch (Exception $e) {
                    $con->rollback();
                    $_POST['error'] = $e;
                    goto output;
                }
            } break;
        }
    }

    function field_or_empty($array, $name) {
        if (empty($array) || empty($array[$name])) {
            return '';
        } else {
            return $array[$name];
        }
    }
?>

<?php
output:
    $header_options['title'] = 'Create Observation';
    include 'templates/header.php';
?>
<?php /* ------------------- PAGE CONTENT BEGINS HERE ------------------- */ ?>

    <h1>Record an observation</h1>

    <?php if (!empty($_POST['error'])): ?>
        <p class="error"><?php echo $_POST['error'] ?></p>
    <?php endif; ?>

    <form action="create_observation.php" method="POST">
        <?php
            $observation = $_SESSION['create_observation']['observation'];
            $notes = $_SESSION['create_observation']['notes'];
        ?>

        <table class="observation-grid">
            <tbody>
                <tr>
                    <td>
                        <h2>General information</h2>
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th>Date</th>
                                    <td><input type="date" name="o_date" value="<?php echo field_or_empty($observation, 'o_date') ?>"></td>
                                </tr>
                                <tr>
                                    <th>Latitude</th>
                                    <td><input type="number" name="o_latitude" value="<?php echo field_or_empty($observation, 'o_latitude') ?>"></td>
                                </tr>
                                <tr>
                                    <th>Longitude</th>
                                    <td><input type="number" name="o_longitude" value="<?php echo field_or_empty($observation, 'o_longitude') ?>"></td>
                                </tr>
                                <tr>
                                    <th>Quadrant Size (m<sup>2</sup>)</th>
                                    <td><input type="number" name="o_quadrantsize" value="<?php echo field_or_empty($observation, 'o_quadrantsize') ?>"></td>
                                </tr>
                                <tr>
                                    <th>Number of Buckthorn stems</th>
                                    <td><input type="number" name="o_numstems" value="<?php echo field_or_empty($observation, 'o_numstems') ?>"></td>
                                </tr>
                                <tr>
                                    <th>Percent Buckthorn foliar coverage</th>
                                    <td><input type="number" name="o_foliar" value="<?php echo field_or_empty($observation, 'o_foliar') ?>"></td>
                                </tr>
                                <tr>
                                    <th>Median Buckthorn stem circumference (cm)</th>
                                    <td><input type="number" name="o_circumference" value="<?php echo field_or_empty($observation, 'o_circumference') ?>"></td>
                                </tr>
                                <tr>
                                    <th>URL to photos</th>
                                    <td><input type="text" name="o_photos" value="<?php echo field_or_empty($observation, 'o_photos') ?>"></td>
                                </tr>
                                <tr>
                                    <th>Habitat description</th>
                                    <td><textarea name="n_habitat" rows="6"><?php echo field_or_empty($notes, 'n_habitat') ?></textarea></td>
                                </tr>
                                <tr>
                                    <th>Other notes</th>
                                    <td><textarea name="n_general" rows="6"><?php echo field_or_empty($notes, 'n_general') ?></textarea></td>
                                </tr>
                                <tr>
                                    <th>Biodiversity notes</th>
                                    <td><textarea name="n_biodiversity" rows="6"><?php echo field_or_empty($notes, 'n_biodiversity') ?></textarea></td>
                                </tr>
                                <tr>
                                    <th>Competition notes</th>
                                    <td><textarea name="n_competition" rows="6"><?php echo field_or_empty($notes, 'n_competition') ?></textarea></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td>
                        <h2>Biodiversities</h2>

                        <?php
                            $bio_counts = $_SESSION['create_observation']['bio_counts'];
                            $bio_count_buckthorns = array_filter($bio_counts, function ($bio_count) {
                                return $bio_count['bc_is_buckthorn'] == '1';
                            });
                            $bio_count_buckthorn = array_shift($bio_count_buckthorns);

                            $species_counter = ord('A');
                        ?>
                        
                        <table class="form-table">
                            <tbody>
                                <?php if (!empty($bio_counts)): ?>
                                    <?php if (!empty($bio_count_buckthorn)): ?>
                                        <tr>
                                            <th>Buckthorn count</th>
                                            <td><?php echo $bio_count_buckthorn['bc_count'] ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php foreach ($bio_counts as $bio_count): ?>
                                        <?php
                                            if ($bio_count['bc_is_buckthorn'] == '1') {
                                                continue;
                                            }
                                        ?>
                                        <tr>
                                            <th>Species <?php echo chr($species_counter) ?> count</th>
                                            <td><?php echo $bio_count['bc_count'] ?></td>
                                        </tr>
                                        <?php
                                            $species_counter++;
                                        ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <tr>
                                    <th>New species</th>
                                    <td>
                                        <input type="checkbox" id="bc_is_buckthorn" name="bc_is_buckthorn">
                                        <label for="bc_is_buckthorn">Is buckthorn?</label>
                                        <input type="number" id="bc_count" name="bc_count" placeholder="Count">
                                        <input type="submit" value="Add new biodiversity" formaction="create_observation.php?action=add_bio_count">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><input class="btn-subtle" type="submit" value="Remove all biodiversities" formaction="create_observation.php?action=reset_bio_counts"></td>
                                </tr>
                            </tbody>
                        </table>


                        <h2>Competitions</h2>

                        <?php
                            $stem_counter = 0;
                        ?>

                        <?php foreach ($_SESSION['create_observation']['competitions'] as $competition): ?>
                            <?php
                                $stem_counter++;
                            ?>

                            <h3>Stem <?php echo $stem_counter ?></h3>

                            <table class="form-table">
                                <tbody>
                                    <tr>
                                        <th>Stem DBH</th>
                                        <td><?php echo $competition['c_dbh_buckthorn'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Nearest buckthorn neighbor: DBH</th>
                                        <td><?php echo $competition['c_dbh_neighbor_b'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Nearest buckthorn neighbor: Distance</th>
                                        <td><?php echo $competition['c_distance_b'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Nearest non-buckthorn neighbor: DBH</th>
                                        <td><?php echo $competition['c_dbh_neighbor_nb'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Nearest non-buckthorn neightbor: Distance</th>
                                        <td><?php echo $competition['c_distance_nb'] ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php endforeach; ?>

                        <h3>New competition</h3>

                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th>Stem DBH</th>
                                    <td><input type="number" name="c_dbh_buckthorn"></td>
                                </tr>
                                <tr>
                                    <th>Nearest buckthorn neighbor: DBH</th>
                                    <td><input type="number" name="c_dbh_neighbor_b"></td>
                                </tr>
                                <tr>
                                    <th>Nearest buckthorn neighbor: Distance</th>
                                    <td><input type="number" name="c_distance_b"></td>
                                </tr>
                                <tr>
                                    <th>Nearest non-buckthorn neighbor: DBH</th>
                                    <td><input type="number" name="c_dbh_neighbor_nb"></td>
                                </tr>
                                <tr>
                                    <th>Nearest non-buckthorn neightbor: Distance</th>
                                    <td><input type="number" name="c_distance_nb"></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><input type="submit" value="Add new competition" formaction="create_observation.php?action=add_competition"></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><input class="btn-subtle" type="submit" value="Remove all competitions" formaction="create_observation.php?action=reset_competitions"></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <input class="btn-finalize" type="submit" value="Finalize Observation" formaction="create_observation.php?action=commit">

        <script>
            $('input').on('keypress', function (e) {
                if (e.which == 13) {
                    e.preventDefault();
                }
            });
        </script>
    </form>

<?php /* ------------------- PAGE CONTENT ENDS HERE ------------------- */ ?>
<?php
    include 'templates/footer.php';
?>
