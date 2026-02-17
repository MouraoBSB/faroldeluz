INSERT INTO settings (`setting_key`, `setting_value`) VALUES
('rajian_titulo', 'Grupo de Estudo Rajian'),
('rajian_descricao', '<p>Bem-vindo ao Grupo de Estudo Rajian, um espaço dedicado ao aprofundamento do conhecimento espírita.</p>'),
('rajian_imagem_destaque', ''),
('rajian_texto_adicional', '<p>Participe conosco nesta jornada de aprendizado e evolução espiritual.</p>')
ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`);
