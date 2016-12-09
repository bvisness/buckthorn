<?php

require_once 'env.php';

function url($path)
{
    $base = '';
    if (isset($_ENV['SITE_ROOT'])) {
        $base = $_ENV['SITE_ROOT'];
    }

    return rtrim($base, '/') . '/' . ltrim($path, '/');
}

function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

function debug_table($rows)
{
?>
    <table>
        <thead>
            <tr>
                <?php foreach (reset($rows) as $column_name => $value): ?>
                    <td><?php echo $column_name ?></td>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <?php foreach ($row as $column_name => $value): ?>
                        <td><?php echo $value ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php
}

function e($output)
{
    return htmlspecialchars($output);
}
