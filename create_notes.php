<?php
    require_once 'utilities/mysql.php';
    require_once 'utilities/user.php';
?>
<?php
    $header_options['title'] = 'Additional observation notes';
    include 'templates/header.php';
?>
<?php /* ------------------- PAGE CONTENT BEGINS HERE ------------------- */ ?>

<form>
<p>Observation habitat notes: <input type='text' id = 'n_habitat'></p>
<p>Observation general notes: <input type='text' id = 'n_general'></p>
<p>Biodiversity notes: <input type='text' id = 'n_biodiversity'></p>
<p>Competition notes: <input type='text' id = 'n_competition'></p>
</form>
<?php /* The following two buttons are left as a reminder that we need to create a link to another page. Probably this starts a method in JS to redirect*/?>
<p><input type = 'button' value = 'Submit' id = 'submit_notes'/></p>

<?php /* ------------------- PAGE CONTENT ENDS HERE ------------------- */ ?>
<?php
    include 'templates/footer.php';
?>