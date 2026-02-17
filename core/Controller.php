<?php
/**
 * Base Controller
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 */

class Controller {
    protected function view($viewPath, $data = []) {
        extract($data);
        $viewFile = BASE_PATH . '/views/' . $viewPath . '.php';
        
        if (!file_exists($viewFile)) {
            throw new Exception("View não encontrada: {$viewPath}");
        }
        
        require_once $viewFile;
    }
    
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
    
    protected function redirect($url, $statusCode = 302) {
        redirect($url, $statusCode);
    }
    
    protected function requireAuth() {
        if (!isset($_SESSION[ADMIN_SESSION_NAME])) {
            $this->redirect(base_url('admin'));
        }
    }
    
    protected function isAuthenticated() {
        return isset($_SESSION[ADMIN_SESSION_NAME]);
    }
    
    protected function getAuthUser() {
        return $_SESSION[ADMIN_SESSION_NAME] ?? null;
    }
    
    protected function verifyCsrf() {
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
    }
}
