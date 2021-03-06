<?php
    require_once 'utilities/buckthorn.php';
    require_once 'utilities/env.php';
    require_once 'utilities/output_helpers.php';
?>
<?php
    $header_options['title'] = 'View Observation';
    $header_options['active_menu_id'] = 'list_observations';
    include 'templates/header.php';
?>

<?php /* ------------------- PAGE CONTENT BEGINS HERE ------------------- */ ?>

    <?php
        $observation = query_first('SELECT * FROM observation WHERE o_id = %o_id%', [
            'o_id' => $_GET['id'],
        ]);
		$observation_t_id = $observation['t_id'];
		$active_team = get_team();
		$active_t_id = $active_team['t_id'];
    ?>

    <h1>Observation Details</h1>

    <?php if (empty($observation)): ?>
        <p class="error">No observation found with id <?php echo $_GET['id'] ?></p>
	<?php elseif (($active_t_id != $observation_t_id) && !is_admin()):
		redirect('/');
	?>
    <?php else: ?>
        <?php
            $notes = query_first('SELECT * FROM notes WHERE o_id = %o_id%', [
                'o_id' => $observation['o_id'],
            ]);
        ?>

        <table class="observation-grid">
            <tbody>
                <tr>
                    <td>
                        <div class="observation">
                            <h2>General information</h2>
                            
                            <table class="form-table">
                                <tbody>
                                    <tr>
                                        <th>Date</th>
                                        <td><?php echo $observation['o_date'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Coordinates</th>
                                        <td><?php echo $observation['o_latitude'] ?>, <?php echo $observation['o_longitude'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Quadrant size (m<sup>2</sup>)</th>
                                        <td><?php echo $observation['o_quadrantsize'] ?>
                                    </tr>
                                    <tr>
                                        <th>Number of buckthorn stems</th>
                                        <td><?php echo $observation['o_numstems'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Buckthorn foliar coverage (%)</th>
                                        <td><?php echo $observation['o_foliar'] ?>%</td>
                                    </tr>
                                    <tr>
                                        <th>Median buckthorn stem circumference (cm)</th>
                                        <td><?php echo $observation['o_circumference'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>URL to photos</th>
                                        <td><a href="<?php echo e($observation['o_photos']) ?>">View</a></td>
                                    </tr>
                                    <tr>
                                        <th>Habitat description</th>
                                        <td><?php echo e($notes['n_habitat']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Other notes</th>
                                        <td><?php echo e($notes['n_general']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Biodiversity notes</th>
                                        <td><?php echo e($notes['n_biodiversity']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Competition notes</th>
                                        <td><?php echo e($notes['n_competition']) ?></td>
                                    </tr>
                                </tbody>
                            </table>

                            <iframe class="google-map" src="//www.google.com/maps/embed/v1/place?q=<?php echo urlencode($observation['o_latitude'] . ', ' . $observation['o_longitude']) ?>&zoom=18&key=<?php echo $_ENV['GOOGLE_MAPS_KEY'] ?>">
                            </iframe>
                        </div>
                    </td>
                    <td>
                        <div class="bio_counts">
                            <h2>Biodiversity Info</h2>

                            <?php
                                $bio_counts = query('SELECT * FROM bio_count WHERE o_id = %o_id%', [
                                    'o_id' => $observation['o_id'],
                                ]);
                                $bio_count_buckthorns = array_filter($bio_counts, function ($bio_count) {
                                    return $bio_count['bc_is_buckthorn'] == '1';
                                });
                                $bio_count_buckthorn = array_shift($bio_count_buckthorns);

                                // Yes, we compute Shannon-Wiener completely through MySQL. Heck yeah!
                                $shannon_wiener = query_first($shannon_wiener_query, [
                                    'o_id' => $observation['o_id'],
                                ]);

                                $species_counter = ord('A');
                            ?>
                            
                            <table class="form-table">
                                <tbody>
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
                                    <tr>
                                        <th>Shannon-Wiener Index</th>
                                        <td><?php echo $shannon_wiener['H'] ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="competitions">
                            <h2>Competition Info</h2>
                            
                            <?php
                                $competitions = query('SELECT * FROM competition WHERE o_id = %o_id%', [
                                    'o_id' => $observation['o_id'],
                                ]);

                                $stem_counter = 0;
                            ?>

                            <?php foreach ($competitions as $competition): ?>
                                <?php
                                    $stem_counter++;
                                ?>

                                <h3>Plant <?php echo $stem_counter ?></h3>

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
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

		<h4>Delete this observation:</h4>
		<form  method="post" action="delete_observation.php ">
		<input type = "hidden" name= "o_id" value = "<?php echo $_GET['id']?>" />
		<p><input type='submit' value='Delete' /></p>
		</form>
    <?php endif; ?>

<?php /* ------------------- PAGE CONTENT ENDS HERE ------------------- */ ?>
<?php
    include 'templates/footer.php';
?>
