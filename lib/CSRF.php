<?php
/**
 * Classe de Proteção CSRF
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-18
 */

class CSRF {
    /**
     * Gera um token CSRF único para a sessão
     */
    public static function generateToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Valida o token CSRF
     */
    public static function validateToken($token) {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Retorna um campo hidden com o token CSRF
     */
    public static function getField() {
        $token = self::generateToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
    
    /**
     * Valida o token do POST atual ou retorna erro
     */
    public static function validate() {
        $token = $_POST['csrf_token'] ?? '';
        
        if (!self::validateToken($token)) {
            http_response_code(403);
            die('Token CSRF inválido. Por favor, recarregue a página e tente novamente.');
        }
    }
}
