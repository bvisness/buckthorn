<?php 

    require_once 'utilities/mysql.php';
    require_once 'utilities/output_helpers.php';
    require_once 'utilities/user.php';
    if (is_admin()):
		$r_name = $_POST['researcher_name'];
		$t_id = $_POST['t_id'];
		$new_researcher_query = query('INSERT INTO researcher (r_name)
														VALUES ("%name%")',[
														'name' => $r_name,
														]);
		$researcher = query_first('SELECT MAX(r_id) as r_id
										FROM researcher
										WHERE r_name = "%r_name%"'
									,[
										'r_name' => $r_name,					
									]);
		$researcher_id =$researcher['r_id'];
		$create_membership_url = url("create_membership.php?r_id=$researcher_id&t_id=$t_id");
		redirect($create_membership_url);
	else:
		redirect('/');
	endif;