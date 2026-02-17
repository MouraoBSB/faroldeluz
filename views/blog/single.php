<?php
$pageTitle = $post['seo_title'] ?: $post['title'];
$metaDescription = $post['seo_meta_description'] ?: $post['excerpt'];
$ogUrl = base_url("blog/{$post['slug']}");
$ogImage = $post['featured_image_url'] ? base_url($post['featured_image_url']) : '';

require_once BASE_PATH . '/views/layout/header.php';
?>

<article class="pt-32 pb-16 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="mb-8">
                <a href="<?= base_url('blog') ?>" class="text-azul-turquesa hover:text-dourado-luz transition inline-flex items-center gap-2">
                    ← Voltar para Blog
                </a>
            </div>
            
            <?php if ($post['featured_image_url']): ?>
                <div class="mb-8 rounded-lg overflow-hidden border-2 border-azul-medio">
                    <img src="<?= base_url($post['featured_image_url']) ?>" 
                         alt="<?= htmlspecialchars($post['title']) ?>"
                         class="w-full h-auto">
                </div>
            <?php endif; ?>
            
            <header class="mb-8">
                <div class="flex items-center gap-3 text-sm text-azul-turquesa mb-4">
                    <span>📅 <?= date('d/m/Y', strtotime($post['published_at'])) ?></span>
                    <span>•</span>
                    <span>🕐 <?= date('H:i', strtotime($post['published_at'])) ?></span>
                </div>
                
                <h1 class="text-4xl md:text-5xl font-bold text-dourado-luz mb-6">
                    <?= htmlspecialchars($post['title']) ?>
                </h1>
                
                <p class="text-xl text-cinza-azulado leading-relaxed">
                    <?= htmlspecialchars($post['excerpt']) ?>
                </p>
            </header>
            
            <?php if (!empty($taxonomies)): ?>
                <div class="mb-8 flex flex-wrap gap-3">
                    <?php 
                    $categories = array_filter($taxonomies, function($t) { return $t['taxonomy_type'] === 'category'; });
                    $tags = array_filter($taxonomies, function($t) { return $t['taxonomy_type'] === 'tag'; });
                    ?>
                    
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <a href="<?= base_url('blog?category=' . $category['id']) ?>" 
                               class="inline-flex items-center gap-2 bg-azul-cosmico hover:bg-azul-medio text-dourado-luz px-4 py-2 rounded-lg font-semibold transition border border-azul-medio">
                                📁 <?= htmlspecialchars($category['name']) ?>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <?php if (!empty($tags)): ?>
                        <?php foreach ($tags as $tag): ?>
                            <span class="inline-flex items-center gap-2 bg-azul-medio text-white px-3 py-2 rounded-lg text-sm">
                                🏷️ <?= htmlspecialchars($tag['name']) ?>
                            </span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="prose prose-invert prose-lg max-w-none mb-12">
                <style>
                    .row-2cols { display: flex; gap: 20px; margin: 20px 0; flex-wrap: wrap; }
                    .row-2cols .col { flex: 1; min-width: 250px; }
                    .row-3cols { display: flex; gap: 15px; margin: 20px 0; flex-wrap: wrap; }
                    .row-3cols .col { flex: 1; min-width: 200px; }
                    .col img { max-width: 100%; height: auto; display: block; margin-bottom: 10px; border-radius: 8px; }
                    @media (max-width: 768px) {
                        .row-2cols, .row-3cols { flex-direction: column; }
                    }
                </style>
                <div class="prose prose-invert max-w-none">
                    <?= $post['content_html'] ?>
                </div>
            </div>
            
            <div class="bg-azul-cosmico/50 rounded-lg border border-azul-medio p-8">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-dourado-luz mb-2">Compartilhe este artigo</h3>
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
                        
                        <a href="https://twitter.com/intent/tweet?url=<?= urlencode($ogUrl) ?>&text=<?= urlencode($post['title']) ?>" 
                           target="_blank"
                           class="bg-[#1DA1F2] hover:bg-[#0d8bd9] text-white px-4 py-3 rounded-lg transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                            Twitter
                        </a>
                        
                        <a href="https://api.whatsapp.com/send?text=<?= urlencode($post['title'] . ' - ' . $ogUrl) ?>" 
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

<style>
.blog-content img {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    margin: 2rem 0;
    border: 1px solid #2A3F5F;
}

.blog-content h2 {
    color: #E8B86D;
    font-size: 2rem;
    font-weight: bold;
    margin-top: 3rem;
    margin-bottom: 1.5rem;
}

.blog-content h3 {
    color: #E8B86D;
    font-size: 1.5rem;
    font-weight: bold;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.blog-content p {
    margin-bottom: 1.5rem;
    line-height: 1.8;
}

.blog-content ul, .blog-content ol {
    margin: 1.5rem 0;
    padding-left: 2rem;
}

.blog-content li {
    margin-bottom: 0.5rem;
}

.blog-content a {
    color: #4A9EFF;
    text-decoration: underline;
}

.blog-content a:hover {
    color: #E8B86D;
}

.blog-content blockquote {
    border-left: 4px solid #E8B86D;
    padding-left: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    color: #8FA3C1;
}

.blog-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 2rem 0;
}

.blog-content table th,
.blog-content table td {
    border: 1px solid #2A3F5F;
    padding: 0.75rem;
    text-align: left;
}

.blog-content table th {
    background-color: #1A2332;
    color: #E8B86D;
    font-weight: bold;
}
</style>

<?php require_once BASE_PATH . '/views/layout/footer.php'; ?>
