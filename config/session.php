<?php
/**
 * Configuração de Sessão Segura
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-18
 */

// Configurações de segurança da sessão
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Apenas HTTPS
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.use_strict_mode', 1);
ini_set('session.gc_maxlifetime', 3600); // 1 hora
ini_set('session.use_only_cookies', 1);

// Iniciar sessão se ainda não iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Regenera o ID da sessão (usar após login)
 */
function regenerateSession() {
    session_regenerate_id(true);
}

/**
 * Valida a sessão atual
 */
function validateSession() {
    // Primeira vez - armazenar fingerprint
    if (!isset($_SESSION['user_ip'])) {
        $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['last_activity'] = time();
        return true;
    }
    
    // Validar IP (comentado para evitar problemas com proxies/VPN)
    // if ($_SESSION['user_ip'] !== $_SERVER['REMOTE_ADDR']) {
    //     session_destroy();
    //     return false;
    // }
    
    // Validar User-Agent
    if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
        session_destroy();
        return false;
    }
    
    // Timeout de inatividade (30 minutos)
    if (isset($_SESSION['last_activity']) && 
        (time() - $_SESSION['last_activity'] > 1800)) {
        session_destroy();
        return false;
    }
    
    $_SESSION['last_activity'] = time();
    return true;
}

/**
 * Destrói a sessão completamente
 */
function destroySession() {
    $_SESSION = [];
    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    session_destroy();
}
