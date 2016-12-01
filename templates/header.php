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
</head>

<body>
