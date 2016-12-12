<?php 

    require_once 'utilities/mysql.php';
    require_once 'utilities/output_helpers.php';
	
	$deactivation_query = query('UPDATE membership
													SET end = now()
													WHERE m_id = %m_id%',[
													'm_id' => $_GET['m_id'],
													]);
	$team = query_first('SELECT * FROM membership WHERE m_id = %m_id%',[
													'm_id' => $_GET['m_id'],
													]);
	$manage_team_url = url("manage_team.php?id=$team[t_id]");
	redirect($manage_team_url);