<?php
    require_once 'utilities/mysql.php';
    require_once 'utilities/output_helpers.php';
    require_once 'utilities/session.php';
    require_once 'utilities/user.php';
?>

<?php
    // Anything that can redirect must be up here, before any content is sent
    // to the browser.

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['t_id'])) {
            $team = query_first('SELECT t_id FROM team WHERE t_id = %t_id%', [
                't_id' => $_POST['t_id'],
            ]);
            if (empty($team)) {
                $_POST['error'] = "No team found with ID $_POST[t_id]";
            } else {
                set_team($_POST['t_id']);
                redirect(url('/'));
            }
        }
    }
?>

<?php
    $header_options['title'] = 'Sign In';
    $header_options['is_team_select_page'] = true;
    include 'templates/header.php';
?>

<?php /* ------------------- PAGE CONTENT BEGINS HERE ------------------- */ ?>

    <h1>Select a team:</h1>

    <?php if (!empty($_POST['error'])): ?>
        <p class="error">Error: <?php echo $_POST['error'] ?></p>
    <?php endif; ?>

    <?php
        $researcher = get_user();
        $teams = query('SELECT * FROM researcher NATURAL JOIN membership NATURAL JOIN team WHERE r_id = %r_id% AND (`end` IS NULL OR `end` > NOW())', [
            'r_id' => $researcher['r_id'],
        ]);
    ?>

    <form action="<?php echo url('teamselect.php'); ?>" method="POST">
        <select name="t_id">
            <?php foreach ($teams as $team): ?>
                <option value="<?php echo $team['t_id'] ?>"><?php echo $team['t_name'] ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Select Team">
    </form>

<?php /* ------------------- PAGE CONTENT ENDS HERE ------------------- */ ?>

<?php
    include 'templates/footer.php';
?>
