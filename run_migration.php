<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$db = Database::getInstance()->getConnection();
$sql = file_get_contents(__DIR__ . '/database/migrations/013_add_revista_imagem_destaque.sql');

try {
    $db->exec($sql);
    echo "✅ Migration executada com sucesso!\n";
} catch (PDOException $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
