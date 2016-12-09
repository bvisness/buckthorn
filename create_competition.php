<?php
    require_once 'utilities/mysql.php';
    require_once 'utilities/user.php';
?>
<?php
    $header_options['title'] = 'Create competition record';
    include 'templates/header.php';
?>
<?php /* ------------------- PAGE CONTENT BEGINS HERE ------------------- */ ?>

<form>
<p>Diameter at breast height of Buckthorn (cm): <input type='text' id = 'c_dbh_buckthorn'></p>
<p>Diameter at breast height of nearest Buckthorn neighbour (cm): <input type='text' id = 'c_dbh_neighbor_b'></p>
<p>Diameter at breast height of nearest non-Buckthorn neighbour (cm): <input type='text' id = 'c_dbh_neighbor_nb'></p>
<p>Distance to the nearest Buckthorn neighbor (cm): <input type='text' id = 'c_distance_b'></p>
<p>Distance to the nearest non-Buckthorn neighbor (cm):  <input type='text' id = 'c_distance_nb'></p>
</form>
<?php /* The following two buttons are left as a reminder that we need to create a link to another page. Probably this starts a method in JS to redirect*/?>
<p><input type = 'button' value = 'Submit' id = 'submit_competition'/></p>
<p><input type = 'button' value = 'Advance to additional notes' id = 'redirect_notes'/></p>

<?php /* ------------------- PAGE CONTENT ENDS HERE ------------------- */ ?>
<?php
    include 'templates/footer.php';
?>
