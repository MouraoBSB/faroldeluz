<?php
/**
 * Executar Migration 019 - Configurações Gerais
 * Autor: Thiago Mourão
 * Data: 2026-02-17 09:58:00
 */

require_once __DIR__ . '/config/config.php';

try {
    $db = new PDO(
        "mysql:host=" . DB_CONFIG['host'] . ";port=" . DB_CONFIG['port'] . ";dbname=" . DB_CONFIG['database'] . ";charset=" . DB_CONFIG['charset'],
        DB_CONFIG['username'],
        DB_CONFIG['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "<h2>Executando Migration 019 - Configurações Gerais</h2>";
    echo "<hr>";
    
    // 1. Meta Description
    $sql = "INSERT INTO settings (setting_key, setting_value, created_at, updated_at) 
            VALUES ('site_meta_description', 'Projeto Espírita Farol de Luz: Revista Espírita, Diálogos do Farol, Grupo de Estudos Rajian e Blog com conteúdos que iluminam reflexões e fortalecem a fé.', NOW(), NOW())
            ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = NOW()";
    
    $db->exec($sql);
    echo "✅ site_meta_description criado/atualizado<br>";
    
    // 2. Favicon
    $sql = "INSERT INTO settings (setting_key, setting_value, created_at, updated_at) 
            VALUES ('site_favicon', '', NOW(), NOW())
            ON DUPLICATE KEY UPDATE updated_at = NOW()";
    
    $db->exec($sql);
    echo "✅ site_favicon criado/atualizado<br>";
    
    // 3. OG Title
    $sql = "INSERT INTO settings (setting_key, setting_value, created_at, updated_at) 
            VALUES ('site_og_title', 'Farol de Luz - A Luz do Consolador para os dias de hoje!', NOW(), NOW())
            ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = NOW()";
    
    $db->exec($sql);
    echo "✅ site_og_title criado/atualizado<br>";
    
    // 4. OG Image
    $sql = "INSERT INTO settings (setting_key, setting_value, created_at, updated_at) 
            VALUES ('site_og_image', '', NOW(), NOW())
            ON DUPLICATE KEY UPDATE updated_at = NOW()";
    
    $db->exec($sql);
    echo "✅ site_og_image criado/atualizado<br>";
    
    echo "<hr>";
    echo "<h3 style='color: green;'>✅ Migration 019 executada com sucesso!</h3>";
    echo "<br>";
    echo "<a href='/admin/configuracoes'>← Ir para Configurações</a>";
    
} catch (PDOException $e) {
    echo "<h3 style='color: red;'>❌ Erro ao executar migration:</h3>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
