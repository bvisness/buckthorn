<?php
    require_once 'utilities/env.php';
    require_once 'utilities/output_helpers.php';
    require_once 'utilities/user.php';
?>
<?php
    $header_options['title'] = 'View Team';
    include 'templates/header.php';
?>

<?php /* ------------------- PAGE CONTENT BEGINS HERE ------------------- */ ?>

	<?php
        if (is_admin()) :
            $current_team = query('SELECT * FROM researcher natural join membership where t_id = %t_id%', [
            't_id' => $_GET['id'],
            ]);
        else :
            $current_team = query('SELECT * FROM researcher natural join membership where t_id = %t_id% and (end is null or end > NOW())', [
            't_id' => $_GET['id'],
            ]);
        endif;
    ?>
	<?php 
		$team_name = query_first('SELECT * FROM team WHERE t_id = %t_id%', [
            't_id' => $_GET['id'],
        ]);
	?>
	<h1><?php echo $team_name['t_name']?></h1>
	
	<table>
        <thead>
            <tr>
				<th>Team member ID</th>
                <th>Team member name</th>
                <th>Begin Date</th>
				<th>End Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($current_team as $member): ?>
                <tr>
                    <td><?php echo $member['r_id'] ?></td>
                    <td><?php echo $member['r_name'] ?></td>
                    <td><?php echo $member['begin'] ?></td>
                    <td><?php  if(empty($member['end']) && is_admin()){ 
										$set_end_date_url = url("deactivate_researcher.php?m_id=$member[m_id]"); ?>
										<a href = <?php echo $set_end_date_url ?>> Deactivate <a/>
							<?php }
										else{ 
											echo $member['end'];
							            }
										?>
					</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (is_admin()): ?>
    	<p><h3>Add a team member</h3></p>
    	<form  method="post">
    	<input type = "hidden" name= "t_id" value = "<?php echo $_GET['id']?>" />
    	<p>Name: <input type='text' name = 'researcher_name'/></p>
    	<p><input type='submit' value='Search existing students' formaction="search_researcher.php"/></p>
    	<p><input type='submit' value='Add team member as a new student' formaction="create_researcher.php"/></p>
    	</form>
    <?php endif; ?>

<?php /* ------------------- PAGE CONTENT ENDS HERE ------------------- */ ?>
<?php
    include 'templates/footer.php';
?>