INSERT INTO settings (`key`, `value`) VALUES
('blog_titulo', 'Blog Farol de Luz'),
('blog_descricao', '<p>Acompanhe nossos artigos, reflexões e estudos sobre espiritismo.</p>'),
('blog_imagem_destaque', ''),
('blog_texto_adicional', '<p>Explore nosso conteúdo e aprofunde seus conhecimentos espíritas.</p>')
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);
