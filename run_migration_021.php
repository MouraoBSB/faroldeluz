<?php
/**
 * Executar Migration 021 - Configurações de Email e Backup
 * Autor: Thiago Mourão
 * Data: 2026-02-17
 */

require_once __DIR__ . '/config/config.php';

try {
    $db = new PDO(
        "mysql:host=" . DB_CONFIG['host'] . ";port=" . DB_CONFIG['port'] . ";dbname=" . DB_CONFIG['database'] . ";charset=" . DB_CONFIG['charset'],
        DB_CONFIG['username'],
        DB_CONFIG['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "<h2>Executando Migration 021 - Email e Backup</h2>";
    echo "<hr>";
    
    // Configurações de Email/SMTP
    echo "<h3>📧 Configurações de Email/SMTP</h3>";
    
    $emailSettings = [
        'smtp_host' => 'pro115.dnspro.com.br',
        'smtp_port' => '465',
        'smtp_user' => 'contato@faroldeluz.ong.br',
        'smtp_password' => '',
        'smtp_encryption' => 'ssl',
        'smtp_from_name' => 'Farol de Luz',
        'smtp_from_email' => 'contato@faroldeluz.ong.br'
    ];
    
    foreach ($emailSettings as $key => $value) {
        $sql = "INSERT INTO settings (setting_key, setting_value, created_at, updated_at) 
                VALUES (?, ?, NOW(), NOW())
                ON DUPLICATE KEY UPDATE updated_at = NOW()";
        $stmt = $db->prepare($sql);
        $stmt->execute([$key, $value]);
        echo "✅ {$key}<br>";
    }
    
    // Configurações de Backup
    echo "<br><h3>🔐 Configurações de Backup</h3>";
    
    $backupSettings = [
        'backup_enabled' => '1',
        'backup_time' => '03:00',
        'backup_retention_days' => '30',
        'backup_files_enabled' => '1',
        'backup_files_frequency' => 'weekly',
        'backup_gdrive_enabled' => '0',
        'backup_gdrive_client_id' => '',
        'backup_gdrive_client_secret' => '',
        'backup_gdrive_refresh_token' => '',
        'backup_gdrive_folder_id' => '',
        'backup_notification_email' => 'contato@faroldeluz.ong.br',
        'backup_weekly_report' => '1',
        'backup_alert_on_failure' => '1'
    ];
    
    foreach ($backupSettings as $key => $value) {
        $sql = "INSERT INTO settings (setting_key, setting_value, created_at, updated_at) 
                VALUES (?, ?, NOW(), NOW())
                ON DUPLICATE KEY UPDATE updated_at = NOW()";
        $stmt = $db->prepare($sql);
        $stmt->execute([$key, $value]);
        echo "✅ {$key}<br>";
    }
    
    echo "<hr>";
    echo "<h3 style='color: green;'>✅ Migration 021 executada com sucesso!</h3>";
    echo "<br>";
    echo "<h4>📋 Próximos passos:</h4>";
    echo "<ol>";
    echo "<li>Acesse: <a href='/admin/configuracoes'>Admin > Configurações</a></li>";
    echo "<li>Configure a senha do email SMTP</li>";
    echo "<li>Teste o envio de email</li>";
    echo "<li>Configure o Google Drive (opcional)</li>";
    echo "<li>Configure os cron jobs no servidor</li>";
    echo "</ol>";
    echo "<br>";
    echo "<p><strong>⚠️ IMPORTANTE:</strong> Delete este arquivo após executar!</p>";
    echo "<br>";
    echo "<a href='/admin/configuracoes'>← Ir para Configurações</a>";
    
} catch (PDOException $e) {
    echo "<h3 style='color: red;'>❌ Erro ao executar migration:</h3>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
