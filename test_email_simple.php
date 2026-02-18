<?php
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json; charset=UTF-8');

echo json_encode([
    'success' => true,
    'message' => 'Teste de JSON funcionando'
], JSON_UNESCAPED_UNICODE);
