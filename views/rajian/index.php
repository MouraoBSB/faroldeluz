<?php
$pageTitle = $rajianSettings['rajian_titulo'] ?? 'Grupo de Estudo Rajian';
$metaDescription = strip_tags($rajianSettings['rajian_descricao'] ?? '');
$ogUrl = base_url('rajian');

require_once BASE_PATH . '/views/layout/header.php';
?>

<section class="pt-32 pb-16 min-h-screen">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold text-dourado-luz mb-12 text-center">
            <?= htmlspecialchars($rajianSettings['rajian_titulo'] ?? 'Grupo de Estudo Rajian') ?>
        </h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-24 max-w-6xl mx-auto">
            <?php if (!empty($rajianSettings['rajian_imagem_destaque'])): ?>
                <div class="order-2 lg:order-1">
                    <img src="<?= base_url($rajianSettings['rajian_imagem_destaque']) ?>" 
                         alt="Imagem de destaque" 
                         class="w-full h-full object-cover rounded-lg border border-azul-medio shadow-lg mb-6">
                    
                    <?php if (!empty($rajianSettings['rajian_whatsapp_group_url'])): ?>
                        <a href="<?= htmlspecialchars($rajianSettings['rajian_whatsapp_group_url']) ?>" 
                           target="_blank"
                           rel="noopener noreferrer"
                           class="flex items-center justify-center gap-3 bg-green-600 hover:bg-green-700 text-white px-6 py-4 rounded-lg font-bold text-base transition shadow-lg hover:shadow-xl w-full">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            Entre no grupo de WhatsApp
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="order-1 lg:order-2 flex items-center">
                <div class="text-lg text-cinza-azulado leading-relaxed prose prose-invert prose-lg max-w-none">
                    <?= $rajianSettings['rajian_descricao'] ?? '' ?>
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
        
        <?php if (!empty($rajians)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20">
                <?php foreach ($rajians as $rajian): ?>
                    <div class="bg-azul-cosmico rounded-lg border border-azul-medio overflow-hidden hover:border-dourado-luz transition group">
                        <a href="<?= base_url("rajian/{$rajian['slug']}") ?>">
                            <div class="h-80 overflow-hidden relative">
                                <?php if (!empty($rajian['youtube_video_id'])): ?>
                                    <img src="https://img.youtube.com/vi/<?= htmlspecialchars($rajian['youtube_video_id']) ?>/hqdefault.jpg" 
                                         alt="<?= htmlspecialchars($rajian['title']) ?>"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                         loading="lazy">
                                <?php elseif (!empty($rajian['cover_image_url'])): ?>
                                    <img src="<?= base_url($rajian['cover_image_url']) ?>" 
                                         alt="<?= htmlspecialchars($rajian['title']) ?>"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                <?php else: ?>
                                    <div class="w-full h-full bg-azul-medio flex items-center justify-center">
                                        <span class="text-6xl">📚</span>
                                    </div>
                                <?php endif; ?>
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                    <span class="text-6xl">▶️</span>
                                </div>
                            </div>
                            
                            <div class="p-5">
                                <div class="text-xs text-azul-turquesa mb-2">
                                    <?= date('d/m/Y', strtotime($rajian['published_at'])) ?>
                                </div>
                                
                                <h3 class="text-base font-bold text-white mb-2 group-hover:text-dourado-luz transition line-clamp-2">
                                    <?= htmlspecialchars($rajian['title']) ?>
                                </h3>
                                
                                <?php if ($rajian['description_html']): ?>
                                    <div class="text-sm text-cinza-azulado mb-4 line-clamp-2">
                                        <?= strip_tags($rajian['description_html']) ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="flex gap-2">
                                    <span class="flex-1 bg-dourado-luz hover:bg-dourado-intenso text-azul-noite px-3 py-2 rounded-lg font-semibold transition text-center text-sm">
                                        Assistir 📖
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
                        <a href="<?= base_url('rajian?page=' . $i . ($search ? '&search=' . urlencode($search) : '') . ($order ? '&order=' . $order : '')) ?>" 
                           class="px-4 py-2 rounded <?= $i === $pagination['current_page'] ? 'bg-dourado-luz text-azul-noite' : 'bg-azul-cosmico text-white hover:bg-azul-medio' ?> transition">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="text-center py-20">
                <div class="text-6xl mb-4">📚</div>
                <p class="text-xl text-cinza-azulado">Nenhum estudo encontrado</p>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($rajianSettings['rajian_texto_adicional'])): ?>
            <div class="max-w-4xl mx-auto mt-16">
                <div class="bg-azul-cosmico/50 rounded-lg border border-azul-medio p-8">
                    <div class="text-lg text-cinza-azulado leading-relaxed prose prose-invert prose-lg max-w-none">
                        <?= $rajianSettings['rajian_texto_adicional'] ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once BASE_PATH . '/views/layout/footer.php'; ?>
