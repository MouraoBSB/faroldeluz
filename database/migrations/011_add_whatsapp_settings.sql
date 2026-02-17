-- Adicionar configurações de WhatsApp (grupo e canal)
-- Autor: Thiago Mourão
-- URL: https://www.instagram.com/mouraoeguerin/
-- Data: 2026-02-15 17:39:00

INSERT INTO settings (setting_key, setting_value) VALUES
('whatsapp_group_url', ''),
('whatsapp_channel_url', '')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);
