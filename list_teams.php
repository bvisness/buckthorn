<?php
    require 'utilities/mysql.php';
    require 'utilities/output_helpers.php';
?>

<?php
    $header_options['title'] = 'All Teams';
    include 'templates/header.php';
?>

<?php /* ------------------- PAGE CONTENT BEGINS HERE ------------------- */ ?>

    <h1>Teams</h1>

    <?php
        $teams = query('SELECT * FROM team');
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
                    <td><?php echo $team['t_name'] ?></td>
                    <td>
                        <?php
                            $researchers = query('SELECT researcher.r_id, r_name FROM membership JOIN researcher ON membership.r_id = researcher.r_id WHERE t_id = %t_id%', [
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

<?php /* ------------------- PAGE CONTENT ENDS HERE ------------------- */ ?>

<?php
    include 'templates/footer.php';
?>
