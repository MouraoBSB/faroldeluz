-- Adicionar configurações da página de diálogos
-- Autor: Thiago Mourão
-- URL: https://www.instagram.com/mouraoeguerin/
-- Data: 2026-02-16 22:45:00

INSERT INTO settings (setting_key, setting_value, created_at, updated_at) VALUES
('dialogos_titulo', 'Diálogos do Farol', NOW(), NOW()),
('dialogos_descricao', '<p>Espaço dedicado ao diálogo e reflexão sobre temas espíritas contemporâneos.</p>', NOW(), NOW()),
('dialogos_imagem_destaque', '', NOW(), NOW()),
('dialogos_texto_adicional', '', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();
