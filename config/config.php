<?php
/**
 * Configurações Globais
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 */

if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', $_ENV['BASE_URL'] ?? 'http://localhost:3000');
define('SITE_NAME', $_ENV['SITE_NAME'] ?? 'Farol de Luz');
define('SITE_SLOGAN', $_ENV['SITE_SLOGAN'] ?? 'A Luz do Consolador para os dias de hoje!');

define('DB_CONFIG', [
    'host' => $_ENV['DB_HOST'] ?? '186.209.113.101',
    'port' => $_ENV['DB_PORT'] ?? '3306',
    'database' => $_ENV['DB_NAME'] ?? 'cemaneto_site_faroldeluz',
    'username' => $_ENV['DB_USER'] ?? 'cemaneto_site_faroldeluz',
    'password' => $_ENV['DB_PASS'] ?? 'EDM7avc8cax!gfw*qjp',
    'charset' => 'utf8mb4'
]);

define('ADMIN_SESSION_NAME', $_ENV['ADMIN_SESSION_NAME'] ?? 'farol_admin_session');
define('ADMIN_SESSION_LIFETIME', $_ENV['ADMIN_SESSION_LIFETIME'] ?? 7200);

date_default_timezone_set('America/Sao_Paulo');

error_reporting(E_ALL);
ini_set('display_errors', 1);

function env($key, $default = null) {
    return $_ENV[$key] ?? $default;
}

function base_url($path = '') {
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

function asset_url($path) {
    return base_url('assets/' . ltrim($path, '/'));
}

function redirect($url, $statusCode = 302) {
    header('Location: ' . $url, true, $statusCode);
    exit;
}

function csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function sanitize_input($data) {
    if (is_array($data)) {
        return array_map('sanitize_input', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function extract_youtube_id($url) {
    $patterns = [
        '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/',
        '/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/',
        '/youtu\.be\/([a-zA-Z0-9_-]+)/',
        '/youtube\.com\/shorts\/([a-zA-Z0-9_-]+)/'
    ];
    
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
    }
    
    return null;
}

function generate_slug($text) {
    $text = strtolower($text);
    $text = preg_replace('/[áàâãä]/u', 'a', $text);
    $text = preg_replace('/[éèêë]/u', 'e', $text);
    $text = preg_replace('/[íìîï]/u', 'i', $text);
    $text = preg_replace('/[óòôõö]/u', 'o', $text);
    $text = preg_replace('/[úùûü]/u', 'u', $text);
    $text = preg_replace('/[ç]/u', 'c', $text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    return $text;
}
