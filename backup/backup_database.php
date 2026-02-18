<?php
/**
 * Script de Backup Automático do Banco de Dados
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-17
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../lib/Mailer.php';

// Buscar configurações diretamente do banco
try {
    $db = new PDO(
        "mysql:host=" . DB_CONFIG['host'] . ";port=" . DB_CONFIG['port'] . ";dbname=" . DB_CONFIG['database'] . ";charset=" . DB_CONFIG['charset'],
        DB_CONFIG['username'],
        DB_CONFIG['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    $stmt = $db->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'backup_%'");
    $settings = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
} catch (Exception $e) {
    echo "❌ Erro ao conectar ao banco: " . $e->getMessage() . "\n";
    exit(1);
}

$backupEnabled = $settings['backup_enabled'] ?? '1';

if ($backupEnabled !== '1') {
    echo "⚠️  Backup desativado nas configurações\n";
    exit(0);
}

$backupDir = __DIR__ . '/database';
$retentionDays = (int)($settings['backup_retention_days'] ?? 30);

if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

$timestamp = date('Y-m-d_H-i-s');
$backupFile = $backupDir . '/backup_' . $timestamp . '.sql';
$logFile = $backupDir . '/backup.log';

$host = DB_CONFIG['host'];
$port = DB_CONFIG['port'];
$database = DB_CONFIG['database'];
$username = DB_CONFIG['username'];
$password = DB_CONFIG['password'];

echo "🔄 Iniciando backup do banco de dados...\n";
echo "📅 Data/Hora: " . date('d/m/Y H:i:s') . "\n\n";

$command = sprintf(
    'mysqldump --host=%s --port=%s --user=%s --password=%s --single-transaction --routines --triggers --add-drop-table %s > %s 2>&1',
    escapeshellarg($host),
    escapeshellarg($port),
    escapeshellarg($username),
    escapeshellarg($password),
    escapeshellarg($database),
    escapeshellarg($backupFile)
);

exec($command, $output, $returnCode);

if ($returnCode === 0 && file_exists($backupFile) && filesize($backupFile) > 0) {
    echo "✅ Dump do banco criado com sucesso\n";
    
    $gzipFile = $backupFile . '.gz';
    exec("gzip -9 " . escapeshellarg($backupFile), $gzipOutput, $gzipReturn);
    
    if ($gzipReturn === 0 && file_exists($gzipFile)) {
        $backupSize = filesize($gzipFile);
        $backupSizeMB = round($backupSize / 1024 / 1024, 2);
        
        echo "✅ Backup comprimido com sucesso\n";
        echo "📁 Arquivo: " . basename($gzipFile) . "\n";
        echo "📊 Tamanho: {$backupSizeMB} MB\n\n";
        
        $logEntry = sprintf(
            "[%s] ✅ Backup criado: %s (%s MB)\n",
            date('Y-m-d H:i:s'),
            basename($gzipFile),
            $backupSizeMB
        );
        file_put_contents($logFile, $logEntry, FILE_APPEND);
        
        if (($settings['backup_gdrive_enabled'] ?? '0') === '1') {
            echo "🔄 Enviando para Google Drive...\n";
            require_once __DIR__ . '/send_to_drive.php';
            $driveResult = sendToGoogleDrive($gzipFile, 'database');
            
            if ($driveResult['success']) {
                echo "✅ Enviado para Google Drive\n\n";
                $logEntry = sprintf(
                    "[%s] ✅ Enviado para Google Drive: %s\n",
                    date('Y-m-d H:i:s'),
                    basename($gzipFile)
                );
                file_put_contents($logFile, $logEntry, FILE_APPEND);
            } else {
                echo "❌ Erro ao enviar para Google Drive: {$driveResult['error']}\n\n";
                $logEntry = sprintf(
                    "[%s] ❌ Erro Google Drive: %s\n",
                    date('Y-m-d H:i:s'),
                    $driveResult['error']
                );
                file_put_contents($logFile, $logEntry, FILE_APPEND);
            }
        }
        
    } else {
        echo "❌ Erro ao comprimir backup\n";
        $logEntry = sprintf(
            "[%s] ❌ Erro ao comprimir backup\n",
            date('Y-m-d H:i:s')
        );
        file_put_contents($logFile, $logEntry, FILE_APPEND);
        
        if (($settings['backup_alert_on_failure'] ?? '1') === '1') {
            $mailer = new Mailer();
            $email = $settings['backup_notification_email'] ?? 'contato@faroldeluz.ong.br';
            $mailer->send(
                $email,
                '❌ Falha no Backup - Farol de Luz',
                "<h2>Erro ao comprimir backup</h2><p>Data: " . date('d/m/Y H:i:s') . "</p>",
                true
            );
        }
        
        exit(1);
    }
} else {
    echo "❌ Erro ao criar backup\n";
    echo "Detalhes: " . implode("\n", $output) . "\n";
    
    $logEntry = sprintf(
        "[%s] ❌ Erro ao criar backup: %s\n",
        date('Y-m-d H:i:s'),
        implode(", ", $output)
    );
    file_put_contents($logFile, $logEntry, FILE_APPEND);
    
    if (($settings['backup_alert_on_failure'] ?? '1') === '1') {
        $mailer = new Mailer();
        $email = $settings['backup_notification_email'] ?? 'contato@faroldeluz.ong.br';
        $mailer->send(
            $email,
            '❌ Falha no Backup - Farol de Luz',
            "<h2>Erro ao criar backup do banco de dados</h2><p>Data: " . date('d/m/Y H:i:s') . "</p><p>Erro: " . implode(", ", $output) . "</p>",
            true
        );
    }
    
    exit(1);
}

echo "🧹 Limpando backups antigos (>{$retentionDays} dias)...\n";
$files = glob($backupDir . '/backup_*.sql.gz');
if ($files) {
    $now = time();
    $deleted = 0;
    
    foreach ($files as $file) {
        $fileAge = $now - filemtime($file);
        $fileDays = floor($fileAge / 86400);
        
        if ($fileDays > $retentionDays) {
            unlink($file);
            $deleted++;
            echo "🗑️  Removido: " . basename($file) . " ({$fileDays} dias)\n";
        }
    }
    
    if ($deleted > 0) {
        echo "\n🧹 {$deleted} backup(s) antigo(s) removido(s)\n";
        $logEntry = sprintf(
            "[%s] 🧹 Limpeza: %d backup(s) removido(s)\n",
            date('Y-m-d H:i:s'),
            $deleted
        );
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }
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
echo "\n✅ Backup concluído com sucesso!\n";

exit(0);
