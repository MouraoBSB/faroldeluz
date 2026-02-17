<?php
session_start();
require_once __DIR__ . '/config/config.php';

echo "<h1>Debug Info</h1>";
echo "<pre>";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n";
echo "_GET['url']: " . ($_GET['url'] ?? 'não definido') . "\n";
echo "\nTodas as variáveis GET:\n";
print_r($_GET);
echo "\nBASE_PATH: " . BASE_PATH . "\n";
echo "\nArquivo AuthController existe? " . (file_exists(BASE_PATH . '/controllers/Admin/AuthController.php') ? 'SIM' : 'NÃO') . "\n";
echo "</pre>";
