<?php
    require 'utilities/mysql.php';
    require 'utilities/output_helpers.php';
?>

<?php
    $header_options['title'] = 'Researchers';
    include 'templates/header.php';
?>

<?php /* ------------------- PAGE CONTENT BEGINS HERE ------------------- */ ?>

    <h1>Researchers</h1>

    <?php if (isset($_GET['id'])): ?>

        <?php
            $researchers = query('SELECT * FROM researcher WHERE r_id = %r_id%', [
                'r_id' => $_GET['id'],
            ]);
            $researcher = array_shift($researchers);
        ?>
        <h2><?php echo $researcher['r_name'] ?></h2>

    <?php else: ?>

        <?php
            $researchers = query('SELECT * FROM researcher');
            debug_table($researchers);
        ?>

    <?php endif; ?>

<?php /* ------------------- PAGE CONTENT ENDS HERE ------------------- */ ?>

<?php
    include 'templates/footer.php';
?>
