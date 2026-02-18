<?php
/**
 * Script direto para testar envio de email
 * Autor: Thiago Mourão
 * Data: 2026-02-17
 */

error_reporting(0);
ini_set('display_errors', 0);
ob_start();

session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/lib/Mailer.php';

ob_end_clean();
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Metodo nao permitido'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$email = $_POST['email'] ?? '';

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Email invalido'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $mailer = new Mailer();
    
    ob_start();
    $success = $mailer->sendTest($email);
    $output = ob_get_clean();
    
    if ($success) {
        echo json_encode([
            'success' => true,
            'message' => "Email de teste enviado para {$email}! Verifique sua caixa de entrada (e spam)."
        ], JSON_UNESCAPED_UNICODE);
    } else {
        $errorMsg = 'Erro ao enviar email. Verifique as configuracoes SMTP.';
        if (!empty($output)) {
            $errorMsg .= ' Debug: ' . $output;
        }
        echo json_encode([
            'success' => false,
            'message' => $errorMsg
        ], JSON_UNESCAPED_UNICODE);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
} catch (Error $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro fatal: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
