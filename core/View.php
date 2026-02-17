<?php
/**
 * View Helper
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 */

class View {
    public static function render($viewPath, $data = []) {
        extract($data);
        $viewFile = BASE_PATH . '/views/' . $viewPath . '.php';
        
        if (!file_exists($viewFile)) {
            throw new Exception("View não encontrada: {$viewPath}");
        }
        
        require_once $viewFile;
    }
    
    public static function escape($text) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
    
    public static function formatDate($date, $format = 'd/m/Y') {
        return date($format, strtotime($date));
    }
    
    public static function truncate($text, $length = 150, $suffix = '...') {
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        return mb_substr($text, 0, $length) . $suffix;
    }
}
