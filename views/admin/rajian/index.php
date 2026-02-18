<?php require_once BASE_PATH . '/views/admin/layout/header.php'; ?>

<div class="min-h-screen bg-azul-noite pt-20">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-azul-cosmico rounded-lg border border-azul-medio shadow-lg">
        <div class="p-8">
            <div class="mb-6">
                <a href="<?= base_url('admin/dashboard') ?>" class="text-azul-turquesa hover:text-dourado-luz transition inline-flex items-center gap-2">
                    ← Voltar ao Dashboard
                </a>
            </div>
            
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-dourado-luz">Gerenciar Rajian</h1>
                <a href="<?= base_url('admin/rajian/criar') ?>" class="bg-dourado-luz hover:bg-dourado-intenso text-azul-noite px-6 py-3 rounded-lg font-semibold transition">
                    + Novo Rajian
                </a>
            </div>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-500/20 border border-green-500 text-green-300 px-6 py-4 rounded-lg mb-6">
                    <?= $_SESSION['success'] ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-500/20 border border-red-500 text-red-300 px-6 py-4 rounded-lg mb-6">
                    <?= $_SESSION['error'] ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <div class="mb-6">
                <form method="GET" class="flex gap-4">
                    <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" 
                           placeholder="Buscar por título..."
                           class="flex-1 px-4 py-2 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz">
                    <button type="submit" class="bg-azul-turquesa hover:bg-azul-turquesa/80 text-white px-6 py-2 rounded-lg transition">
                        Buscar
                    </button>
                    <?php if ($search): ?>
                        <a href="<?= base_url('admin/rajian') ?>" class="bg-azul-medio hover:bg-azul-medio/80 text-white px-6 py-2 rounded-lg transition">
                            Limpar
                        </a>
                    <?php endif; ?>
                </form>
            </div>
            
            <?php if (!empty($rajians)): ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-azul-medio">
                                <th class="text-left py-4 px-6 text-dourado-luz font-semibold">Título</th>
                                <th class="text-left py-4 px-6 text-dourado-luz font-semibold">Status</th>
                                <th class="text-left py-4 px-6 text-dourado-luz font-semibold">Data</th>
                                <th class="text-right py-4 px-6 text-dourado-luz font-semibold">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rajians as $rajian): ?>
                                <tr class="border-b border-azul-medio/50 hover:bg-azul-medio/20 transition">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-4">
                                            <?php if (!empty($rajian['cover_image_url'])): ?>
                                                <img src="<?= base_url($rajian['cover_image_url']) ?>" 
                                                     alt="<?= htmlspecialchars($rajian['title']) ?>"
                                                     class="w-16 h-16 object-cover rounded">
                                            <?php else: ?>
                                                <div class="w-16 h-16 bg-azul-medio rounded flex items-center justify-center">
                                                    <span class="text-dourado-luz text-xl">📖</span>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <div class="text-white font-semibold"><?= htmlspecialchars($rajian['title']) ?></div>
                                                <div class="text-sm text-cinza-azulado"><?= $rajian['slug'] ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($rajian['status'] === 'published'): ?>
                                            <span class="bg-green-500/20 text-green-300 px-3 py-1 rounded-full text-sm">Publicado</span>
                                        <?php else: ?>
                                            <span class="bg-yellow-500/20 text-yellow-300 px-3 py-1 rounded-full text-sm">Rascunho</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-cinza-azulado">
                                        <?= date('d/m/Y', strtotime($rajian['published_at'])) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2 justify-end">
                                            <a href="<?= base_url("rajian/{$rajian['slug']}") ?>" 
                                               target="_blank"
                                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition text-sm inline-flex items-center gap-1">
                                                👁️ Visualizar
                                            </a>
                                            <a href="<?= base_url("admin/rajian/{$rajian['id']}/editar") ?>" 
                                               class="bg-azul-turquesa hover:bg-azul-turquesa/80 text-white px-4 py-2 rounded transition text-sm">
                                                Editar
                                            </a>
                                            <form method="POST" action="<?= base_url("admin/rajian/{$rajian['id']}/deletar") ?>" 
                                                  onsubmit="return confirm('Tem certeza que deseja deletar este rajian?');" class="inline">
                                                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition text-sm">
                                                    Deletar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
            <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                <div class="mt-6 flex justify-center gap-2">
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <a href="<?= base_url('admin/rajian?page=' . $i . ($search ? '&search=' . urlencode($search) : '')) ?>" 
                           class="px-4 py-2 rounded <?= $i === $pagination['current_page'] ? 'bg-dourado-luz text-azul-noite' : 'bg-azul-cosmico text-white hover:bg-azul-medio' ?> transition">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
            <?php else: ?>
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">📚</div>
                    <p class="text-xl text-cinza-azulado">Nenhum rajian encontrado</p>
                    <a href="<?= base_url('admin/rajian/criar') ?>" class="inline-block mt-4 bg-dourado-luz hover:bg-dourado-intenso text-azul-noite px-6 py-3 rounded-lg font-semibold transition">
                        Criar Primeiro Rajian
                    </a>
                </div>
            <?php endif; ?>
        </div>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/views/admin/layout/footer.php'; ?>
