<?php

require_once __DIR__ . '/../vendor/autoload.php';
use josegonzalez\Dotenv\Loader as EnvLoader;

$loader = new EnvLoader('.env');
$loader->parse();
$loader->toEnv();

function get_db_connection()
{
    $con = mysqli_connect($_ENV['DB_HOST'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DB_NAME'], $_ENV['DB_PORT']);
    if (!$con) {
        return false;
    }

    return $con;
}
