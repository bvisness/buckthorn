<?php

require_once 'mysql.php';

// The string syntax below is called Nowdoc. It's super gross but it does let
// you span multiple lines.

$query_insert_observation = <<<'EOD'
INSERT
INTO observation ( t_id ,  o_date ,  o_latitude ,  o_longitude ,  o_quadrantsize ,  o_numstems ,  o_foliar ,  o_circumference ,  o_photos )
VALUES           (%t_id%, %o_date%, %o_latitude%, %o_longitude%, %o_quadrantsize%, %o_numstems%, %o_foliar%, %o_circumference%, %o_photos%)
EOD;

$query_insert_bio_count = <<<'EOD'
INSERT
INTO bio_count ( o_id ,  bc_count ,  bc_is_buckthorn )
VALUES         (%o_id%, %bc_count%, %bc_is_buckthorn%)
EOD;

$query_insert_competition = <<<'EOD'
INSERT
INTO competition ( o_id ,  c_dbh_buckthorn ,  c_dbh_neighbor_b ,  c_dbh_neighbor_nb ,  c_distance_b ,  c_distance_nb ,  c_notes )
VALUES           (%o_id%, %c_dbh_buckthorn%, %c_dbh_neighbor_b%, %c_dbh_neighbor_nb%, %c_distance_b%, %c_distance_nb%, %c_notes%)
EOD;

$query_insert_notes = <<<'EOD'
INSERT
INTO notes ( o_id ,  n_habitat ,  n_general ,  n_biodiversity ,  n_competition )
VALUES     (%o_id%, %n_habitat%, %n_general%, %n_biodiversity%, %n_competition%)
EOD;

// Functions for these queries will go here.
