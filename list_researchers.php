<?php
    require 'utilities/mysql.php';
    require 'utilities/output_helpers.php';
?>

<?php
    $header_options['title'] = 'All Researchers';
    include 'templates/header.php';
?>

<?php /* ------------------- PAGE CONTENT BEGINS HERE ------------------- */ ?>

    <h1>Researchers</h1>

    <?php
        $researchers = query('SELECT * FROM researcher');

        // Sort researchers alphabetically
        usort($researchers, function ($a, $b) {
            return strcmp($a['r_name'], $b['r_name']);
        });
    ?>

    <ul>
        <?php foreach ($researchers as $researcher): ?>
            <li><?php echo $researcher['r_name'] ?></li>
        <?php endforeach; ?>
    </ul>

<?php /* ------------------- PAGE CONTENT ENDS HERE ------------------- */ ?>

<?php
    include 'templates/footer.php';
?>
