<?php
    require_once 'utilities/mysql.php';
    require_once 'utilities/user.php';
?>
<?php
    $header_options['title'] = 'Create biodiversity record';
    include 'templates/header.php';
?>
<?php /* ------------------- PAGE CONTENT BEGINS HERE ------------------- */ ?>

<form>
<p>Buckthorn measurment: <input type='checkbox' value='is_buckthorn' id="bc_is_buckthorn"/></p>
<p>Number of plants: <input type='text' id = 'bc_count'></p>
</form>
<?php /* The following two buttons are left as a reminder that we need to create a link to another page. Probably this starts a method in JS to redirect*/?>
<p><input type = 'button' value = 'Submit' id = 'submit_bio_count'/></p>
<p><input type = 'button' value = 'Advance to competition records' id = 'redirect_competition'/></p>

<?php /* ------------------- PAGE CONTENT ENDS HERE ------------------- */ ?>
<?php
    include 'templates/footer.php';
?>
