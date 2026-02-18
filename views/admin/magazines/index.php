<?php
$pageTitle = 'Gerenciar Revistas - Admin';
require_once BASE_PATH . '/views/admin/layout/header.php';
?>

<div class="flex h-screen bg-azul-noite">
    <?php require_once BASE_PATH . '/views/admin/layout/sidebar.php'; ?>
    
    <div class="flex-1 overflow-auto">
        <div class="p-8">
            <div class="mb-6">
                <a href="<?= base_url('admin/dashboard') ?>" class="text-azul-turquesa hover:text-dourado-luz transition inline-flex items-center gap-2">
                    ← Voltar ao Dashboard
                </a>
            </div>
            
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-dourado-luz">Gerenciar Revistas</h1>
                <a href="<?= base_url('admin/revistas/criar') ?>" class="bg-dourado-luz hover:bg-dourado-intenso text-azul-noite px-6 py-3 rounded-lg font-semibold transition">
                    + Nova Revista
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
            
            <div class="bg-azul-cosmico rounded-lg border border-azul-medio p-6 mb-6">
                <form method="GET" class="flex gap-4">
                    <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" 
                           placeholder="Buscar revistas..." 
                           class="flex-1 px-4 py-2 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz">
                    <button type="submit" class="bg-azul-turquesa hover:bg-azul-turquesa/80 text-white px-6 py-2 rounded-lg transition">
                        Buscar
                    </button>
                    <?php if ($search): ?>
                        <a href="<?= base_url('admin/revistas') ?>" class="bg-azul-medio hover:bg-azul-medio/80 text-white px-6 py-2 rounded-lg transition">
                            Limpar
                        </a>
                    <?php endif; ?>
                </form>
            </div>
            
            <div class="bg-azul-cosmico rounded-lg border border-azul-medio overflow-hidden">
                <table class="w-full">
                    <thead class="bg-azul-noite">
                        <tr>
                            <th class="px-6 py-4 text-left text-dourado-luz font-semibold">Capa</th>
                            <th class="px-6 py-4 text-left text-dourado-luz font-semibold">Título</th>
                            <th class="px-6 py-4 text-left text-dourado-luz font-semibold">Data</th>
                            <th class="px-6 py-4 text-left text-dourado-luz font-semibold">Status</th>
                            <th class="px-6 py-4 text-left text-dourado-luz font-semibold">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-azul-medio">
                        <?php if (empty($magazines)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-cinza-azulado">
                                    Nenhuma revista encontrada.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($magazines as $magazine): ?>
                                <tr class="hover:bg-azul-noite/50 transition">
                                    <td class="px-6 py-4">
                                        <?php if ($magazine['cover_image_url']): ?>
                                            <img src="<?= base_url($magazine['cover_image_url']) ?>" alt="Capa" class="w-16 h-20 object-cover rounded">
                                        <?php else: ?>
                                            <div class="w-16 h-20 bg-azul-medio rounded flex items-center justify-center text-cinza-azulado text-xs">
                                                Sem capa
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-white font-medium"><?= htmlspecialchars($magazine['title']) ?></div>
                                        <div class="text-sm text-cinza-azulado"><?= htmlspecialchars($magazine['slug']) ?></div>
                                    </td>
                                    <td class="px-6 py-4 text-cinza-azulado">
                                        <?= date('d/m/Y', strtotime($magazine['published_at'])) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($magazine['status'] === 'published'): ?>
                                            <span class="bg-green-500/20 text-green-300 px-3 py-1 rounded-full text-sm">Publicado</span>
                                        <?php else: ?>
                                            <span class="bg-yellow-500/20 text-yellow-300 px-3 py-1 rounded-full text-sm">Rascunho</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <a href="<?= base_url("revista/{$magazine['slug']}") ?>" 
                                               target="_blank"
                                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition text-sm inline-flex items-center gap-1">
                                                👁️ Visualizar
                                            </a>
                                            <a href="<?= base_url("admin/revistas/{$magazine['id']}/editar") ?>" 
                                               class="bg-azul-turquesa hover:bg-azul-turquesa/80 text-white px-4 py-2 rounded transition text-sm">
                                                Editar
                                            </a>
                                            <form method="POST" action="<?= base_url("admin/revistas/{$magazine['id']}/deletar") ?>" 
                                                  onsubmit="return confirm('Tem certeza que deseja deletar esta revista?');" class="inline">
                                                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition text-sm">
                                                    Deletar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                <div class="mt-6 flex justify-center gap-2">
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <a href="<?= base_url('admin/revistas?page=' . $i . ($search ? '&search=' . urlencode($search) : '')) ?>" 
                           class="px-4 py-2 rounded <?= $i === $pagination['current_page'] ? 'bg-dourado-luz text-azul-noite' : 'bg-azul-cosmico text-white hover:bg-azul-medio' ?> transition">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/views/admin/layout/footer.php'; ?>
