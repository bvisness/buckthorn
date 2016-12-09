<?php
    require_once 'utilities/mysql.php';
    require_once 'utilities/user.php';
?>
<?php
    $header_options['title'] = 'Create Observation';
    include 'templates/header.php';
?>
<?php /* ------------------- PAGE CONTENT BEGINS HERE ------------------- */ ?>

    <form>
		<p>Date: <input type = 'date' id = 'o_date'></p>
		<p>Latitude: <input type = 'text' id = 'o_latitude'></p>
		<p>Longitude: <input type = 'text' id = 'o_longitude'></p>
		<p>Quadrant Size (m): <input type = 'text' id = 'o_quadrentsize'></p>
		<p>Number of Buckthorn stems: <input type = 'text' id = 'o_numstems'></p>
		<p>Percent Buckthorn foliar coverage: <input type = 'text' id = 'o_foliar'></p>
		<p>Median Buckthorn stem circumference (cm): <input type = 'text' id = 'o_circumference'></p>
		<p>URL to photos: <input type = 'text' id = 'o_photos'></p>
	</form>
	<?php /* The following button is left as a reminder that we need to create a link to another page. Probably this starts a method in JS to redirect*/?>
	<p><input type = 'button' value = 'Submit' id = 'submitObservation'/></p>

<?php /* ------------------- PAGE CONTENT ENDS HERE ------------------- */ ?>
<?php
    include 'templates/footer.php';
?>
