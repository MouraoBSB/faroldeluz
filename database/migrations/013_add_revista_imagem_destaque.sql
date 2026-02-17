-- Adicionar campo para imagem de destaque da página de revistas
-- Autor: Thiago Mourão
-- URL: https://www.instagram.com/mouraoeguerin/
-- Data: 2026-02-16 14:41:00

INSERT INTO settings (setting_key, setting_value, created_at, updated_at)
VALUES ('revista_imagem_destaque', '', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();
