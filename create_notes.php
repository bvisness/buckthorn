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
        <p>Observation habitat notes: <textarea id="n_habitat" rows="4" cols="40"></textarea></p>
        <p>Observation general notes: <textarea id="n_general" rows="4" cols="40"></textarea></p>
        <p>Biodiversity notes: <textarea id="n_biodiversity" rows="4" cols="40"></textarea></p>
        <p>Competition notes: <textarea id="n_competition" rows="4" cols="40"></textarea></p>
    </form>
    <?php /* The following two buttons are left as a reminder that we need to create a link to another page. Probably this starts a method in JS to redirect*/?>
    <p><input type = 'button' value = 'Submit' id = 'submit_notes'/></p>

<?php /* ------------------- PAGE CONTENT ENDS HERE ------------------- */ ?>
<?php
    include 'templates/footer.php';
?>