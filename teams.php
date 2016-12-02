<?php
    require 'utilities/mysql.php';
    require 'utilities/output_helpers.php';
?>

<?php
    $header_options['title'] = 'Teams';
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
                <td>Team Name</td>
                <td>Researchers</td>
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
                            
                            $researcher_htmls = [];
                            foreach ($researchers as $researcher) {
                                $link = "<a href=\"" . url("/researchers.php?id=$researcher[r_id]") . "\">$researcher[r_name]</a>";
                                $researcher_htmls[] = $link;
                            }

                            echo implode(', ', $researcher_htmls);
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
