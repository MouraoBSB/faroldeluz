<?php
/**
 * Verificar tabelas do banco de dados
 * Autor: Thiago Mourão
 * Data: 2026-02-17 10:40:00
 */

require_once __DIR__ . '/config/config.php';

try {
    $db = new PDO(
        "mysql:host=" . DB_CONFIG['host'] . ";port=" . DB_CONFIG['port'] . ";dbname=" . DB_CONFIG['database'] . ";charset=" . DB_CONFIG['charset'],
        DB_CONFIG['username'],
        DB_CONFIG['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "<h2>Tabelas do banco de dados</h2>";
    echo "<hr>";
    
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li><strong>{$table}</strong></li>";
    }
    echo "</ul>";
    
    echo "<hr>";
    echo "<h3>Procurando tabela de usuários...</h3>";
    
    foreach ($tables as $table) {
        if (stripos($table, 'user') !== false || stripos($table, 'admin') !== false) {
            echo "<p style='color: green;'>✅ Possível tabela de usuários: <strong>{$table}</strong></p>";
            
            // Mostrar estrutura
            $stmt = $db->query("DESCRIBE {$table}");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
            echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th></tr>";
            foreach ($columns as $col) {
                echo "<tr>";
                echo "<td>{$col['Field']}</td>";
                echo "<td>{$col['Type']}</td>";
                echo "<td>{$col['Null']}</td>";
                echo "<td>{$col['Key']}</td>";
                echo "</tr>";
            }
            echo "</table><br>";
        }
    }
    
} catch (PDOException $e) {
    echo "<h3 style='color: red;'>❌ Erro:</h3>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
