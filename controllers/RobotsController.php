<?php
/**
 * RobotsController
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 */

require_once BASE_PATH . '/core/Controller.php';

class RobotsController extends Controller {
    public function index() {
        header('Content-Type: text/plain; charset=utf-8');
        
        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo "Disallow: /admin/\n";
        echo "Disallow: /api/\n\n";
        echo "Sitemap: " . base_url('sitemap.xml') . "\n";
        
        exit;
    }
}
