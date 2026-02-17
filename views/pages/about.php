<?php
/**
 * Página O Projeto
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-17 00:51:00
 */

$pageTitle = $settings['sobre_titulo'] ?? 'O Projeto Farol de Luz';
$metaDescription = strip_tags($settings['sobre_texto'] ?? '');
$ogUrl = base_url('sobre');
$ogImage = !empty($settings['sobre_imagem']) ? base_url($settings['sobre_imagem']) : base_url('assets/images/og-default.jpg');

require_once BASE_PATH . '/views/layout/header.php';
?>

<section class="pt-32 pb-16 min-h-screen">
    <div class="container mx-auto px-4 max-w-4xl">
        <h1 class="text-4xl md:text-5xl font-bold text-dourado-luz mb-12 text-center">
            <?= htmlspecialchars($settings['sobre_titulo'] ?? 'O Projeto Farol de Luz') ?>
        </h1>
        
        <?php if (!empty($settings['sobre_imagem'])): ?>
        <div class="mb-12">
            <img src="<?= base_url($settings['sobre_imagem']) ?>" 
                 alt="<?= htmlspecialchars($settings['sobre_titulo'] ?? 'O Projeto') ?>" 
                 class="w-full rounded-xl shadow-2xl border border-azul-medio">
        </div>
        <?php endif; ?>
        
        <div class="prose prose-invert prose-lg max-w-none">
            <div class="bg-azul-cosmico rounded-xl p-8 border border-azul-medio">
                <?= $settings['sobre_texto'] ?? '<p class="text-cinza-azulado">Configure o conteúdo desta página em Configurações > Página Sobre.</p>' ?>
            </div>
        </div>
    </div>
</section>

<?php require_once BASE_PATH . '/views/layout/footer.php'; ?>
