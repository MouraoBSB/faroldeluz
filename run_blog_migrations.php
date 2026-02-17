<?php
/**
 * Script para executar migrations do Blog
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-16 23:31:00
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$db = Database::getInstance()->getConnection();

$sql = file_get_contents(__DIR__ . '/database/migrations/018_add_blog_settings.sql');

try {
    $db->exec($sql);
    echo "✅ Migration de configurações do blog executada com sucesso!\n";
} catch (PDOException $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
