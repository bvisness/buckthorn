<?php
    require_once 'utilities/env.php';
    require_once 'utilities/output_helpers.php';
?>
<?php
    $header_options['title'] = 'View Observation';
    include 'templates/header.php';
?>

<?php /* ------------------- PAGE CONTENT BEGINS HERE ------------------- */ ?>

	<?php
        $current_team = query('SELECT * FROM researcher natural join membership where t_id = %t_id% and (end > NOW() or end is null)', [
            't_id' => $_GET['id'],
        ]);
    ?>
	<?php 
		$team_name = query_first('SELECT * FROM team WHERE t_id = %t_id%', [
            't_id' => $_GET['id'],
        ]);
	?>
	<h1><?php echo $team_name['t_name']?></h1>
	
	<table>
        <thead>
            <tr>
				<th>Team member ID</th>
                <th>Team member name</th>
                <th>Begin Date</th>
				<th>End Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($current_team as $member): ?>
                <tr>
                    <td><?php echo $member['r_id'] ?></td>
                    <td><?php echo $member['r_name'] ?></td>
                    <td><?php echo $member['begin'] ?></td>
                    <td><?php echo $member['end'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php /* ------------------- PAGE CONTENT ENDS HERE ------------------- */ ?>
<?php
    include 'templates/footer.php';
?>