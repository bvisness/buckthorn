<?php 

    require_once 'utilities/mysql.php';
    require_once 'utilities/output_helpers.php';
    require_once 'utilities/user.php';
    if (is_admin()):
		$existing_team = query("SELECT * FROM team
											WHERE t_name = '%t_name%'",[
											't_name' => $_POST['team_name'],
											]);
		if(empty($existing_team)){
			$new_team_query = query("INSERT INTO team (t_name)
															VALUES ('%t_name%')",[
															't_name' => $_POST['team_name'],
															]);
		}
	endif;
	redirect(url("/list_teams.php"));
