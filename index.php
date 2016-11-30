<?php
require __DIR__ . '/vendor/autoload.php';

use josegonzalez\Dotenv\Loader as EnvLoader;

$loader = new EnvLoader('.env');
$loader->parse();
$loader->toEnv();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Hello world!</title>
</head>

<body>
    Hello world!

<?php
    $con = mysqli_connect($_ENV['DB_HOST'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DB_NAME'], $_ENV['DB_PORT']);

    if (!$con) {
        echo 'Error: Unable to connect to MySQL.' . PHP_EOL;
        echo 'Debugging errno: ' . mysqli_connect_errno() . PHP_EOL;
        echo 'Debugging error: ' . mysqli_connect_error() . PHP_EOL;
        exit;
    }

    echo 'Success: A proper connection to MySQL was made!' . PHP_EOL;
    echo 'Host information: ' . mysqli_get_host_info($con) . PHP_EOL;

    mysqli_close($con);
?>
</body>

</html>

