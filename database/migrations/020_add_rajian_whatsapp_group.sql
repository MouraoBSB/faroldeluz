-- Migration: Adicionar link do grupo WhatsApp do Rajian
-- Autor: Thiago Mourão
-- URL: https://www.instagram.com/mouraoeguerin/
-- Data: 2026-02-17 10:23:00

INSERT INTO settings (setting_key, setting_value, created_at, updated_at) 
VALUES ('rajian_whatsapp_group_url', '', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();
