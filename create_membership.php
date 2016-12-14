<?php 

    require_once 'utilities/mysql.php';
    require_once 'utilities/output_helpers.php';
    require_once 'utilities/user.php';
    if (is_admin()):
		$existing_membership = query("SELECT * FROM membership
														WHERE t_id = '%t_id%'
														AND r_id = '%r_id%'
														AND end is null",[
														'r_id' => $_GET['r_id'],
														't_id' => $_GET['t_id'],
														]);
		if(empty($existing_membership)){
			$new_teammate_query = query('INSERT INTO membership (r_id, t_id, begin)
															VALUES (%r_id%,%t_id%, NOW())',[
															'r_id' => $_GET['r_id'],
															't_id' => $_GET['t_id'],
															]);
		}
		$manage_team_url = url("manage_team.php?id=$_GET[t_id]");
		redirect($manage_team_url);
	else:
		redirect('/');
	endif;