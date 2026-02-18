<?php
/**
 * Admin AuthController
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 * Atualizado: 2026-02-18 (Segurança)
 */

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/models/User.php';
require_once BASE_PATH . '/lib/RateLimit.php';
require_once BASE_PATH . '/lib/SecurityLogger.php';
require_once BASE_PATH . '/lib/CSRF.php';

class AdminAuthController extends Controller {
    public function login() {
        if ($this->isAuthenticated()) {
            $this->redirect(base_url('admin/dashboard'));
            return;
        }
        
        $this->view('admin/login');
    }
    
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(base_url('admin'));
            return;
        }
        
        $email = sanitize_input($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        $logger = new SecurityLogger();
        $rateLimit = new RateLimit();
        
        // Verificar rate limiting (5 tentativas por 5 minutos)
        $identifier = $_SERVER['REMOTE_ADDR'] . ':login';
        if (!$rateLimit->check($identifier, 5, 300)) {
            $logger->rateLimitBlocked($identifier, 'login');
            $_SESSION['admin_error'] = 'Muitas tentativas de login. Aguarde 5 minutos e tente novamente.';
            $this->redirect(base_url('admin'));
            return;
        }
        
        // Validar CSRF
        CSRF::validate();
        
        $userModel = new User();
        $user = $userModel->authenticate($email, $password);
        
        if ($user) {
            // Login bem-sucedido
            $logger->loginAttempt($email, true);
            
            // Resetar rate limit após login bem-sucedido
            $rateLimit->reset($identifier);
            
            // Regenerar session ID por segurança
            session_regenerate_id(true);
            
            $_SESSION[ADMIN_SESSION_NAME] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ];
            
            // Armazenar fingerprint da sessão
            $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
            $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $_SESSION['last_activity'] = time();
            
            $this->redirect(base_url('admin/dashboard'));
        } else {
            // Login falhou
            $logger->loginAttempt($email, false, 'Credenciais inválidas');
            $_SESSION['admin_error'] = 'E-mail ou senha inválidos.';
            $this->redirect(base_url('admin'));
        }
    }
    
    public function logout() {
        $logger = new SecurityLogger();
        $logger->log('LOGOUT', 'INFO', [
            'user_id' => $_SESSION[ADMIN_SESSION_NAME]['id'] ?? null
        ]);
        
        unset($_SESSION[ADMIN_SESSION_NAME]);
        session_destroy();
        $this->redirect(base_url('admin'));
    }
}
