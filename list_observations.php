<?php
    require_once 'utilities/env.php';
    require_once 'utilities/output_helpers.php';
?>
<?php
    $header_options['title'] = 'All Observations';
    $header_options['active_menu_id'] = 'list_observations';
    include 'templates/header.php';
?>

<?php /* ------------------- PAGE CONTENT BEGINS HERE ------------------- */ ?>

    <?php /* View list of observations */ ?>

    <?php
        if (is_admin()):
            $observations = query('SELECT o_id, t_id, o_date FROM observation');
        else:
            $observations = query('SELECT o_id, t_id, o_date FROM observation WHERE t_id = %t_id%', [
                't_id' => $_SESSION['t_id']
            ]);
        endif;
    ?>

    <h1>Observations</h1>

    <table class="pretty-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Team Name</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($observations as $observation): ?>
                <?php
                    $team = query_first('SELECT t_name FROM team WHERE t_id = %t_id%', [
                        't_id' => $observation['t_id'],
                    ]);
                    $link_url = url("view_observation.php?id=$observation[o_id]");
                ?>
                <tr>
                    <td><?php echo $observation['o_date'] ?></td>
                    <td><?php echo $team['t_name'] ?></td>
                    <td><a href="<?php echo $link_url ?>">View</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php /* ------------------- PAGE CONTENT ENDS HERE ------------------- */ ?>
<?php
    include 'templates/footer.php';
?>
