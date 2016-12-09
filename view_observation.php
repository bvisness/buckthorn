<?php
    require_once 'utilities/env.php';
    require_once 'utilities/output_helpers.php';
?>
<?php
    $header_options['title'] = 'View Observations';
    include 'templates/header.php';
?>

<?php /* ------------------- PAGE CONTENT BEGINS HERE ------------------- */ ?>

    <?php
        $observation = query_first('SELECT * FROM observation WHERE o_id = %o_id%', [
            'o_id' => $_GET['id'],
        ]);
    ?>

    <h1>Observation <?php echo empty($observation) ? '??' : $observation['o_id'] ?></h1>

    <?php if (empty($observation)): ?>
        <p class="error">No observation found with id <?php echo $_GET['id'] ?></p>
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
                </tbody>
            </table>

            <iframe src="//www.google.com/maps/embed/v1/place?q=<?php echo urlencode($observation['o_latitude'] . ', ' . $observation['o_longitude']) ?>&zoom=18&key=<?php echo $_ENV['GOOGLE_MAPS_KEY'] ?>">
            </iframe>
        </div>
        <div class="bio_counts">
            <h2>Biodiversities</h2>
            <?php /* Need to fill this in */ ?>
        </div>
        <div class="competitions">
            <h2>Competitions</h2>
            <?php /* Need to fill this in */ ?>
        </div>
        <div class="notes">
            <h2>Notes</h2>
            <div class="note">
                <h3 class="title">Habitat</h3>
                <div class="content"><?php echo e($notes['n_habitat']) ?></div>
            </div>
            <div class="note">
                <h3 class="title">General</h3>
                <div class="content"><?php echo e($notes['n_general']) ?></div>
            </div>
            <div class="note">
                <h3 class="title">Biodiversity</h3>
                <div class="content"><?php echo e($notes['n_biodiversity']) ?></div>
            </div>
            <div class="note">
                <h3 class="title">Competition</h3>
                <div class="content"><?php echo e($notes['n_competition']) ?></div>
            </div>
        </div>
    <?php endif; ?>

<?php /* ------------------- PAGE CONTENT ENDS HERE ------------------- */ ?>
<?php
    include 'templates/footer.php';
?>
