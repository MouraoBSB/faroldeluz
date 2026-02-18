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
            
            <div class="post-content mb-12">
                <?= $post['content_html'] ?>
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
/* Estilos customizados para posts do blog - Baseado em padrão WordPress */

:root {
  --color-link: #4A9EFF;
  --color-link-hover: #06BCC1;
  --color-quote-border: #E8B86D;
  --width-quote-border: 3px;
  --bg-quote: rgba(232, 184, 109, 0.1);
  --color-code-text: #89E3E4;
  --bg-code: #0B0515;
  --space-s: 0.25rem;
  --space-m: 0.75rem;
  --space-l: 1.25rem;
  --space-xl: 2.25rem;
  --space-xxl: 2.5rem;
  --radius-m: 0.75rem;
  --transition-default: 0.2s ease-in-out;
  --font-weight-light: 300;
  --font-weight-regular: 400;
  --font-weight-medium: 500;
  --font-weight-bold: 700;
  --font-size-xs: .85rem;
  --font-size-p: 1.15rem;
  --mobile-font-size-p: 1.15rem;
  --font-size-m: 1.5rem;
  --mobile-font-size-m: 1.5rem;
  --font-size-l: 2rem;
  --mobile-font-size-l: 2rem;
  --line-height-body: 1.75em;
  --line-height-heading: 1.25em;
  --line-height-list: 1.2em;
}

/* Headings */
.post-content h2, 
.post-content h3, 
.post-content h4, 
.post-content h5, 
.post-content h6 {
  padding-top: var(--space-xxl);
  padding-bottom: var(--space-s);
  font-weight: var(--font-weight-bold);
  line-height: var(--line-height-heading);
  color: #E8B86D;
}

.post-content h2 {
  font-size: var(--font-size-l);
}

.post-content h3, 
.post-content h4, 
.post-content h5, 
.post-content h6 {
  font-size: var(--font-size-m);
}

/* Paragraphs */
.post-content p {
  padding-bottom: var(--space-l);
  margin-bottom: 0;
  line-height: var(--line-height-body);
  font-size: var(--font-size-p);
  color: #B8C5D6;
}

/* Links */
.post-content p a,
.post-content a {
  color: var(--color-link);
  font-weight: var(--font-weight-medium);
  text-decoration: underline;
  transition: color var(--transition-default);
}

.post-content p a:hover,
.post-content a:hover {
  color: var(--color-link-hover);
}

/* Lists */
.post-content ul,
.post-content ol {
  font-size: var(--font-size-p);
  font-weight: var(--font-weight-medium);
  padding-bottom: var(--space-xl);
  padding-left: 2rem;
  color: #B8C5D6;
}

.post-content ul li,
.post-content ol li {
  line-height: var(--line-height-list);
  margin-bottom: var(--space-l);
}

/* Images */
.post-content img {
  margin: var(--space-xxl) 0;
  border-radius: var(--radius-m);
  max-width: 100%;
  height: auto;
  border: 1px solid #2A3F5F;
}

/* Blockquotes */
.post-content blockquote {
  border-left: var(--width-quote-border) solid var(--color-quote-border);
  margin: var(--space-xxl) 0;
  padding: var(--space-m) var(--space-l);
  background: var(--bg-quote);
  font-size: var(--font-size-p);
  font-weight: var(--font-weight-regular);
  border-radius: 0 var(--radius-m) var(--radius-m) 0;
}

.post-content blockquote cite {
  font-size: var(--font-size-xs);
  font-weight: var(--font-weight-light);
  color: #8FA3C1;
}

.post-content blockquote p {
  padding-bottom: 0;
  color: #B8C5D6;
}

/* Code Blocks */
.post-content pre {
  background: var(--bg-code);
  padding: var(--space-xl);
  border-radius: var(--radius-m);
  overflow-x: auto;
  margin: var(--space-xxl) 0;
}

.post-content code {
  color: var(--color-code-text);
  font-family: 'Courier New', monospace;
  font-size: 0.9em;
}

.post-content p code {
  background: var(--bg-code);
  padding: 0.2em 0.4em;
  border-radius: 0.25rem;
}

/* Tables */
.post-content table {
  width: 100%;
  border-collapse: collapse;
  margin: var(--space-xxl) 0;
  border-radius: var(--radius-m);
  overflow: hidden;
}

.post-content table th,
.post-content table td {
  border: 1px solid #2A3F5F;
  padding: var(--space-m);
  text-align: left;
}

.post-content table th {
  background-color: #1A2332;
  color: #E8B86D;
  font-weight: var(--font-weight-bold);
}

.post-content table td {
  color: #B8C5D6;
}

/* TinyMCE Columns Support */
.post-content .row-2cols { 
  display: flex; 
  gap: 20px; 
  margin: var(--space-xxl) 0; 
  flex-wrap: wrap; 
}

.post-content .row-2cols .col { 
  flex: 1; 
  min-width: 250px; 
}

.post-content .row-3cols { 
  display: flex; 
  gap: 15px; 
  margin: var(--space-xxl) 0; 
  flex-wrap: wrap; 
}

.post-content .row-3cols .col { 
  flex: 1; 
  min-width: 200px; 
}

/* Mobile responsiveness */
@media screen and (max-width: 767px) {
  .post-content p {
    font-size: var(--mobile-font-size-p);
  }

  .post-content h2 {
    font-size: var(--mobile-font-size-l);
  }

  .post-content h3, 
  .post-content h4, 
  .post-content h5, 
  .post-content h6 {
    font-size: var(--mobile-font-size-m);
  }
  
  .post-content .row-2cols, 
  .post-content .row-3cols { 
    flex-direction: column; 
  }
}
</style>

<?php require_once BASE_PATH . '/views/layout/footer.php'; ?>
