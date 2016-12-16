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

        <div class="observation">
            <h2>Details</h2>
            
            <table>
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
                        <th>Quadrant Size</th>
                        <td><?php echo $observation['o_quadrantsize'] ?> m<sup>2</sup></td>
                    </tr>
                    <tr>
                        <th>Number of Buckthorn Stems</th>
                        <td><?php echo $observation['o_numstems'] ?></td>
                    </tr>
                    <tr>
                        <th>Buckthorn Foliar Coverage</th>
                        <td><?php echo $observation['o_foliar'] ?>%</td>
                    </tr>
                    <tr>
                        <th>Median Buckthorn Stem Circumference</th>
                        <td><?php echo $observation['o_circumference'] ?></td>
                    </tr>
                    <tr>
                        <th>Photos</th>
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
                </tbody>
            </table>

            <iframe src="//www.google.com/maps/embed/v1/place?q=<?php echo urlencode($observation['o_latitude'] . ', ' . $observation['o_longitude']) ?>&zoom=18&key=<?php echo $_ENV['GOOGLE_MAPS_KEY'] ?>">
            </iframe>
        </div>
        <div class="bio_counts">
            <h2>Biodiversities</h2>

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
            
            <table>
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

            <h3>Notes</h3>

            <p><?php echo e($notes['n_biodiversity']) ?></p>
        </div>
        <div class="competitions">
            <h2>Competitions</h2>
            
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

                <h3>Stem <?php echo $stem_counter ?></h3>

                <table>
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

            <h3>Notes</h3>

            <p><?php echo e($notes['n_competition']) ?></p>
        </div>
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
