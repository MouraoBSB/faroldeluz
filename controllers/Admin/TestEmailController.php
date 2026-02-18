<?php
/**
 * TestEmailController - Testar envio de emails
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-17
 */

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/lib/Mailer.php';

class AdminTestEmailController extends Controller {
    
    public function send() {
        header('Content-Type: application/json; charset=UTF-8');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Metodo nao permitido'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!verify_csrf_token($csrfToken)) {
            echo json_encode([
                'success' => false,
                'message' => 'Token de seguranca invalido'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        $email = sanitize_input($_POST['email'] ?? '');
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'success' => false,
                'message' => 'Email invalido'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        try {
            $mailer = new Mailer();
            $success = $mailer->sendTest($email);
            
            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => "Email de teste enviado para {$email}! Verifique sua caixa de entrada (e spam)."
                ], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Erro ao enviar email. Verifique as configuracoes SMTP e tente novamente.'
                ], JSON_UNESCAPED_UNICODE);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Erro: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
        
        exit;
    }
}
