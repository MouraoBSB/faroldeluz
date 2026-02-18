<?php
/**
 * Gerador de Sitemap XML
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-17
 */

require_once __DIR__ . '/config/config.php';

header('Content-Type: application/xml; charset=utf-8');

try {
    $db = new PDO(
        "mysql:host=" . DB_CONFIG['host'] . ";port=" . DB_CONFIG['port'] . ";dbname=" . DB_CONFIG['database'] . ";charset=" . DB_CONFIG['charset'],
        DB_CONFIG['username'],
        DB_CONFIG['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    
    // Página inicial
    echo '  <url>' . "\n";
    echo '    <loc>' . base_url() . '</loc>' . "\n";
    echo '    <changefreq>daily</changefreq>' . "\n";
    echo '    <priority>1.0</priority>' . "\n";
    echo '  </url>' . "\n";
    
    // Páginas estáticas
    $staticPages = [
        ['url' => 'sobre', 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['url' => 'contato', 'priority' => '0.7', 'changefreq' => 'monthly'],
        ['url' => 'blog', 'priority' => '0.9', 'changefreq' => 'daily'],
        ['url' => 'revistas', 'priority' => '0.9', 'changefreq' => 'weekly'],
        ['url' => 'dialogos', 'priority' => '0.9', 'changefreq' => 'weekly'],
        ['url' => 'rajian', 'priority' => '0.9', 'changefreq' => 'weekly'],
        ['url' => 'batuira', 'priority' => '0.8', 'changefreq' => 'monthly'],
    ];
    
    foreach ($staticPages as $page) {
        echo '  <url>' . "\n";
        echo '    <loc>' . base_url($page['url']) . '</loc>' . "\n";
        echo '    <changefreq>' . $page['changefreq'] . '</changefreq>' . "\n";
        echo '    <priority>' . $page['priority'] . '</priority>' . "\n";
        echo '  </url>' . "\n";
    }
    
    // Posts do Blog
    $stmt = $db->query("
        SELECT slug, updated_at, created_at 
        FROM blog_posts 
        WHERE status = 'published' 
        ORDER BY created_at DESC
    ");
    
    while ($post = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $lastmod = $post['updated_at'] ?: $post['created_at'];
        echo '  <url>' . "\n";
        echo '    <loc>' . base_url('blog/' . $post['slug']) . '</loc>' . "\n";
        echo '    <lastmod>' . date('Y-m-d', strtotime($lastmod)) . '</lastmod>' . "\n";
        echo '    <changefreq>monthly</changefreq>' . "\n";
        echo '    <priority>0.7</priority>' . "\n";
        echo '  </url>' . "\n";
    }
    
    // Revistas
    $stmt = $db->query("
        SELECT slug, updated_at, created_at 
        FROM magazines 
        WHERE status = 'published' 
        ORDER BY edition_number DESC
    ");
    
    while ($magazine = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $lastmod = $magazine['updated_at'] ?: $magazine['created_at'];
        echo '  <url>' . "\n";
        echo '    <loc>' . base_url('revistas/' . $magazine['slug']) . '</loc>' . "\n";
        echo '    <lastmod>' . date('Y-m-d', strtotime($lastmod)) . '</lastmod>' . "\n";
        echo '    <changefreq>yearly</changefreq>' . "\n";
        echo '    <priority>0.8</priority>' . "\n";
        echo '  </url>' . "\n";
    }
    
    // Diálogos
    $stmt = $db->query("
        SELECT slug, updated_at, created_at 
        FROM dialogos 
        WHERE status = 'published' 
        ORDER BY created_at DESC
    ");
    
    while ($dialogo = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $lastmod = $dialogo['updated_at'] ?: $dialogo['created_at'];
        echo '  <url>' . "\n";
        echo '    <loc>' . base_url('dialogos/' . $dialogo['slug']) . '</loc>' . "\n";
        echo '    <lastmod>' . date('Y-m-d', strtotime($lastmod)) . '</lastmod>' . "\n";
        echo '    <changefreq>monthly</changefreq>' . "\n";
        echo '    <priority>0.7</priority>' . "\n";
        echo '  </url>' . "\n";
    }
    
    // Estudos Rajian
    $stmt = $db->query("
        SELECT slug, updated_at, created_at 
        FROM rajian_studies 
        WHERE status = 'published' 
        ORDER BY created_at DESC
    ");
    
    while ($study = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $lastmod = $study['updated_at'] ?: $study['created_at'];
        echo '  <url>' . "\n";
        echo '    <loc>' . base_url('rajian/' . $study['slug']) . '</loc>' . "\n";
        echo '    <lastmod>' . date('Y-m-d', strtotime($lastmod)) . '</lastmod>' . "\n";
        echo '    <changefreq>monthly</changefreq>' . "\n";
        echo '    <priority>0.7</priority>' . "\n";
        echo '  </url>' . "\n";
    }
    
    echo '</urlset>';
    
} catch (Exception $e) {
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    echo '  <url>' . "\n";
    echo '    <loc>' . base_url() . '</loc>' . "\n";
    echo '    <changefreq>daily</changefreq>' . "\n";
    echo '    <priority>1.0</priority>' . "\n";
    echo '  </url>' . "\n";
    echo '</urlset>';
    
    error_log("Erro ao gerar sitemap: " . $e->getMessage());
}
