-- Migration: Adicionar configurações de Email e Backup
-- Autor: Thiago Mourão
-- URL: https://www.instagram.com/mouraoeguerin/
-- Data: 2026-02-17 21:57:00

-- Configurações de Email/SMTP
INSERT INTO settings (setting_key, setting_value, created_at, updated_at) VALUES
('smtp_host', 'pro115.dnspro.com.br', NOW(), NOW()),
('smtp_port', '465', NOW(), NOW()),
('smtp_user', 'contato@faroldeluz.ong.br', NOW(), NOW()),
('smtp_password', '', NOW(), NOW()),
('smtp_encryption', 'ssl', NOW(), NOW()),
('smtp_from_name', 'Farol de Luz', NOW(), NOW()),
('smtp_from_email', 'contato@faroldeluz.ong.br', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- Configurações de Backup
INSERT INTO settings (setting_key, setting_value, created_at, updated_at) VALUES
('backup_enabled', '1', NOW(), NOW()),
('backup_time', '03:00', NOW(), NOW()),
('backup_retention_days', '30', NOW(), NOW()),
('backup_files_enabled', '1', NOW(), NOW()),
('backup_files_frequency', 'weekly', NOW(), NOW()),
('backup_gdrive_enabled', '0', NOW(), NOW()),
('backup_gdrive_client_id', '', NOW(), NOW()),
('backup_gdrive_client_secret', '', NOW(), NOW()),
('backup_gdrive_refresh_token', '', NOW(), NOW()),
('backup_gdrive_folder_id', '', NOW(), NOW()),
('backup_notification_email', 'contato@faroldeluz.ong.br', NOW(), NOW()),
('backup_weekly_report', '1', NOW(), NOW()),
('backup_alert_on_failure', '1', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();
