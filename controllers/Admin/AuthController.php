<?php
/**
 * Admin AuthController
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 */

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/models/User.php';

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
        $csrfToken = $_POST['csrf_token'] ?? '';
        
        if (!verify_csrf_token($csrfToken)) {
            $_SESSION['admin_error'] = 'Token de segurança inválido.';
            $this->redirect(base_url('admin'));
            return;
        }
        
        $userModel = new User();
        $user = $userModel->authenticate($email, $password);
        
        if ($user) {
            $_SESSION[ADMIN_SESSION_NAME] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ];
            $this->redirect(base_url('admin/dashboard'));
        } else {
            $_SESSION['admin_error'] = 'E-mail ou senha inválidos.';
            $this->redirect(base_url('admin'));
        }
    }
    
    public function logout() {
        unset($_SESSION[ADMIN_SESSION_NAME]);
        session_destroy();
        $this->redirect(base_url('admin'));
    }
}
