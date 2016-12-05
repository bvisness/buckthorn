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
    } else if (is_logged_in() && !is_team_selected() && !$header_options['is_team_select_page']) {
        redirect('teamselect.php');
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title><?php echo $header_options['title'] ?></title>
</head>

<body>
