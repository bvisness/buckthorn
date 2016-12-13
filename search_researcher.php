<?php
    require_once 'utilities/mysql.php';
    require_once 'utilities/user.php';
?>
<?php
    $header_options['title'] = 'Search researchers';
    include 'templates/header.php';
?>

<?php /* ------------------- PAGE CONTENT BEGINS HERE ------------------- */ ?>

	<?php
		$researcher_name = $_POST['researcher_name'];
        $researcher_list = query("SELECT * FROM researcher where r_name like '%%r_name%%'", [
            'r_name' => $researcher_name,
        ]);
    ?>
	
	<h1>Search Researchers</h1>
	<h5>Researchers found for the search string: <?php echo $researcher_name?></h5>
	
	<table>
        <thead>
            <tr>
				<th>Team member ID</th>
                <th>Team member name</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($researcher_list as $researcher): ?>
                <tr>
                    <td><?php echo $researcher['r_id'] ?></td>
                    <td><?php echo $researcher['r_name'] ?></td>
					<?php 
						$t_id = $_POST['t_id'];
						$create_membership_url = url("create_membership.php?t_id=$t_id&r_id=$researcher[r_id]");
					?>
					<td><a href = <?php echo $create_membership_url?>>Create membership</a></td>
					</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<h4>Search for a different researcher:</h4>
	<form  method="post">
	<input type = "hidden" name= "t_id" value = "<?php echo $_POST['t_id']?>" />
	<p>Name: <input type='text' name = 'researcher_name'/></p>
	<p><input type='submit' value='Search existing students' formaction="search_researcher.php"/></p>
	<p><input type='submit' value='Add team member as a new student' formaction="create_researcher.php"/></p>
	</form>

<?php /* ------------------- PAGE CONTENT ENDS HERE ------------------- */ ?>

<?php
    include 'templates/footer.php';
?>