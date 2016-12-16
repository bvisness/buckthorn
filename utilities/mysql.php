<?php

/**
 * This file contains helpful functions for interacting with a MySQL database.
 * Of particular interest is the query() function (and its partner,
 * query_first()), which provide an easy mechanism for escaping values for use
 * in an SQL query.
 */

require_once 'env.php';

$_con = null;

/**
 * Gets a connection to the database. This can be safely called as many times
 * as you want during the script's execution. If a connection is already open,
 * that existing connection will be used.
 *
 * @return mysqli The database connection object.
 */
function get_db_connection()
{
    global $_con;

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

register_shutdown_function(function () {
    global $_con;

    if (!empty($_con)) {
        $_con->close();
    }
});

/**
 * Escapes a value for safe use in a MySQL query.
 *
 * @param  mixed  $value The value to escape.
 * @return string        The escaped version of the value.
 */
function escape($value, $name = '')
{
    if (is_array($value)) {
        if (empty($name)) {
            throw new MySqlException('Cannot use an array as a value for a MySQL query.');
        } else {
            throw new MySqlException("Failed to escape `$name` for use in MySQL because it was an array.");
        }
    }

    $con = get_db_connection();

    return mysqli_real_escape_string($con, $value);
}

/**
 * Makes a query to the database and returns the result in a nice format.
 *
 * This function also does automatic escaping of values passed into it. The
 * function will automatically attempt to replace any instances of %name%
 * with the corresponding value in $parameters. For example, if the query
 * is the following:
 *
 * SELECT * FROM my_table WHERE my_column = %column_value%
 *
 * and $parameters is the following:
 *
 * [
 *     "column_value" => "'A dangerous string'; DROP TABLE my_table;"
 * ]
 *
 * then %column_value% will be replaced with an escaped version of the
 * dangerous string seen above.
 *
 * @param  string $query      The MySQL query to execute.
 * @param  array  $parameters An associative array of values to substitute
 *                            into the query. For each entry, the key is the
 *                            name of the attribute, and the value is the
 *                            value to substitute into the query.
 * @return boolean|array      This function will return one of the
 *                            following values:
 *                             - An array of associative arrays, if the query
 *                               was of the kind that returns results. The
 *                               outer array will have one entry for each row.
 *                               Each row is represented by an associative
 *                               array where the keys are column names and the
 *                               values are the corresponding values.
 *                             - true if the query was successful but returned
 *                               no results. (This can happen with queries like
 *                               DELETE, for example.)
 *                             - false if an error occurred.
 */
function query($query, $parameters = [])
{
    $query = preg_replace_callback(
        '/%([^%]+)%/',
        function ($matches) use ($parameters) {
            if (!array_key_exists($matches[1], $parameters)) {
                return $matches[0];
            }

            return escape($parameters[$matches[1]], $matches[1]);
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

/**
 * Same as the query function, but in the case where query would return
 * multiple results, this function returns only the first object in the
 * result set.
 */
function query_first($query, $parameters = [])
{
    $result = query($query, $parameters);

    if ($result === false || $result === true) {
        return $result;
    }

    if (empty($result)) {
        return null;
    } else {
        return array_shift($result);
    }
}

class MySqlException extends Exception
{
    public function __construct($message, Exception $previous = null)
    {
        $con = get_db_connection();
        $message .= ' MySQL message: `' . $con->error . '` (code ' . $con->errno . ')';

        parent::__construct($message, $con->errno, $previous);
    }
}
