-- Tabela de configurações do site
-- Autor: Thiago Mourão
-- URL: https://www.instagram.com/mouraoeguerin/
-- Data: 2026-02-15 16:12:00

CREATE TABLE IF NOT EXISTS settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_setting_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Configurações padrão
INSERT INTO settings (setting_key, setting_value) VALUES
('whatsapp_group_url', ''),
('contact_email', 'contato@faroldeluz.com.br'),
('contact_phone', ''),
('social_links_json', '{"facebook":"","instagram":"","youtube":""}')
ON DUPLICATE KEY UPDATE setting_key = setting_key;
