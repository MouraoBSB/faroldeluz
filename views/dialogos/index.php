<?php
$pageTitle = $dialogoSettings['dialogos_titulo'] ?? 'Diálogos do Farol';
$metaDescription = strip_tags($dialogoSettings['dialogos_descricao'] ?? '');
$ogUrl = base_url('dialogos');

require_once BASE_PATH . '/views/layout/header.php';
?>

<section class="pt-32 pb-16 min-h-screen">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold text-dourado-luz mb-12 text-center">
            <?= htmlspecialchars($dialogoSettings['dialogos_titulo'] ?? 'Diálogos do Farol') ?>
        </h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-16 max-w-6xl mx-auto">
            <?php if (!empty($dialogoSettings['dialogos_imagem_destaque'])): ?>
                <div class="order-2 lg:order-1">
                    <img src="<?= base_url($dialogoSettings['dialogos_imagem_destaque']) ?>" 
                         alt="Imagem de destaque" 
                         class="w-full h-full object-cover rounded-lg border border-azul-medio shadow-lg">
                </div>
            <?php endif; ?>
            
            <div class="order-1 lg:order-2 flex items-center">
                <div class="text-lg text-cinza-azulado leading-relaxed prose prose-invert prose-lg max-w-none">
                    <?= $dialogoSettings['dialogos_descricao'] ?? '' ?>
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
                    <select name="order"
                            class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz">
                        <option value="recent" <?= $order === 'recent' ? 'selected' : '' ?>>Mais Recentes</option>
                        <option value="oldest" <?= $order === 'oldest' ? 'selected' : '' ?>>Mais Antigos</option>
                        <option value="title" <?= $order === 'title' ? 'selected' : '' ?>>Ordem Alfabética</option>
                    </select>
                </div>
                
                <div>
                    <button type="submit" class="w-full bg-dourado-luz hover:bg-dourado-intenso text-azul-noite px-4 py-3 rounded-lg font-semibold transition">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>
        
        <?php if (!empty($dialogos)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20">
                <?php foreach ($dialogos as $dialogo): ?>
                    <div class="bg-azul-cosmico rounded-lg border border-azul-medio overflow-hidden hover:border-dourado-luz transition group">
                        <a href="<?= base_url("dialogos/{$dialogo['slug']}") ?>">
                            <div class="h-80 overflow-hidden relative">
                                <?php if ($dialogo['cover_image_url']): ?>
                                    <img src="<?= base_url($dialogo['cover_image_url']) ?>" 
                                         alt="<?= htmlspecialchars($dialogo['title']) ?>"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                <?php else: ?>
                                    <div class="w-full h-full bg-azul-medio flex items-center justify-center">
                                        <span class="text-6xl">📹</span>
                                    </div>
                                <?php endif; ?>
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                    <span class="text-6xl">▶️</span>
                                </div>
                            </div>
                            
                            <div class="p-5">
                                <div class="text-xs text-azul-turquesa mb-2">
                                    <?= date('d/m/Y', strtotime($dialogo['published_at'])) ?>
                                </div>
                                
                                <h3 class="text-base font-bold text-white mb-2 group-hover:text-dourado-luz transition line-clamp-2">
                                    <?= htmlspecialchars($dialogo['title']) ?>
                                </h3>
                                
                                <?php if ($dialogo['description_html']): ?>
                                    <div class="text-sm text-cinza-azulado mb-4 line-clamp-2">
                                        <?= strip_tags($dialogo['description_html']) ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="flex gap-2">
                                    <span class="flex-1 bg-dourado-luz hover:bg-dourado-intenso text-azul-noite px-3 py-2 rounded-lg font-semibold transition text-center text-sm">
                                        Assistir 🎬
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                <div class="flex justify-center gap-2">
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <a href="<?= base_url('dialogos?page=' . $i . ($search ? '&search=' . urlencode($search) : '') . ($order ? '&order=' . $order : '')) ?>" 
                           class="px-4 py-2 rounded <?= $i === $pagination['current_page'] ? 'bg-dourado-luz text-azul-noite' : 'bg-azul-cosmico text-white hover:bg-azul-medio' ?> transition">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="text-center py-20">
                <div class="text-6xl mb-4">🎬</div>
                <p class="text-xl text-cinza-azulado">Nenhum diálogo encontrado</p>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($dialogoSettings['dialogos_texto_adicional'])): ?>
            <div class="max-w-4xl mx-auto mt-16">
                <div class="bg-azul-cosmico/50 rounded-lg border border-azul-medio p-8">
                    <div class="text-lg text-cinza-azulado leading-relaxed prose prose-invert prose-lg max-w-none">
                        <?= $dialogoSettings['dialogos_texto_adicional'] ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once BASE_PATH . '/views/layout/footer.php'; ?>
