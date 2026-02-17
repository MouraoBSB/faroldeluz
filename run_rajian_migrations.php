<?php
/**
 * Script para executar migrations do Rajian
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-16 23:25:00
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$db = Database::getInstance()->getConnection();

$migrations = [
    '016_create_rajian_table.sql',
    '017_add_rajian_settings.sql'
];

foreach ($migrations as $migration) {
    $sql = file_get_contents(__DIR__ . '/database/migrations/' . $migration);
    
    try {
        $db->exec($sql);
        echo "✅ Migration {$migration} executada com sucesso!\n";
    } catch (PDOException $e) {
        echo "❌ Erro em {$migration}: " . $e->getMessage() . "\n";
    }
}
