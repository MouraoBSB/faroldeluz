-- Script para adicionar coluna cover_image_url na tabela rajian_studies
-- Execute este comando no seu cliente MySQL (phpMyAdmin, MySQL Workbench, etc.)

USE farol_de_luz;

ALTER TABLE rajian_studies 
ADD COLUMN cover_image_url VARCHAR(500) NULL AFTER og_image_url;

-- Verificar se a coluna foi adicionada
DESCRIBE rajian_studies;
