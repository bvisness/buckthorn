<?php
    require_once 'utilities/mysql.php';
    require_once 'utilities/output_helpers.php';
    require_once 'utilities/session.php';
    require_once 'utilities/user.php';
?>

<?php
    // Handle POST requests (meaning the user actually did log in.) We must do
    // all of this before any content is sent to the user, or redirects will
    // not work!
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['r_id'])) {
            $researcher = get_user($_POST['r_id']);
            if (empty($researcher)) {
                $_POST['error'] = "No researcher found with ID $_POST[r_id]";
            } else {
                login($_POST['r_id']);
                redirect(url('/'));
            }
        }
    }
?>

<?php
    $header_options['title'] = 'Sign In';
    include 'templates/header.php';
?>

<?php /* ------------------- PAGE CONTENT BEGINS HERE ------------------- */ ?>

    <h1>Hi, who are you?</h1>

    <?php if (!empty($_POST['error'])): ?>
        <p class="error">Error: <?php echo $_POST['error'] ?></p>
    <?php endif; ?>

    <?php
        $researchers = query('SELECT * FROM researcher');

        // sort researchers by name
        usort($researchers, function ($a, $b) {
            if ($a['r_name'] < $b['r_name']) {
                return -1;
            } else if ($a['r_name'] > $b['r_name']) {
                return 1;
            } else {
                return 0;
            }
        });
    ?>

    <form action="<?php echo url('login.php') ?>" method="POST">
        <select name="r_id">
            <?php foreach ($researchers as $researcher): ?>
                <option value="<?php echo $researcher['r_id'] ?>"><?php echo $researcher['r_name'] ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Log In">
    </form>

<?php /* ------------------- PAGE CONTENT ENDS HERE ------------------- */ ?>

<?php
    include 'templates/footer.php';
?>
