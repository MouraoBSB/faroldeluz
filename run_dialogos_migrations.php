<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$db = Database::getInstance()->getConnection();

echo "Executando migrations de diálogos...\n\n";

$migrations = [
    '014_create_dialogos_table.sql',
    '015_add_dialogos_settings.sql'
];

foreach ($migrations as $migration) {
    echo "Executando: $migration\n";
    $sql = file_get_contents(__DIR__ . '/database/migrations/' . $migration);
    
    try {
        $db->exec($sql);
        echo "✅ $migration executada com sucesso!\n\n";
    } catch (PDOException $e) {
        echo "❌ Erro em $migration: " . $e->getMessage() . "\n\n";
    }
}

echo "✅ Migrations concluídas!\n";
