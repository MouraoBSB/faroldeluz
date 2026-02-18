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
    <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold text-dourado-luz mb-12 text-center">
            <?= htmlspecialchars($settings['sobre_titulo'] ?? 'O Projeto Farol de Luz') ?>
        </h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-16 max-w-6xl mx-auto">
            <?php if (!empty($settings['sobre_imagem'])): ?>
                <div class="order-2 lg:order-1 flex items-start justify-center">
                    <img src="<?= base_url($settings['sobre_imagem']) ?>" 
                         alt="<?= htmlspecialchars($settings['sobre_titulo'] ?? 'O Projeto') ?>" 
                         class="w-full max-h-[500px] object-contain rounded-lg border border-azul-medio shadow-lg">
                </div>
            <?php endif; ?>
            
            <div class="order-1 lg:order-2 flex items-center">
                <div class="prose prose-invert prose-lg max-w-none">
                    <?php if (!empty($settings['sobre_texto'])): ?>
                        <?= $settings['sobre_texto'] ?>
                    <?php else: ?>
                        <p class="text-cinza-azulado">Configure o conteúdo desta página em Configurações > Página Sobre.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once BASE_PATH . '/views/layout/footer.php'; ?>
