<?php
$pageTitle = $blogSettings['blog_titulo'] ?? 'Blog Farol de Luz';
$metaDescription = strip_tags($blogSettings['blog_descricao'] ?? '');
$ogUrl = base_url('blog');

require_once BASE_PATH . '/views/layout/header.php';
?>

<section class="pt-32 pb-16 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-dourado-luz mb-6">
                <?= htmlspecialchars($blogSettings['blog_titulo'] ?? 'Blog Farol de Luz') ?>
            </h1>
            <p class="text-xl text-cinza-azulado max-w-3xl mx-auto">
                <?= $blogSettings['blog_descricao'] ?? '' ?>
            </p>
        </div>
        
        <!-- Filtro de Categorias -->
        <?php if (!empty($categories)): ?>
        <div class="mb-8 flex flex-wrap gap-3 justify-center">
            <a href="<?= base_url('blog') ?>" 
               class="px-4 py-2 rounded-lg font-semibold transition <?= !$categoryFilter ? 'bg-dourado-luz text-azul-noite' : 'bg-azul-cosmico text-white hover:bg-azul-medio' ?>">
                Todas
            </a>
            <?php foreach ($categories as $category): ?>
                <a href="<?= base_url('blog?category=' . $category['id']) ?>" 
                   class="px-4 py-2 rounded-lg font-semibold transition <?= $categoryFilter == $category['id'] ? 'bg-dourado-luz text-azul-noite' : 'bg-azul-cosmico text-white hover:bg-azul-medio' ?>">
                    <?= htmlspecialchars($category['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-16 max-w-6xl mx-auto">
            <?php if (!empty($blogSettings['blog_imagem_destaque'])): ?>
                <div class="order-2 lg:order-1 flex items-start justify-center">
                    <img src="<?= base_url($blogSettings['blog_imagem_destaque']) ?>" 
                         alt="Imagem de destaque" 
                         class="w-full max-h-[500px] object-contain rounded-lg border border-azul-medio shadow-lg">
                </div>
            <?php endif; ?>
            
            <div class="order-1 lg:order-2 flex items-center">
                <div class="text-lg text-cinza-azulado leading-relaxed prose prose-invert prose-lg max-w-none">
                    <?php if (!empty($blogSettings['blog_descricao'])): ?>
                        <?= $blogSettings['blog_descricao'] ?>
                    <?php else: ?>
                        <p>Bem-vindo ao Blog Farol de Luz, um espaço dedicado a reflexões, estudos e compartilhamento de conhecimento espírita.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="bg-azul-cosmico rounded-lg border border-azul-medio p-6 mb-8 max-w-2xl mx-auto">
            <form method="GET" class="flex gap-4">
                <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" 
                       placeholder="Buscar artigos..."
                       class="flex-1 px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz">
                <button type="submit" class="bg-dourado-luz hover:bg-dourado-intenso text-azul-noite px-6 py-3 rounded-lg font-semibold transition">
                    Buscar
                </button>
            </form>
        </div>
        
        <?php if (!empty($posts)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20">
                <?php foreach ($posts as $post): ?>
                    <article class="bg-azul-cosmico rounded-lg border border-azul-medio overflow-hidden hover:border-dourado-luz transition group">
                        <a href="<?= base_url("blog/{$post['slug']}") ?>">
                            <?php if ($post['featured_image_url']): ?>
                                <div class="h-48 overflow-hidden">
                                    <img src="<?= base_url($post['featured_image_url']) ?>" 
                                         alt="<?= htmlspecialchars($post['title']) ?>"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                </div>
                            <?php endif; ?>
                            
                            <div class="p-6">
                                <div class="flex items-center gap-2 text-xs text-azul-turquesa mb-3">
                                    <span>📅 <?= date('d/m/Y', strtotime($post['published_at'])) ?></span>
                                    <span>•</span>
                                    <span>🕐 <?= date('H:i', strtotime($post['published_at'])) ?></span>
                                </div>
                                
                                <?php if (!empty($post['taxonomies'])): ?>
                                    <?php $postCategories = array_filter($post['taxonomies'], function($t) { return $t['taxonomy_type'] === 'category'; }); ?>
                                    <?php if (!empty($postCategories)): ?>
                                        <div class="flex flex-wrap gap-2 mb-3">
                                            <?php foreach ($postCategories as $category): ?>
                                                <span class="inline-block bg-azul-medio text-dourado-luz px-2 py-1 rounded text-xs font-semibold">
                                                    🏷️ <?= htmlspecialchars($category['name']) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                <h2 class="text-xl font-bold text-white mb-3 group-hover:text-dourado-luz transition line-clamp-2">
                                    <?= htmlspecialchars($post['title']) ?>
                                </h2>
                                
                                <p class="text-cinza-azulado mb-4 line-clamp-3">
                                    <?= htmlspecialchars($post['excerpt']) ?>
                                </p>
                                
                                <span class="inline-block bg-dourado-luz hover:bg-dourado-intenso text-azul-noite px-4 py-2 rounded-lg font-semibold transition text-sm">
                                    Ler mais →
                                </span>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
            
            <?php if (isset($pagination) && $pagination['last_page'] > 1): ?>
                <div class="flex justify-center gap-2">
                    <?php for ($i = 1; $i <= $pagination['last_page']; $i++): ?>
                        <a href="<?= base_url('blog?page=' . $i . ($search ? '&search=' . urlencode($search) : '')) ?>" 
                           class="px-4 py-2 rounded <?= $i === $pagination['current_page'] ? 'bg-dourado-luz text-azul-noite' : 'bg-azul-cosmico text-white hover:bg-azul-medio' ?> transition">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="text-center py-20">
                <div class="text-6xl mb-4">📝</div>
                <p class="text-xl text-cinza-azulado">Nenhum post encontrado</p>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($blogSettings['blog_texto_adicional'])): ?>
            <div class="max-w-4xl mx-auto mt-16">
                <div class="bg-azul-cosmico/50 rounded-lg border border-azul-medio p-8">
                    <div class="text-lg text-cinza-azulado leading-relaxed prose prose-invert prose-lg max-w-none">
                        <?= $blogSettings['blog_texto_adicional'] ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once BASE_PATH . '/views/layout/footer.php'; ?>
