<?php

/**
 * This file contains helpful functions for producing HTML output.
 */

require_once 'env.php';

/**
 * Uses the site root defined in .env to produce an environment-correct
 * absolute URL.
 */
function url($path)
{
    $base = '';
    if (isset($_ENV['SITE_ROOT'])) {
        $base = $_ENV['SITE_ROOT'];
    }

    return rtrim($base, '/') . '/' . ltrim($path, '/');
}

/**
 * Redirects the user to a URL. Plain and simple.
 */
function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

/**
 * Escapes user values for HTML output.
 */
function e($output)
{
    return htmlspecialchars($output);
}
