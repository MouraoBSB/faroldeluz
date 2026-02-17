-- Criar tabela de diálogos
-- Autor: Thiago Mourão
-- URL: https://www.instagram.com/mouraoeguerin/
-- Data: 2026-02-16 22:45:00

CREATE TABLE IF NOT EXISTS dialogos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description_html TEXT,
    youtube_url VARCHAR(500) NOT NULL,
    cover_image_url VARCHAR(500),
    published_at DATETIME,
    status ENUM('draft', 'published') DEFAULT 'draft',
    seo_title VARCHAR(255),
    seo_meta_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_published_at (published_at),
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
