<?php 

    require_once 'utilities/mysql.php';
    require_once 'utilities/output_helpers.php';
    require_once 'utilities/user.php';
	$o_id = $_POST['o_id'];
	$team = get_team();
	$t_id = $team['t_id'];
    if (is_admin()):
		$delete_observation_query = query("DELETE FROM observation
															WHERE o_id = '%o_id%'",[
															'o_id' => $_POST['o_id'],
															]);
	else:
		$delete_observation_query_protected = query("DELETE FROM observation
																			WHERE o_id = '%o_id%'
																			AND t_id = '%t_id%'",[
																			'o_id' => $_POST['o_id'],
																			't_id' => $t_id,
																			]);
	endif;
	redirect('/list_observations.php');