<?php
/**
 * Router para servidor PHP embutido
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:26:00
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js|ico|svg|woff|woff2|ttf|eot)$/', $uri)) {
    return false;
}

$_GET['url'] = $uri;
$_SERVER['SCRIPT_NAME'] = '/index.php';
require_once __DIR__ . '/index.php';
