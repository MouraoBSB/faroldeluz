-- Tabela de relações entre conteúdo e taxonomias
-- Autor: Thiago Mourão
-- URL: https://www.instagram.com/mouraoeguerin/
-- Data: 2026-02-15 16:12:00

CREATE TABLE IF NOT EXISTS taxonomy_relations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    content_type ENUM('blog_post') NOT NULL,
    content_id INT UNSIGNED NOT NULL,
    term_id INT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_relation (content_type, content_id, term_id),
    INDEX idx_content (content_type, content_id),
    INDEX idx_term_id (term_id),
    FOREIGN KEY (term_id) REFERENCES taxonomy_terms(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
