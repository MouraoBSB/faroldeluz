<?php
/**
 * Script de Migração do Banco de Dados
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance()->getConnection();

$migrationsPath = __DIR__ . '/migrations';
$migrations = glob($migrationsPath . '/*.sql');
sort($migrations);

echo "Iniciando migrações do banco de dados...\n\n";

foreach ($migrations as $migration) {
    $filename = basename($migration);
    echo "Executando: {$filename}... ";
    
    try {
        $sql = file_get_contents($migration);
        $db->exec($sql);
        echo "✓ Concluído\n";
    } catch (PDOException $e) {
        echo "✗ Erro: " . $e->getMessage() . "\n";
    }
}

echo "\nMigrações concluídas!\n";
