<?php

require_once 'mysql.php';
require_once 'session.php';

function login($id)
{
    $_SESSION['r_id'] = $id;
}

function set_team($team_id)
{
    $_SESSION['t_id'] = $team_id;
}

function logout()
{
    if (isset($_SESSION['r_id'])) {
        unset($_SESSION['r_id']);
    }
    if (isset($_SESSION['t_id'])) {
        unset($_SESSION['t_id']);
    }
}

function get_user($id = null)
{
    // Use the currently logged-in user by default
    if ($id === null && isset($_SESSION['r_id'])) {
        $id = $_SESSION['r_id'];
    }

    // Return null if no id was set at all
    if (!isset($id)) {
        return null;
    }

    // Get researcher by this id
    $researcher = query_first('SELECT * FROM researcher WHERE r_id = %r_id%', [
        'r_id' => $id,
    ]);

    return $researcher;
}

function get_team($id = null)
{
    // Use the currently selected team by default
    if ($id === null && isset($_SESSION['t_id'])) {
        $id = $_SESSION['t_id'];
    }

    // Return null if no id was set at all
    if (!isset($id)) {
        return null;
    }

    // Get team by this id
    $team = query_first('SELECT * FROM team WHERE t_id = %t_id%', [
        't_id' => $id,
    ]);

    return $team;
}

function is_logged_in()
{
    return !empty(get_user());
}

function is_team_selected()
{
    return !empty(get_team());
}

function is_admin()
{
    return $_SESSION['t_id'] === 0;
}
