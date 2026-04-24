<?php
$uri = urldecode(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
$file = ltrim($uri, "/");

// Default to index.html
if ($file === "" || $file === "/") {
    $file = "index.html";
}

// Full path
$path = __DIR__ . "/" . $file;

// Serve PHP files
if (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === "php") {
    require $path;
    return true;
}

// Serve existing static files (html, css, js, images etc.)
if (is_file($path)) {
    return false;
}

// If path is a directory look for index file inside
if (is_dir($path)) {
    $index = rtrim($path, "/") . "/index.html";
    if (is_file($index)) {
        include $index;
        return true;
    }
}

// Fallback to index.html only for truly unknown routes
include __DIR__ . "/index.html";
