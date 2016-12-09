<?php

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
    'c_notes',
];

$insert_notes_fields = [
    'o_id',
    'n_habitat',
    'n_general',
    'n_biodiversity',
    'n_competition',
];

function generate_insert_query($table, $fields)
{
    $percented_fields = array_map(function ($field) {
        return '%' . $field . '%';
    }, $fields);

    return "INSERT INTO $table (" . implode($fields, ', ') . ') VALUES (' . implode($percented_fields, ', ') . ')';
}
