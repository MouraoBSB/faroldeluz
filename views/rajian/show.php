<?php
$pageTitle = $rajian['seo_title'] ?: $rajian['title'];
$metaDescription = $rajian['seo_meta_description'] ?: strip_tags($rajian['description_html']);
$ogUrl = base_url("rajian/{$rajian['slug']}");
$ogImage = $rajian['cover_image_url'] ? base_url($rajian['cover_image_url']) : '';

require_once BASE_PATH . '/views/layout/header.php';

function getYoutubeEmbedUrl($url) {
    if (empty($url)) {
        return '';
    }
    
    $videoId = '';
    
    if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $url, $matches)) {
        $videoId = $matches[1];
    } elseif (preg_match('/youtube\.com\/live\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
        $videoId = $matches[1];
    } elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
        $videoId = $matches[1];
    } elseif (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
        $videoId = $matches[1];
    } elseif (preg_match('/youtube\.com\/v\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
        $videoId = $matches[1];
    }
    
    return $videoId ? "https://www.youtube.com/embed/{$videoId}" : '';
}

$embedUrl = getYoutubeEmbedUrl($rajian['youtube_url']);
?>

<article class="pt-32 pb-16 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            <div class="mb-8">
                <a href="<?= base_url('rajian') ?>" class="text-azul-turquesa hover:text-dourado-luz transition inline-flex items-center gap-2">
                    ← Voltar para Rajian
                </a>
            </div>
            
            <header class="mb-8">
                <div class="text-sm text-azul-turquesa mb-4">
                    <?= date('d/m/Y', strtotime($rajian['published_at'])) ?>
                </div>
                
                <h1 class="text-4xl md:text-5xl font-bold text-dourado-luz mb-6">
                    <?= htmlspecialchars($rajian['title']) ?>
                </h1>
                
                <?php if ($rajian['description_html']): ?>
                    <div class="text-lg text-cinza-azulado leading-relaxed prose prose-invert prose-lg max-w-none mb-8">
                        <?= $rajian['description_html'] ?>
                    </div>
                <?php endif; ?>
            </header>
            
            <?php if ($embedUrl): ?>
                <div class="relative w-full mb-12" style="padding-bottom: 56.25%;">
                    <iframe 
                        src="<?= $embedUrl ?>" 
                        class="absolute top-0 left-0 w-full h-full rounded-lg border-2 border-azul-medio shadow-2xl"
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>
            <?php else: ?>
                <div class="bg-azul-cosmico rounded-lg border border-azul-medio p-12 text-center mb-12">
                    <div class="text-6xl mb-4">⚠️</div>
                    <p class="text-xl text-cinza-azulado">Vídeo não disponível</p>
                </div>
            <?php endif; ?>
            
            <div class="bg-azul-cosmico/50 rounded-lg border border-azul-medio p-8">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-dourado-luz mb-2">Compartilhe este estudo</h3>
                        <p class="text-cinza-azulado">Ajude a espalhar conhecimento espírita</p>
                    </div>
                    
                    <div class="flex gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($ogUrl) ?>" 
                           target="_blank"
                           class="bg-[#1877F2] hover:bg-[#0d65d9] text-white px-4 py-3 rounded-lg transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            Facebook
                        </a>
                        
                        <a href="https://twitter.com/intent/tweet?url=<?= urlencode($ogUrl) ?>&text=<?= urlencode($rajian['title']) ?>" 
                           target="_blank"
                           class="bg-[#1DA1F2] hover:bg-[#0d8bd9] text-white px-4 py-3 rounded-lg transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                            Twitter
                        </a>
                        
                        <a href="https://api.whatsapp.com/send?text=<?= urlencode($rajian['title'] . ' - ' . $ogUrl) ?>" 
                           target="_blank"
                           class="bg-[#25D366] hover:bg-[#1fb855] text-white px-4 py-3 rounded-lg transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>

<?php require_once BASE_PATH . '/views/layout/footer.php'; ?>
