<?php

/** 
 * This file contains functions related to user authentication and login.
 */

require_once 'mysql.php';
require_once 'session.php';

/**
 * Sets the active user account to the ID given.
 */
function login($id)
{
    $_SESSION['r_id'] = $id;
}

/**
 * Sets the active team to the team given.
 */
function set_team($team_id)
{
    $_SESSION['t_id'] = $team_id;
}

/**
 * Logs out the current user (if any) and unsets the active team (if any)
 */
function logout()
{
    if (isset($_SESSION['r_id'])) {
        unset($_SESSION['r_id']);
    }
    if (isset($_SESSION['t_id'])) {
        unset($_SESSION['t_id']);
    }
}

/**
 * Gets information about the current user from the database.
 */
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

/**
 * Gets information about the current team from the database.
 */
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

/**
 * Returns whether a user is currently logged in.
 */
function is_logged_in()
{
    return !empty(get_user());
}

/**
 * Returns whether a team has been selected.
 */
function is_team_selected()
{
    return !empty(get_team());
}

/**
 * Returns whether the currently logged-in user is an admin.
 */
function is_admin()
{
    return (int) $_SESSION['t_id'] === 0;
}
