<?php
/**
 * View Admin Newsletter
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-17 02:28:00
 */

$pageTitle = 'Newsletter - Admin';
require_once BASE_PATH . '/views/admin/layout/header.php';
?>

<div class="flex-1 overflow-auto">
    <div class="p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-dourado-luz">Newsletter</h1>
            <a href="<?= base_url('admin/newsletter/export?status=' . ($status ?: 'active')) ?>" 
               class="bg-azul-turquesa hover:bg-opacity-80 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                <span>📥</span> Exportar CSV
            </a>
        </div>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-500/20 border border-green-500 text-green-500 px-4 py-3 rounded-lg mb-6">
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-500/20 border border-red-500 text-red-500 px-4 py-3 rounded-lg mb-6">
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-azul-cosmico rounded-lg border border-azul-medio p-6">
                <div class="text-cinza-azulado mb-2">Total de Inscritos</div>
                <div class="text-3xl font-bold text-dourado-luz"><?= $stats['total'] ?></div>
            </div>
            
            <div class="bg-azul-cosmico rounded-lg border border-azul-medio p-6">
                <div class="text-cinza-azulado mb-2">Ativos</div>
                <div class="text-3xl font-bold text-green-500"><?= $stats['active'] ?></div>
            </div>
            
            <div class="bg-azul-cosmico rounded-lg border border-azul-medio p-6">
                <div class="text-cinza-azulado mb-2">Cancelados</div>
                <div class="text-3xl font-bold text-red-500"><?= $stats['unsubscribed'] ?></div>
            </div>
        </div>
        
        <div class="bg-azul-cosmico rounded-lg border border-azul-medio p-6 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                           placeholder="Buscar por nome ou e-mail..."
                           class="w-full px-4 py-2 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz">
                </div>
                
                <div>
                    <select name="status" 
                            class="w-full px-4 py-2 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz">
                        <option value="">Todos os Status</option>
                        <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Ativos</option>
                        <option value="unsubscribed" <?= $status === 'unsubscribed' ? 'selected' : '' ?>>Cancelados</option>
                    </select>
                </div>
                
                <div>
                    <button type="submit" class="w-full bg-dourado-luz hover:bg-dourado-intenso text-azul-noite px-4 py-2 rounded-lg font-semibold transition">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>
        
        <div class="bg-azul-cosmico rounded-lg border border-azul-medio overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-azul-noite">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-dourado-luz uppercase tracking-wider">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-dourado-luz uppercase tracking-wider">E-mail</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-dourado-luz uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-dourado-luz uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-dourado-luz uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-azul-medio">
                        <?php if (!empty($subscribers)): ?>
                            <?php foreach ($subscribers as $subscriber): ?>
                                <tr class="hover:bg-azul-noite/50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-white">
                                        <?= htmlspecialchars($subscriber['name']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-cinza-azulado">
                                        <?= htmlspecialchars($subscriber['email']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($subscriber['status'] === 'active'): ?>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-500/20 text-green-500">
                                                Ativo
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-500/20 text-red-500">
                                                Cancelado
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-cinza-azulado text-sm">
                                        <?= date('d/m/Y H:i', strtotime($subscriber['created_at'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form method="POST" action="<?= base_url('admin/newsletter/deletar') ?>" 
                                              onsubmit="return confirm('Tem certeza que deseja remover este inscrito?')" 
                                              class="inline">
                                            <input type="hidden" name="id" value="<?= $subscriber['id'] ?>">
                                            <button type="submit" class="text-red-500 hover:text-red-400 transition">
                                                Excluir
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-cinza-azulado">
                                    Nenhum inscrito encontrado
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
            <div class="flex justify-center gap-2 mt-6">
                <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                    <a href="<?= base_url('admin/newsletter?page=' . $i . ($search ? '&search=' . urlencode($search) : '') . ($status ? '&status=' . $status : '')) ?>" 
                       class="px-4 py-2 rounded <?= $i === $pagination['current_page'] ? 'bg-dourado-luz text-azul-noite' : 'bg-azul-cosmico text-white hover:bg-azul-medio' ?> transition">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once BASE_PATH . '/views/admin/layout/footer.php'; ?>
