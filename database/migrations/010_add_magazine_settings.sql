-- Adicionar configurações de revista na tabela settings
-- Autor: Thiago Mourão
-- URL: https://www.instagram.com/mouraoeguerin/
-- Data: 2026-02-15 17:29:00

INSERT INTO settings (setting_key, setting_value) VALUES
('revista_titulo', 'Revista Espírita Farol de Luz: A Oficina do Pensamento'),
('revista_descricao', 'A Revista Espírita Farol de Luz é o braço editorial do nosso projeto, funcionando como uma oficina onde o conhecimento é trabalhado com profundidade, carinho e fidelidade doutrinárias.'),
('revista_texto_adicional', 'Explore nosso acervo e baixe gratuitamente as edições disponíveis. Que estas páginas sejam luz em sua jornada de renovação íntima.')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);
