<?php
/**
 * Classe de Logs de Segurança
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-18
 */

class SecurityLogger {
    private $logFile;
    
    public function __construct() {
        $logDir = __DIR__ . '/../logs';
        if (!file_exists($logDir)) {
            mkdir($logDir, 0755, true);
        }
        $this->logFile = $logDir . '/security.log';
    }
    
    /**
     * Registra um evento de segurança
     */
    public function log($event, $severity = 'INFO', $details = []) {
        $entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'CLI',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'CLI',
            'request_uri' => $_SERVER['REQUEST_URI'] ?? 'CLI',
            'event' => $event,
            'severity' => $severity,
            'details' => $details,
            'user_id' => $_SESSION['user_id'] ?? null
        ];
        
        $line = json_encode($entry, JSON_UNESCAPED_UNICODE) . "\n";
        file_put_contents($this->logFile, $line, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Registra tentativa de login
     */
    public function loginAttempt($email, $success, $reason = '') {
        $this->log('LOGIN_ATTEMPT', $success ? 'INFO' : 'WARNING', [
            'email' => $email,
            'success' => $success,
            'reason' => $reason
        ]);
    }
    
    /**
     * Registra atividade suspeita
     */
    public function suspiciousActivity($description, $details = []) {
        $this->log('SUSPICIOUS_ACTIVITY', 'CRITICAL', array_merge([
            'description' => $description
        ], $details));
    }
    
    /**
     * Registra bloqueio por rate limit
     */
    public function rateLimitBlocked($identifier, $action) {
        $this->log('RATE_LIMIT_BLOCKED', 'WARNING', [
            'identifier' => $identifier,
            'action' => $action
        ]);
    }
    
    /**
     * Registra erro CSRF
     */
    public function csrfError($action) {
        $this->log('CSRF_ERROR', 'WARNING', [
            'action' => $action
        ]);
    }
    
    /**
     * Registra upload de arquivo
     */
    public function fileUpload($filename, $success, $reason = '') {
        $this->log('FILE_UPLOAD', $success ? 'INFO' : 'WARNING', [
            'filename' => $filename,
            'success' => $success,
            'reason' => $reason
        ]);
    }
}
