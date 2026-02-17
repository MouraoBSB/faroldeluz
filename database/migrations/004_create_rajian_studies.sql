-- Tabela do Grupo de Estudos Rajian
-- Autor: Thiago Mourão
-- URL: https://www.instagram.com/mouraoeguerin/
-- Data: 2026-02-15 16:12:00

CREATE TABLE IF NOT EXISTS rajian_studies (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    youtube_url VARCHAR(500) NOT NULL,
    youtube_video_id VARCHAR(50) NOT NULL,
    description_html LONGTEXT NULL,
    published_at DATETIME NOT NULL,
    status ENUM('draft', 'published') NOT NULL DEFAULT 'draft',
    seo_title VARCHAR(255) NULL,
    seo_meta_description VARCHAR(300) NULL,
    seo_keywords VARCHAR(300) NULL,
    seo_canonical VARCHAR(500) NULL,
    og_image_url VARCHAR(500) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    INDEX idx_published_at (published_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
