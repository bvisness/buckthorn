<?php
    require_once 'utilities/output_helpers.php';
    require_once 'utilities/user.php';

    // Everything in this block happens before output begins. That means it can
    // be used for redirection and stuff.

    if (!isset($header_options)) {
        $header_options = [];
    }
    $header_options += [
        'title' => '',
        'is_login_page' => false,
        'is_team_select_page' => false,
    ];
    
    if (!is_logged_in() && !$header_options['is_login_page']) {
        redirect('login.php');
    } else if (is_logged_in() && !is_team_selected() && !$header_options['is_login_page'] && !$header_options['is_team_select_page']) {
        redirect('teamselect.php');
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title><?php echo $header_options['title'] ?></title>
    <link rel="stylesheet" type="text/css" href="normalize.css"></link>
</head>

<body>
<a href="logout.php">Logout<a/>
<a href="create_observation.php">Create an observation<a/>
<a href="list_observations.php">View an observation<a/>
<a href="list_teams.php">View an list of the teams<a/>
<a href="manage_teams.php">Manage students and teams<a/>