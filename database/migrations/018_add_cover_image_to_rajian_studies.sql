-- Adicionar campo de imagem de capa para estudos Rajian
-- Autor: Thiago Mourão
-- URL: https://www.instagram.com/mouraoeguerin/
-- Data: 2026-02-17 01:54:00

ALTER TABLE rajian_studies 
ADD COLUMN cover_image_url VARCHAR(500) NULL AFTER og_image_url;
