<?php
/**
 * ContactController
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 */

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/models/Setting.php';

class ContactController extends Controller {
    public function index() {
        $settingModel = new Setting();
        $settings = $settingModel->getAll();
        
        $this->view('contact/index', [
            'settings' => $settings
        ]);
    }
    
    public function send() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(base_url('contato'));
            return;
        }
        
        $name = sanitize_input($_POST['name'] ?? '');
        $email = sanitize_input($_POST['email'] ?? '');
        $message = sanitize_input($_POST['message'] ?? '');
        $csrfToken = $_POST['csrf_token'] ?? '';
        
        if (!verify_csrf_token($csrfToken)) {
            $_SESSION['contact_error'] = 'Token de segurança inválido.';
            $this->redirect(base_url('contato'));
            return;
        }
        
        if (empty($name) || empty($email) || empty($message)) {
            $_SESSION['contact_error'] = 'Todos os campos são obrigatórios.';
            $this->redirect(base_url('contato'));
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['contact_error'] = 'E-mail inválido.';
            $this->redirect(base_url('contato'));
            return;
        }
        
        $settingModel = new Setting();
        $contactEmail = $settingModel->get('contact_email', 'contato@faroldeluz.com.br');
        
        $subject = "Contato do site Farol de Luz - {$name}";
        $body = "Nome: {$name}\nE-mail: {$email}\n\nMensagem:\n{$message}";
        $headers = "From: {$email}\r\nReply-To: {$email}";
        
        if (mail($contactEmail, $subject, $body, $headers)) {
            $_SESSION['contact_success'] = 'Mensagem enviada com sucesso!';
        } else {
            $_SESSION['contact_error'] = 'Erro ao enviar mensagem. Tente novamente.';
        }
        
        $this->redirect(base_url('contato'));
    }
}
