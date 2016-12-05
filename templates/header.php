<!DOCTYPE html>
<html>

<?php
if (!isset($header_options)) {
    $header_options = [];
}
$header_options += [
    'title' => '',
];
?>

<head>
    <title><?php echo $header_options['title'] ?></title>
    <link rel="stylesheet" type="text/css" href="normalize.css"></link>
</head>

<body>
