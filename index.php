<?php
    require_once 'utilities/mysql.php';
    require_once 'utilities/user.php';
?>

<?php
    $header_options['title'] = 'Hello World!';
    include 'templates/header.php';
?>

<?php /* ------------------- PAGE CONTENT BEGINS HERE ------------------- */ ?>

    <img class="wanted" src="<?php echo url('/assets/buckthorn_wanted.gif') ?>">

<?php /* ------------------- PAGE CONTENT ENDS HERE ------------------- */ ?>

<?php
    include 'templates/footer.php';
?>
