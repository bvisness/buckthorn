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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="normalize.css">
    <link rel="stylesheet" type="text/css" href="assets/styles.css">
</head>

<body>
    <nav>
        <a class="brand" href="<?php echo url('/') ?>">Buckthorn</a>
        <ul>
            <li class="active"><a href="logout.php">Logout</a></li>
            <li><a href="create_observation.php">Record an observation</a></li>
            <li><a href="list_observations.php">View observations</a></li>
            <li><a href="list_teams.php">View teams</a></li>
        </ul>
    </nav>
    <main>
