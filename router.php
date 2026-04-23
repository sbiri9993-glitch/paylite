<?php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Remove leading slash
$file = ltrim($uri, '/');

// Default to index.html
if ($file === '' || $file === '/') {
    $file = 'index.html';
}

// Full path to the file
$path = __DIR__ . '/' . $file;

// Serve PHP files
if (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === 'php') {
    require $path;
    return true;
}

// Serve static files (html, css, js, images)
if (is_file($path)) {
    return false;
}

// Fallback — serve index.html
include __DIR__ . '/index.html';