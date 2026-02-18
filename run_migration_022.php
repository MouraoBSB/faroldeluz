<?php
/**
 * Script para executar migration 022 - Rate Limits
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-18
 */

require_once __DIR__ . '/config/config.php';

try {
    $db = new PDO(
        "mysql:host=" . DB_CONFIG['host'] . ";port=" . DB_CONFIG['port'] . ";dbname=" . DB_CONFIG['database'] . ";charset=" . DB_CONFIG['charset'],
        DB_CONFIG['username'],
        DB_CONFIG['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "🔄 Executando migration 022...\n\n";
    
    $sql = file_get_contents(__DIR__ . '/database/migrations/022_create_rate_limits_table.sql');
    
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (!empty($statement) && !str_starts_with($statement, '--')) {
            $db->exec($statement);
            echo "✅ Executado: " . substr($statement, 0, 50) . "...\n";
        }
    }
    
    echo "\n✅ Migration 022 executada com sucesso!\n";
    echo "\n📊 Tabela 'rate_limits' criada.\n";
    
} catch (PDOException $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
    exit(1);
}
