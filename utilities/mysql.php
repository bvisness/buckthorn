<?php

require_once 'env.php';

$_con = null;

function get_db_connection()
{
    if (!empty($_con)) {
        return $_con;
    }

    $con = mysqli_connect($_ENV['DB_HOST'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DB_NAME'], $_ENV['DB_PORT']);
    if (!$con) {
        return false;
    }

    $_con = $con;
    return $_con;
}

function escape($value)
{
    $con = get_db_connection();

    return mysqli_real_escape_string($con, $value);
}

function query($query, $parameters = [])
{
    $query = preg_replace_callback(
        '/%([^%]+)%/',
        function ($matches) use ($parameters) {
            if (!array_key_exists($matches[1], $parameters)) {
                return $matches[0];
            }

            return escape($parameters[$matches[1]]);
        },
        $query
    );

    $con = get_db_connection();
    $result = $con->query($query);
    if ($result === false) {
        return false;
    }

    if ($result === true) {
        // This was not a query that returns results
        return true;
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}
