<?php
/**
 * Script de Relatório Semanal de Backups
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

$weeklyReport = $settings['backup_weekly_report'] ?? '1';

if ($weeklyReport !== '1') {
    echo "⚠️  Relatório semanal desativado\n";
    exit(0);
}

$email = $settings['backup_notification_email'] ?? 'contato@faroldeluz.ong.br';
if (empty($email)) {
    echo "❌ Email de notificação não configurado\n";
    exit(1);
}

$databaseDir = __DIR__ . '/database';
$filesDir = __DIR__ . '/files';
$logFile = $databaseDir . '/backup.log';

$databaseBackups = glob($databaseDir . '/backup_*.sql.gz');
$filesBackups = glob($filesDir . '/files_*.tar.gz');

$databaseCount = count($databaseBackups);
$filesCount = count($filesBackups);

$databaseSize = 0;
foreach ($databaseBackups as $file) {
    $databaseSize += filesize($file);
}

$filesSize = 0;
foreach ($filesBackups as $file) {
    $filesSize += filesize($file);
}

$databaseSizeMB = round($databaseSize / 1024 / 1024, 2);
$filesSizeMB = round($filesSize / 1024 / 1024, 2);
$totalSizeMB = $databaseSizeMB + $filesSizeMB;

$lastDatabaseBackup = !empty($databaseBackups) ? max(array_map('filemtime', $databaseBackups)) : null;
$lastFilesBackup = !empty($filesBackups) ? max(array_map('filemtime', $filesBackups)) : null;

$oldestBackup = null;
if (!empty($databaseBackups)) {
    $oldestTime = min(array_map('filemtime', $databaseBackups));
    $oldestDays = floor((time() - $oldestTime) / 86400);
    $oldestBackup = $oldestDays;
}

$weekStart = date('d/m/Y', strtotime('-7 days'));
$weekEnd = date('d/m/Y');

$driveStatus = (($settings['backup_gdrive_enabled'] ?? '0') === '1') ? '✅ Ativo' : '❌ Desativado';

$emailBody = "
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        h1 { color: #E8B86D; }
        h2 { color: #4A9EFF; margin-top: 30px; }
        .stat { background: #f4f4f4; padding: 15px; margin: 10px 0; border-left: 4px solid #E8B86D; }
        .stat strong { color: #E8B86D; }
        .success { color: #28a745; }
        .warning { color: #ffc107; }
        .error { color: #dc3545; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; color: #8FA3C1; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #E8B86D; color: white; }
    </style>
</head>
<body>
    <h1>📊 Relatório Semanal de Backups</h1>
    <p><strong>Período:</strong> {$weekStart} a {$weekEnd}</p>
    
    <h2>✅ Banco de Dados</h2>
    <div class='stat'>
        <strong>Backups realizados:</strong> {$databaseCount}<br>
        <strong>Tamanho total:</strong> {$databaseSizeMB} MB<br>
        <strong>Último backup:</strong> " . ($lastDatabaseBackup ? date('d/m/Y H:i', $lastDatabaseBackup) : 'Nenhum') . "<br>
        <strong>Status Google Drive:</strong> {$driveStatus}
    </div>
    
    <h2>📁 Arquivos (Uploads)</h2>
    <div class='stat'>
        <strong>Backups realizados:</strong> {$filesCount}<br>
        <strong>Tamanho total:</strong> {$filesSizeMB} MB<br>
        <strong>Último backup:</strong> " . ($lastFilesBackup ? date('d/m/Y H:i', $lastFilesBackup) : 'Nenhum') . "<br>
        <strong>Status Google Drive:</strong> {$driveStatus}
    </div>
    
    <h2>📊 Estatísticas Gerais</h2>
    <table>
        <tr>
            <th>Métrica</th>
            <th>Valor</th>
        </tr>
        <tr>
            <td>Total de backups</td>
            <td><strong>" . ($databaseCount + $filesCount) . "</strong></td>
        </tr>
        <tr>
            <td>Espaço utilizado (local)</td>
            <td><strong>{$totalSizeMB} MB</strong></td>
        </tr>
        <tr>
            <td>Backup mais antigo</td>
            <td><strong>" . ($oldestBackup !== null ? "{$oldestBackup} dias" : 'N/A') . "</strong></td>
        </tr>
        <tr>
            <td>Retenção configurada</td>
            <td><strong>Retenção configurada:</strong> " . ($settings['backup_retention_days'] ?? '30') . " dias</strong></td>
        </tr>
    </table>
    
    <h2>🔗 Ações Rápidas</h2>
    <p>
        <a href='" . base_url('admin/backup') . "' style='display: inline-block; background: #E8B86D; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>Ver Todos os Backups</a>
        <a href='" . base_url('admin/backup/run') . "' style='display: inline-block; background: #4A9EFF; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>Fazer Backup Manual</a>
    </p>
    
    <div class='footer'>
        <p>Sistema de Backup Automático - Farol de Luz<br>
        A Luz do Consolador para os dias de hoje!</p>
    </div>
</body>
</html>
";

$mailer = new Mailer();
$success = $mailer->send($email, "📊 Relatório Semanal de Backups - Farol de Luz", $emailBody, true);

if ($success) {
    echo "✅ Relatório semanal enviado para: {$email}\n";
    
    $logEntry = sprintf(
        "[%s] 📧 Relatório semanal enviado para {$email}\n",
        date('Y-m-d H:i:s')
    );
    file_put_contents($logFile, $logEntry, FILE_APPEND);
} else {
    echo "❌ Erro ao enviar relatório semanal\n";
}

exit($success ? 0 : 1);
