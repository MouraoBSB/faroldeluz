<?php
/**
 * NewsletterController
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 */

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/models/NewsletterSubscriber.php';

class NewsletterController extends Controller {
    public function subscribe() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['ok' => false, 'message' => 'Método não permitido'], 405);
            return;
        }
        
        $name = sanitize_input($_POST['name'] ?? '');
        $email = sanitize_input($_POST['email'] ?? '');
        
        if (empty($name) || empty($email)) {
            $this->json(['ok' => false, 'message' => 'Nome e e-mail são obrigatórios']);
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->json(['ok' => false, 'message' => 'E-mail inválido']);
            return;
        }
        
        $newsletterModel = new NewsletterSubscriber();
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        
        try {
            $newsletterModel->subscribe($name, $email, $ip, $userAgent);
            $this->json(['ok' => true, 'message' => 'Inscrição realizada com sucesso!']);
        } catch (Exception $e) {
            $this->json(['ok' => false, 'message' => 'Erro ao processar inscrição']);
        }
    }
    
    public function unsubscribe() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['ok' => false, 'message' => 'Método não permitido'], 405);
            return;
        }
        
        $email = sanitize_input($_POST['email'] ?? '');
        
        if (empty($email)) {
            $this->json(['ok' => false, 'message' => 'E-mail é obrigatório']);
            return;
        }
        
        $newsletterModel = new NewsletterSubscriber();
        
        if ($newsletterModel->unsubscribe($email)) {
            $this->json(['ok' => true, 'message' => 'Inscrição cancelada com sucesso']);
        } else {
            $this->json(['ok' => false, 'message' => 'E-mail não encontrado']);
        }
    }
}
