-- Script correto para adicionar coluna cover_image_url
-- Execute este comando no phpMyAdmin

USE cemaneto_site_faroldeluz;

ALTER TABLE rajian_studies 
ADD COLUMN cover_image_url VARCHAR(500) NULL AFTER og_image_url;

-- Verificar se a coluna foi adicionada
DESCRIBE rajian_studies;
