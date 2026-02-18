<?php
$pageTitle = $magazine['seo_title'] ?: $magazine['title'];
$metaDescription = $magazine['seo_meta_description'] ?: strip_tags($magazine['description_html']);
$ogUrl = base_url("revista/{$magazine['slug']}");
$ogImage = $magazine['cover_image_url'] ? base_url($magazine['cover_image_url']) : '';

require_once BASE_PATH . '/views/layout/header.php';
?>

<section class="pt-32 pb-16 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="mb-8">
            <a href="<?= base_url('revista') ?>" class="text-azul-turquesa hover:text-dourado-luz transition inline-flex items-center gap-2">
                ← Voltar para Revistas
            </a>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <div class="lg:col-span-1">
                <?php if ($magazine['cover_image_url']): ?>
                    <div class="sticky top-24">
                        <img src="<?= base_url($magazine['cover_image_url']) ?>" 
                             alt="<?= htmlspecialchars($magazine['title']) ?>"
                             class="w-full max-h-[600px] object-contain rounded-lg border-2 border-azul-medio shadow-2xl mx-auto">
                        
                        <?php if ($magazine['pdf_url']): ?>
                            <a href="<?= htmlspecialchars(convert_gdrive_to_download($magazine['pdf_url'])) ?>" 
                               target="_blank"
                               class="mt-6 w-full bg-dourado-luz hover:bg-dourado-intenso text-azul-noite px-6 py-4 rounded-lg font-bold text-center transition flex items-center justify-center gap-3 text-lg">
                                <span class="text-2xl">📥</span> Baixar PDF
                            </a>
                        <?php endif; ?>
                        
                        <div class="mt-4 text-center text-sm text-cinza-azulado">
                            Publicado em <?= date('d/m/Y', strtotime($magazine['published_at'])) ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="lg:col-span-2">
                <h1 class="text-4xl md:text-5xl font-bold text-dourado-luz mb-6">
                    <?= htmlspecialchars($magazine['title']) ?>
                </h1>
                
                <?php if ($magazine['description_html']): ?>
                    <div class="prose prose-invert prose-lg max-w-none">
                        <div class="text-cinza-azulado leading-relaxed">
                            <?= $magazine['description_html'] ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="mt-12 pt-8 border-t border-azul-medio">
                    <h3 class="text-2xl font-bold text-dourado-luz mb-6">Compartilhar</h3>
                    <div class="flex gap-4">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($ogUrl) ?>" 
                           target="_blank"
                           class="bg-azul-cosmico hover:bg-azul-medio text-white px-6 py-3 rounded-lg transition">
                            Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?= urlencode($ogUrl) ?>&text=<?= urlencode($magazine['title']) ?>" 
                           target="_blank"
                           class="bg-azul-cosmico hover:bg-azul-medio text-white px-6 py-3 rounded-lg transition">
                            Twitter
                        </a>
                        <a href="https://api.whatsapp.com/send?text=<?= urlencode($magazine['title'] . ' - ' . $ogUrl) ?>" 
                           target="_blank"
                           class="bg-azul-cosmico hover:bg-azul-medio text-white px-6 py-3 rounded-lg transition">
                            WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once BASE_PATH . '/views/layout/footer.php'; ?>
