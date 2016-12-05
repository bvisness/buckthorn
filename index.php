<?php
    require_once 'utilities/mysql.php';
    require_once 'utilities/user.php';
?>

<?php
    $header_options['title'] = 'Hello World!';
    include 'templates/header.php';
?>

<?php /* ------------------- PAGE CONTENT BEGINS HERE ------------------- */ ?>

    <p>Hello world!</p>

    <?php
        $con = get_db_connection();

        if (!$con) {
            echo 'Error: Unable to connect to MySQL.' . PHP_EOL;
            echo 'Debugging errno: ' . mysqli_connect_errno() . PHP_EOL;
            echo 'Debugging error: ' . mysqli_connect_error() . PHP_EOL;
            exit;
        }

        echo 'Success: A proper connection to MySQL was made!' . PHP_EOL;
        echo 'Host information: ' . mysqli_get_host_info($con) . PHP_EOL;

        mysqli_close($con);
    ?>

    <?php if (is_logged_in()): ?>
        <?php
            $researcher = get_user();
        ?>
        <p><?php echo $researcher['r_name'] ?> (id <?php echo $researcher['r_id'] ?>) is logged in.</p>
    <?php else: ?>
        <p>No one is logged in.</p>
    <?php endif; ?>

    <?php if (is_team_selected()): ?>
        <?php
            $team = get_team();
        ?>
        <p>Team <?php echo $team['t_id'] ?> ("<?php echo $team['t_name'] ?>") is selected.</p>
    <?php else: ?>
        <p>No team is selected.</p>
    <?php endif; ?>

<?php /* ------------------- PAGE CONTENT ENDS HERE ------------------- */ ?>

<?php
    include 'templates/footer.php';
?>
