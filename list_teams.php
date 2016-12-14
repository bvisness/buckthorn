<?php
    require_once 'utilities/mysql.php';
    require_once 'utilities/output_helpers.php';
    require_once 'utilities/user.php';
?>

<?php
    $header_options['title'] = 'All Teams';
    include 'templates/header.php';
?>

<?php /* ------------------- PAGE CONTENT BEGINS HERE ------------------- */ ?>

    <h1>Teams</h1>

    <?php
        if (is_admin()):
            $teams = query('SELECT * FROM team');
        else:
            $teams = query('SELECT * FROM team WHERE t_id = %t_id%', [
                't_id' => $_SESSION['t_id']
            ]);
        endif;
    ?>

    <table>
        <thead>
            <tr>
                <th>Team Name</th>
                <th>Researchers</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($teams as $team): ?>
                <tr>
					<?php $manage_team_url = url("manage_team.php?id=$team[t_id]");?>
                    <td><a href=<?php echo $manage_team_url?>><?php echo $team['t_name'] ?></td>
                    <td>
                        <?php
                            $researchers = query('SELECT researcher.r_id, r_name FROM membership JOIN researcher ON membership.r_id = researcher.r_id WHERE t_id = %t_id% and (end is null or end > NOW())', [
                                't_id' => $team['t_id'],
                            ]);
                                
                            $researcher_names = array_column($researchers, 'r_name');
                            echo implode(', ', $researcher_names);
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (is_admin()): ?>
	   <h3>Create a new team</h3>
	   <form  method="post" action="create_team.php">
	   <p>Name: <input type='text' name = 'team_name'/></p>
	   <p><input type='submit' value='Create Team' /></p>
	   </form>
    <?php endif; ?>

<?php /* ------------------- PAGE CONTENT ENDS HERE ------------------- */ ?>

<?php
    include 'templates/footer.php';
?>
