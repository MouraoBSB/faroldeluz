-- Tabela de termos de taxonomia (categorias e tags)
-- Autor: Thiago Mourão
-- URL: https://www.instagram.com/mouraoeguerin/
-- Data: 2026-02-15 16:12:00

CREATE TABLE IF NOT EXISTS taxonomy_terms (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    taxonomy_type ENUM('category', 'tag') NOT NULL,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL,
    parent_id INT UNSIGNED NULL,
    description TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_slug_type (slug, taxonomy_type),
    INDEX idx_taxonomy_type (taxonomy_type),
    INDEX idx_parent_id (parent_id),
    FOREIGN KEY (parent_id) REFERENCES taxonomy_terms(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
