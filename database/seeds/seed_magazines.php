<?php
/**
 * Seed de Revistas para Teste
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-16 14:21:00
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';

$db = Database::getInstance()->getConnection();

$magazines = [
    [
        'title' => 'Edição 01 - O Despertar da Consciência',
        'slug' => 'edicao-01-despertar-consciencia',
        'description_html' => '<p>Primeira edição da Revista Farol de Luz, explorando os fundamentos da doutrina espírita e o despertar da consciência humana.</p>',
        'pdf_url' => 'https://drive.google.com/file/d/exemplo1',
        'published_at' => '2025-01-15 00:00:00',
        'status' => 'published'
    ],
    [
        'title' => 'Edição 02 - A Importância da Prece',
        'slug' => 'edicao-02-importancia-prece',
        'description_html' => '<p>Reflexões sobre o poder da prece e sua importância na jornada espiritual.</p>',
        'pdf_url' => 'https://drive.google.com/file/d/exemplo2',
        'published_at' => '2025-02-15 00:00:00',
        'status' => 'published'
    ],
    [
        'title' => 'Edição 03 - Mediunidade e Responsabilidade',
        'slug' => 'edicao-03-mediunidade-responsabilidade',
        'description_html' => '<p>Estudo aprofundado sobre a mediunidade e as responsabilidades do médium.</p>',
        'pdf_url' => 'https://drive.google.com/file/d/exemplo3',
        'published_at' => '2025-03-15 00:00:00',
        'status' => 'published'
    ],
    [
        'title' => 'Edição 04 - Caridade: O Amor em Ação',
        'slug' => 'edicao-04-caridade-amor-acao',
        'description_html' => '<p>A caridade como expressão máxima do amor cristão e sua prática no cotidiano.</p>',
        'pdf_url' => 'https://drive.google.com/file/d/exemplo4',
        'published_at' => '2025-04-15 00:00:00',
        'status' => 'published'
    ],
    [
        'title' => 'Edição 05 - Reencarnação e Evolução',
        'slug' => 'edicao-05-reencarnacao-evolucao',
        'description_html' => '<p>Compreendendo o processo reencarnatório e sua importância para a evolução espiritual.</p>',
        'pdf_url' => 'https://drive.google.com/file/d/exemplo5',
        'published_at' => '2025-05-15 00:00:00',
        'status' => 'published'
    ],
    [
        'title' => 'Edição 06 - O Evangelho à Luz do Espiritismo',
        'slug' => 'edicao-06-evangelho-luz-espiritismo',
        'description_html' => '<p>Análise dos ensinamentos de Jesus sob a ótica da doutrina espírita.</p>',
        'pdf_url' => 'https://drive.google.com/file/d/exemplo6',
        'published_at' => '2025-06-15 00:00:00',
        'status' => 'published'
    ],
    [
        'title' => 'Edição 07 - Obsessão e Desobsessão',
        'slug' => 'edicao-07-obsessao-desobsessao',
        'description_html' => '<p>Entendendo os processos obsessivos e as práticas de desobsessão.</p>',
        'pdf_url' => 'https://drive.google.com/file/d/exemplo7',
        'published_at' => '2025-07-15 00:00:00',
        'status' => 'published'
    ],
    [
        'title' => 'Edição 08 - A Vida no Mundo Espiritual',
        'slug' => 'edicao-08-vida-mundo-espiritual',
        'description_html' => '<p>Explorando a vida após a morte e as colônias espirituais.</p>',
        'pdf_url' => 'https://drive.google.com/file/d/exemplo8',
        'published_at' => '2025-08-15 00:00:00',
        'status' => 'published'
    ],
    [
        'title' => 'Edição 09 - Reforma Íntima',
        'slug' => 'edicao-09-reforma-intima',
        'description_html' => '<p>O processo de transformação interior e o trabalho de autoaperfeiçoamento.</p>',
        'pdf_url' => 'https://drive.google.com/file/d/exemplo9',
        'published_at' => '2025-09-15 00:00:00',
        'status' => 'published'
    ],
    [
        'title' => 'Edição 10 - Espiritismo e Ciência',
        'slug' => 'edicao-10-espiritismo-ciencia',
        'description_html' => '<p>A relação entre o espiritismo e as descobertas científicas modernas.</p>',
        'pdf_url' => 'https://drive.google.com/file/d/exemplo10',
        'published_at' => '2025-10-15 00:00:00',
        'status' => 'published'
    ]
];

echo "Inserindo revistas de exemplo...\n\n";

foreach ($magazines as $index => $magazine) {
    $stmt = $db->prepare("
        INSERT INTO magazines (title, slug, description_html, pdf_url, published_at, status, seo_title, seo_meta_description, created_at, updated_at)
        VALUES (:title, :slug, :description_html, :pdf_url, :published_at, :status, :seo_title, :seo_meta_description, NOW(), NOW())
    ");
    
    $stmt->execute([
        'title' => $magazine['title'],
        'slug' => $magazine['slug'],
        'description_html' => $magazine['description_html'],
        'pdf_url' => $magazine['pdf_url'],
        'published_at' => $magazine['published_at'],
        'status' => $magazine['status'],
        'seo_title' => $magazine['title'],
        'seo_meta_description' => strip_tags($magazine['description_html'])
    ]);
    
    echo "✓ Revista " . ($index + 1) . " inserida: {$magazine['title']}\n";
}

echo "\n✅ Seed concluído! 10 revistas inseridas com sucesso.\n";
