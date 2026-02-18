<?php
/**
 * Página Guia Batuíra
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-17 00:55:00
 */

$pageTitle = $settings['batuira_titulo'] ?? 'Guia Batuíra';
$metaDescription = strip_tags($settings['batuira_texto'] ?? '');
$ogUrl = base_url('sobre/batuira');
$ogImage = !empty($settings['batuira_imagem']) ? base_url($settings['batuira_imagem']) : base_url('assets/images/og-default.jpg');

require_once BASE_PATH . '/views/layout/header.php';
?>

<section class="pt-32 pb-16 min-h-screen">
    <div class="container mx-auto px-4 max-w-6xl">
        <h1 class="text-4xl md:text-5xl font-bold text-dourado-luz mb-12 text-center">
            <?= htmlspecialchars($settings['batuira_titulo'] ?? 'Guia Batuíra') ?>
        </h1>
        
        <div class="bg-azul-cosmico rounded-xl p-8 border border-azul-medio">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                <?php if (!empty($settings['batuira_imagem'])): ?>
                <div class="order-1">
                    <img src="<?= base_url($settings['batuira_imagem']) ?>" 
                         alt="<?= htmlspecialchars($settings['batuira_titulo'] ?? 'Guia Batuíra') ?>" 
                         class="w-full rounded-lg shadow-2xl border border-azul-medio sticky top-24">
                </div>
                <?php endif; ?>
                
                <div class="<?= !empty($settings['batuira_imagem']) ? 'order-2' : 'col-span-2' ?>">
                    <div class="prose prose-invert prose-lg max-w-none">
                        <?= $settings['batuira_texto'] ?? '<p class="text-cinza-azulado">Configure o conteúdo desta página em Configurações > Página Guia Batuíra.</p>' ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (!empty($settings['batuira_imagem_adicional'])): ?>
        <div class="mt-16 max-w-4xl mx-auto">
            <div class="cursor-pointer" onclick="openLightbox('<?= base_url($settings['batuira_imagem_adicional']) ?>')">
                <img src="<?= base_url($settings['batuira_imagem_adicional']) ?>" 
                     alt="Imagem adicional" 
                     class="w-full rounded-lg border border-azul-medio shadow-lg hover:shadow-2xl transition-shadow duration-300">
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Lightbox Modal -->
<div id="lightbox" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4" onclick="closeLightbox()">
    <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white text-4xl hover:text-dourado-luz transition z-10">
        &times;
    </button>
    <img id="lightbox-img" src="" alt="" class="max-w-full max-h-[90vh] object-contain" onclick="event.stopPropagation()">
</div>

<script>
function openLightbox(imageSrc) {
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    lightboxImg.src = imageSrc;
    lightbox.classList.remove('hidden');
    lightbox.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    const lightbox = document.getElementById('lightbox');
    lightbox.classList.add('hidden');
    lightbox.classList.remove('flex');
    document.body.style.overflow = 'auto';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLightbox();
    }
});
</script>

<?php require_once BASE_PATH . '/views/layout/footer.php'; ?>
