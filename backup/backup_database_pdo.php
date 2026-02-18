<?php
/**
 * Script de Backup do Banco de Dados via PDO
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-17
 */

require_once __DIR__ . '/../config/config.php';

echo "🔄 Iniciando backup do banco de dados via PDO...\n";
echo "📅 Data/Hora: " . date('d/m/Y H:i:s') . "\n\n";

try {
    $db = new PDO(
        "mysql:host=" . DB_CONFIG['host'] . ";port=" . DB_CONFIG['port'] . ";dbname=" . DB_CONFIG['database'] . ";charset=" . DB_CONFIG['charset'],
        DB_CONFIG['username'],
        DB_CONFIG['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    $backupDir = __DIR__ . '/database';
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d_H-i-s');
    $backupFile = $backupDir . '/backup_' . $timestamp . '.sql';
    
    $output = "-- Backup do Banco de Dados\n";
    $output .= "-- Data: " . date('Y-m-d H:i:s') . "\n";
    $output .= "-- Banco: " . DB_CONFIG['database'] . "\n\n";
    $output .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
    
    // Listar todas as tabelas
    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tables as $table) {
        echo "📋 Exportando tabela: {$table}\n";
        
        // DROP TABLE
        $output .= "-- Tabela: {$table}\n";
        $output .= "DROP TABLE IF EXISTS `{$table}`;\n";
        
        // CREATE TABLE
        $createTable = $db->query("SHOW CREATE TABLE `{$table}`")->fetch(PDO::FETCH_ASSOC);
        $output .= $createTable['Create Table'] . ";\n\n";
        
        // INSERT DATA
        $rows = $db->query("SELECT * FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $values = array_map(function($value) use ($db) {
                    return $value === null ? 'NULL' : $db->quote($value);
                }, $row);
                
                $output .= "INSERT INTO `{$table}` VALUES (" . implode(', ', $values) . ");\n";
            }
            $output .= "\n";
        }
    }
    
    $output .= "SET FOREIGN_KEY_CHECKS=1;\n";
    
    // Salvar arquivo
    file_put_contents($backupFile, $output);
    
    // Comprimir
    $gzipFile = $backupFile . '.gz';
    $gz = gzopen($gzipFile, 'w9');
    gzwrite($gz, $output);
    gzclose($gz);
    
    // Remover arquivo não comprimido
    unlink($backupFile);
    
    $backupSize = filesize($gzipFile);
    $backupSizeMB = round($backupSize / 1024 / 1024, 2);
    
    echo "\n✅ Backup criado com sucesso!\n";
    echo "📁 Arquivo: " . basename($gzipFile) . "\n";
    echo "📊 Tamanho: {$backupSizeMB} MB\n";
    echo "📋 Tabelas: " . count($tables) . "\n";
    
    // Log
    $logFile = $backupDir . '/backup.log';
    $logEntry = sprintf(
        "[%s] ✅ Backup criado: %s (%s MB, %d tabelas)\n",
        date('Y-m-d H:i:s'),
        basename($gzipFile),
        $backupSizeMB,
        count($tables)
    );
    file_put_contents($logFile, $logEntry, FILE_APPEND);
    
    // Enviar para Google Drive se ativado
    $stmt = $db->query("SELECT setting_value FROM settings WHERE setting_key = 'backup_gdrive_enabled'");
    $gdriveEnabled = $stmt->fetchColumn();
    
    if ($gdriveEnabled === '1') {
        echo "\n🔄 Enviando para Google Drive...\n";
        require_once __DIR__ . '/send_to_drive.php';
        $driveResult = sendToGoogleDrive($gzipFile, 'database');
        
        if ($driveResult['success']) {
            echo "✅ Enviado para Google Drive: {$driveResult['file_name']}\n";
            $logEntry = sprintf(
                "[%s] ✅ Enviado para Google Drive: %s\n",
                date('Y-m-d H:i:s'),
                basename($gzipFile)
            );
            file_put_contents($logFile, $logEntry, FILE_APPEND);
        } else {
            echo "❌ Erro ao enviar para Google Drive: {$driveResult['error']}\n";
            $logEntry = sprintf(
                "[%s] ❌ Erro Google Drive: %s\n",
                date('Y-m-d H:i:s'),
                $driveResult['error']
            );
            file_put_contents($logFile, $logEntry, FILE_APPEND);
        }
    }
    
    // Limpeza de backups antigos
    $stmt = $db->query("SELECT setting_value FROM settings WHERE setting_key = 'backup_retention_days'");
    $retentionDays = (int)($stmt->fetchColumn() ?: 30);
    
    echo "\n🧹 Limpando backups antigos (>{$retentionDays} dias)...\n";
    $files = glob($backupDir . '/backup_*.sql.gz');
    $deleted = 0;
    
    foreach ($files as $file) {
        $fileAge = time() - filemtime($file);
        $fileDays = floor($fileAge / 86400);
        
        if ($fileDays > $retentionDays) {
            unlink($file);
            $deleted++;
            echo "🗑️  Removido: " . basename($file) . " ({$fileDays} dias)\n";
        }
    }
    
    if ($deleted > 0) {
        $logEntry = sprintf(
            "[%s] 🧹 Limpeza: %d backup(s) removido(s)\n",
            date('Y-m-d H:i:s'),
            $deleted
        );
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }
    
    $totalBackups = count(glob($backupDir . '/backup_*.sql.gz'));
    $totalSize = 0;
    foreach (glob($backupDir . '/backup_*.sql.gz') as $file) {
        $totalSize += filesize($file);
    }
    $totalSizeMB = round($totalSize / 1024 / 1024, 2);
    
    echo "\n📊 Estatísticas:\n";
    echo "   Total de backups: {$totalBackups}\n";
    echo "   Espaço utilizado: {$totalSizeMB} MB\n";
    echo "   Retenção: {$retentionDays} dias\n";
    echo "\n✅ Backup concluído!\n";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
    exit(1);
}
