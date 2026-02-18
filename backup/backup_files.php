<?php
/**
 * Script de Backup de Arquivos (Uploads)
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-17
 */

require_once __DIR__ . '/../config/config.php';

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

$backupEnabled = $settings['backup_files_enabled'] ?? '1';

if ($backupEnabled !== '1') {
    echo "⚠️  Backup de arquivos desativado\n";
    exit(0);
}

$backupDir = __DIR__ . '/files';
$uploadsDir = BASE_PATH . '/assets/uploads';
$timestamp = date('Y-m-d');
$backupFile = $backupDir . '/files_' . $timestamp . '.tar.gz';
$logFile = __DIR__ . '/database/backup.log';

if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

echo "🔄 Iniciando backup de arquivos...\n";
echo "📅 Data: " . date('d/m/Y H:i:s') . "\n";
echo "📁 Origem: {$uploadsDir}\n\n";

$command = sprintf(
    'tar -czf %s -C %s uploads 2>&1',
    escapeshellarg($backupFile),
    escapeshellarg(BASE_PATH . '/assets')
);

exec($command, $output, $returnCode);

if ($returnCode === 0 && file_exists($backupFile)) {
    $backupSize = filesize($backupFile);
    $backupSizeMB = round($backupSize / 1024 / 1024, 2);
    
    $fileCount = 0;
    exec("tar -tzf " . escapeshellarg($backupFile) . " | wc -l", $countOutput);
    if (isset($countOutput[0])) {
        $fileCount = (int)$countOutput[0];
    }
    
    echo "✅ Backup de arquivos criado com sucesso\n";
    echo "📁 Arquivo: " . basename($backupFile) . "\n";
    echo "📊 Tamanho: {$backupSizeMB} MB\n";
    echo "📄 Arquivos: {$fileCount}\n\n";
    
    $logEntry = sprintf(
        "[%s] ✅ Backup de arquivos: %s (%s MB, %d arquivos)\n",
        date('Y-m-d H:i:s'),
        basename($backupFile),
        $backupSizeMB,
        $fileCount
    );
    file_put_contents($logFile, $logEntry, FILE_APPEND);
    
    if (($settings['backup_gdrive_enabled'] ?? '0') === '1') {
        echo "🔄 Enviando para Google Drive...\n";
        require_once __DIR__ . '/send_to_drive.php';
        $driveResult = sendToGoogleDrive($backupFile, 'files');
        
        if ($driveResult['success']) {
            echo "✅ Enviado para Google Drive\n\n";
            $logEntry = sprintf(
                "[%s] ✅ Arquivos enviados para Google Drive\n",
                date('Y-m-d H:i:s')
            );
            file_put_contents($logFile, $logEntry, FILE_APPEND);
        } else {
            echo "❌ Erro ao enviar para Google Drive: {$driveResult['error']}\n\n";
        }
    }
    
    $retentionDays = (int)($settings['backup_retention_days'] ?? 30);
    $files = glob($backupDir . '/files_*.tar.gz');
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
            echo "\n🧹 {$deleted} backup(s) de arquivos removido(s)\n";
        }
    }
    
    echo "\n✅ Backup de arquivos concluído!\n";
    exit(0);
    
} else {
    echo "❌ Erro ao criar backup de arquivos\n";
    echo "Detalhes: " . implode("\n", $output) . "\n";
    
    $logEntry = sprintf(
        "[%s] ❌ Erro ao criar backup de arquivos\n",
        date('Y-m-d H:i:s')
    );
    file_put_contents($logFile, $logEntry, FILE_APPEND);
    
    exit(1);
}
