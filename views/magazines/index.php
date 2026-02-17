<?php
$pageTitle = $magazineSettings['revista_titulo'] ?? 'Revista Espírita Farol de Luz';
$metaDescription = strip_tags($magazineSettings['revista_descricao'] ?? '');
$ogUrl = base_url('revista');

require_once BASE_PATH . '/views/layout/header.php';
?>

<section class="pt-32 pb-16 min-h-screen">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold text-dourado-luz mb-12 text-center">
            <?= htmlspecialchars($magazineSettings['revista_titulo'] ?? 'Revista Espírita Farol de Luz') ?>
        </h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-16 max-w-6xl mx-auto">
            <?php if (!empty($magazineSettings['revista_imagem_destaque'])): ?>
                <div class="order-2 lg:order-1">
                    <img src="<?= base_url($magazineSettings['revista_imagem_destaque']) ?>" 
                         alt="Imagem de destaque da revista" 
                         class="w-full h-full object-cover rounded-lg border border-azul-medio shadow-lg">
                </div>
            <?php endif; ?>
            
            <div class="order-1 lg:order-2 flex items-center">
                <div class="text-lg text-cinza-azulado leading-relaxed prose prose-invert prose-lg max-w-none">
                    <?= $magazineSettings['revista_descricao'] ?? '' ?>
                </div>
            </div>
        </div>
        
        <div class="bg-azul-cosmico rounded-lg border border-azul-medio p-6 mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" 
                           placeholder="Buscar por título..."
                           class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz">
                </div>
                
                <div>
                    <select name="order" class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz">
                        <option value="recent" <?= ($order ?? 'recent') === 'recent' ? 'selected' : '' ?>>Mais Recentes</option>
                        <option value="oldest" <?= ($order ?? '') === 'oldest' ? 'selected' : '' ?>>Mais Antigas</option>
                        <option value="title" <?= ($order ?? '') === 'title' ? 'selected' : '' ?>>Título (A-Z)</option>
                    </select>
                </div>
                
                <div>
                    <button type="submit" class="w-full bg-dourado-luz hover:bg-dourado-intenso text-azul-noite px-6 py-3 rounded-lg font-semibold transition">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>
        
        <?php if (empty($magazines)): ?>
            <div class="text-center py-16">
                <div class="text-6xl mb-4">📚</div>
                <h3 class="text-2xl font-bold text-dourado-luz mb-2">Nenhuma revista encontrada</h3>
                <p class="text-cinza-azulado">Em breve teremos novas edições disponíveis.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20">
                <?php foreach ($magazines as $magazine): ?>
                    <div class="bg-azul-cosmico rounded-lg border border-azul-medio overflow-hidden hover:border-dourado-luz transition group">
                        <?php if ($magazine['cover_image_url']): ?>
                            <div class="h-80 overflow-hidden">
                                <img src="<?= base_url($magazine['cover_image_url']) ?>" 
                                     alt="<?= htmlspecialchars($magazine['title']) ?>"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                     loading="lazy">
                            </div>
                        <?php endif; ?>
                        
                        <div class="p-5">
                            <div class="text-xs text-azul-turquesa mb-2">
                                <?= date('d/m/Y', strtotime($magazine['published_at'])) ?>
                            </div>
                            
                            <h3 class="text-base font-bold text-white mb-2 group-hover:text-dourado-luz transition line-clamp-1">
                                <?= htmlspecialchars($magazine['title']) ?>
                            </h3>
                            
                            <?php if ($magazine['description_html']): ?>
                                <div class="text-sm text-cinza-azulado mb-4 line-clamp-2">
                                    <?= strip_tags($magazine['description_html']) ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex gap-2">
                                <a href="<?= base_url("revista/{$magazine['slug']}") ?>" 
                                   class="flex-1 bg-azul-medio hover:bg-azul-turquesa text-white px-3 py-2 rounded-lg text-center text-sm transition">
                                    Detalhes
                                </a>
                                
                                <?php if ($magazine['pdf_url']): ?>
                                    <a href="<?= htmlspecialchars($magazine['pdf_url']) ?>" 
                                       target="_blank"
                                       class="flex-1 bg-dourado-luz hover:bg-dourado-intenso text-azul-noite px-3 py-2 rounded-lg font-semibold transition text-center text-sm">
                                        📥 PDF
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                <div class="flex justify-center gap-2">
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <a href="<?= base_url('revista?page=' . $i . ($search ? '&search=' . urlencode($search) : '') . ($order ? '&order=' . $order : '')) ?>" 
                           class="px-4 py-2 rounded <?= $i === $pagination['current_page'] ? 'bg-dourado-luz text-azul-noite' : 'bg-azul-cosmico text-white hover:bg-azul-medio' ?> transition">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <?php if (!empty($magazineSettings['revista_texto_adicional'])): ?>
            <div class="max-w-4xl mx-auto mt-16">
                <div class="bg-azul-cosmico/50 rounded-lg border border-azul-medio p-8">
                    <div class="text-lg text-cinza-azulado leading-relaxed prose prose-invert prose-lg max-w-none">
                        <?= $magazineSettings['revista_texto_adicional'] ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once BASE_PATH . '/views/layout/footer.php'; ?>
