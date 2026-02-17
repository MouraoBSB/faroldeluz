-- Primeiro, vamos descobrir qual banco você está usando
-- Execute estes comandos um por vez no phpMyAdmin

-- 1. Ver todos os bancos disponíveis
SHOW DATABASES;

-- 2. Ver qual banco está sendo usado atualmente
SELECT DATABASE();

-- 3. Ver as tabelas disponíveis no banco atual
SHOW TABLES;

-- 4. Ver a estrutura da tabela rajian_studies (se existir)
DESCRIBE rajian_studies;
