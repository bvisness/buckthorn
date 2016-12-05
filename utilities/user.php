<?php

require_once 'mysql.php';
require_once 'session.php';

function login($id)
{
    $_SESSION['r_id'] = $id;
}

function logout()
{
    if (isset($_SESSION['r_id'])) {
        unset($_SESSION['r_id']);
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

    // Get researchers with this id
    $matching_researchers = query('SELECT * FROM researcher WHERE r_id = %r_id%', [
        'r_id' => $id,
    ]);

    // Return stuff!
    if (empty($matching_researchers)) {
        return null;
    } else {
        return array_shift($matching_researchers);
    }
}

function is_logged_in()
{
    return !empty(get_user());
}
