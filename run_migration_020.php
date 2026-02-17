<?php
/**
 * Executar Migration 020 - Link WhatsApp Grupo Rajian
 * Autor: Thiago Mourão
 * Data: 2026-02-17 10:24:00
 */

require_once __DIR__ . '/config/config.php';

try {
    $db = new PDO(
        "mysql:host=" . DB_CONFIG['host'] . ";port=" . DB_CONFIG['port'] . ";dbname=" . DB_CONFIG['database'] . ";charset=" . DB_CONFIG['charset'],
        DB_CONFIG['username'],
        DB_CONFIG['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "<h2>Executando Migration 020 - Link WhatsApp Grupo Rajian</h2>";
    echo "<hr>";
    
    $sql = "INSERT INTO settings (setting_key, setting_value, created_at, updated_at) 
            VALUES ('rajian_whatsapp_group_url', '', NOW(), NOW())
            ON DUPLICATE KEY UPDATE updated_at = NOW()";
    
    $db->exec($sql);
    echo "✅ rajian_whatsapp_group_url criado/atualizado<br>";
    
    echo "<hr>";
    echo "<h3 style='color: green;'>✅ Migration 020 executada com sucesso!</h3>";
    echo "<br>";
    echo "<a href='/admin/configuracoes'>← Ir para Configurações</a>";
    
} catch (PDOException $e) {
    echo "<h3 style='color: red;'>❌ Erro ao executar migration:</h3>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
