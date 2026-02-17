<?php
$pageTitle = 'Página não encontrada - ' . SITE_NAME;
require_once BASE_PATH . '/views/layout/header.php';
?>

<section class="min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-6xl md:text-8xl font-bold text-dourado-luz mb-6">404</h1>
        <p class="text-2xl md:text-3xl text-cinza-azulado mb-8">Página não encontrada</p>
        <p class="text-lg text-cinza-azulado mb-8">A página que você procura não existe ou foi movida.</p>
        <a href="<?= base_url() ?>" class="inline-block bg-dourado-luz text-azul-noite px-8 py-4 rounded-lg hover:bg-dourado-intenso transition font-bold text-lg">
            Voltar para o Início
        </a>
    </div>
</section>

<?php require_once BASE_PATH . '/views/layout/footer.php'; ?>
