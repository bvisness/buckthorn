<?php

/**
 * This file contains information specifically about buckthorn queries. The
 * arrays of column names are used both to generate insert queries and to help
 * with form validation in create_observation.php.
 */

require_once 'mysql.php';

$insert_observation_fields = [
    't_id',
    'o_date',
    'o_latitude',
    'o_longitude',
    'o_quadrantsize',
    'o_numstems',
    'o_foliar',
    'o_circumference',
    'o_photos',
];

$insert_bio_count_fields = [
    'o_id',
    'bc_count',
    'bc_is_buckthorn',
];

$insert_competition_fields = [
    'o_id',
    'c_dbh_buckthorn',
    'c_dbh_neighbor_b',
    'c_dbh_neighbor_nb',
    'c_distance_b',
    'c_distance_nb',
];

$insert_notes_fields = [
    'o_id',
    'n_habitat',
    'n_general',
    'n_biodiversity',
    'n_competition',
];

// Yes, we calculate the Shannon-Wiener index through a single SQL query. The
// weird syntax is simply a construct in PHP that lets you make strings that
// span multiple lines.
$shannon_wiener_query = <<<'EOD'
SELECT
    -SUM(
            (bc_count / (SELECT SUM(bc_count) FROM bio_count WHERE o_id = %o_id%))
        * LN(bc_count / (SELECT SUM(bc_count) FROM bio_count WHERE o_id = %o_id%))
    ) as H
FROM bio_count
WHERE o_id = %o_id%
EOD;

/**
 * Generates an INSERT INTO query string based on a table name and an array of
 * column names. The VALUES part of the string will have %placeholders% so that
 * it can be directly used with the query() function in mysql.php.
 */
function generate_insert_query($table, $fields)
{
    $percented_fields = array_map(function ($field) {
        return "'%" . $field . "%'";
    }, $fields);

    return "INSERT INTO $table (" . implode($fields, ', ') . ') VALUES (' . implode($percented_fields, ', ') . ')';
}
