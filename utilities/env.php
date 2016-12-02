<?php

require_once __DIR__ . '/../vendor/autoload.php';
use josegonzalez\Dotenv\Loader as EnvLoader;

$loader = new EnvLoader('.env');
$loader->parse();
$loader->toEnv();
