-- Migration: Adicionar configurações gerais (meta description e favicon)
-- Autor: Thiago Mourão
-- URL: https://www.instagram.com/mouraoeguerin/
-- Data: 2026-02-17 09:50:00

-- Meta description para compartilhamento de links (OG tags)
INSERT INTO settings (`key`, `value`, `type`, created_at, updated_at) 
VALUES ('site_meta_description', 'Projeto Espírita Farol de Luz: Revista Espírita, Diálogos do Farol, Grupo de Estudos Rajian e Blog com conteúdos que iluminam reflexões e fortalecem a fé.', 'textarea', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- Favicon do site
INSERT INTO settings (`key`, `value`, `type`, created_at, updated_at) 
VALUES ('site_favicon', '', 'file', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- Título do site para OG tags
INSERT INTO settings (`key`, `value`, `type`, created_at, updated_at) 
VALUES ('site_og_title', 'Farol de Luz - A Luz do Consolador para os dias de hoje!', 'text', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- Imagem padrão para compartilhamento (OG image)
INSERT INTO settings (`key`, `value`, `type`, created_at, updated_at) 
VALUES ('site_og_image', '', 'file', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();
