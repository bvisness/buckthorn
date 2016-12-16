<?php

/**
 * This file simply loads environment-specific config values from the .env
 * file in the root directory.
 */

require_once __DIR__ . '/../vendor/autoload.php';
use josegonzalez\Dotenv\Loader as EnvLoader;

$loader = new EnvLoader('.env');
$loader->parse();
$loader->toEnv();
