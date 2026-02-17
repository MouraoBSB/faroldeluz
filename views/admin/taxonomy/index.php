<?php
/**
 * View Admin Taxonomias - Index
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-17 01:16:00
 */

$pageTitle = 'Categorias e Tags - Admin';
require_once BASE_PATH . '/views/admin/layout/header.php';
?>

<div class="flex h-screen bg-azul-noite">
    <?php require_once BASE_PATH . '/views/admin/layout/sidebar.php'; ?>
    
    <div class="flex-1 overflow-auto">
        <div class="p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-dourado-luz">Categorias e Tags</h1>
                <a href="<?= base_url('admin/taxonomias/criar') ?>" class="bg-dourado-luz hover:bg-dourado-intenso text-azul-noite px-6 py-3 rounded-lg font-semibold transition">
                    + Nova <?= $type === 'category' ? 'Categoria' : 'Tag' ?>
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
            
            <!-- Abas -->
            <div class="mb-6 border-b border-azul-medio">
                <nav class="flex gap-4">
                    <a href="<?= base_url('admin/taxonomias?type=category') ?>" class="px-6 py-3 font-semibold rounded-t-lg transition <?= $type === 'category' ? 'bg-azul-cosmico text-dourado-luz border-b-2 border-dourado-luz' : 'text-cinza-azulado hover:text-dourado-luz' ?>">
                        Categorias
                    </a>
                    <a href="<?= base_url('admin/taxonomias?type=tag') ?>" class="px-6 py-3 font-semibold rounded-t-lg transition <?= $type === 'tag' ? 'bg-azul-cosmico text-dourado-luz border-b-2 border-dourado-luz' : 'text-cinza-azulado hover:text-dourado-luz' ?>">
                        Tags
                    </a>
                </nav>
            </div>
            
            <!-- Busca -->
            <div class="mb-6">
                <form method="GET" action="<?= base_url('admin/taxonomias') ?>" class="flex gap-4">
                    <input type="hidden" name="type" value="<?= $type ?>">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                           placeholder="Buscar <?= $type === 'category' ? 'categorias' : 'tags' ?>..." 
                           class="flex-1 px-4 py-3 bg-azul-cosmico border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz">
                    <button type="submit" class="bg-azul-turquesa hover:bg-azul-turquesa/80 text-white px-6 py-3 rounded-lg font-semibold transition">
                        Buscar
                    </button>
                </form>
            </div>
            
            <!-- Tabela -->
            <div class="bg-azul-cosmico rounded-lg border border-azul-medio overflow-hidden">
                <table class="w-full">
                    <thead class="bg-azul-noite">
                        <tr>
                            <th class="px-6 py-4 text-left text-dourado-luz font-semibold">Nome</th>
                            <th class="px-6 py-4 text-left text-dourado-luz font-semibold">Slug</th>
                            <th class="px-6 py-4 text-left text-dourado-luz font-semibold">Descrição</th>
                            <th class="px-6 py-4 text-right text-dourado-luz font-semibold">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-azul-medio">
                        <?php if (empty($terms)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-cinza-azulado">
                                    Nenhuma <?= $type === 'category' ? 'categoria' : 'tag' ?> encontrada
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($terms as $term): ?>
                                <tr class="hover:bg-azul-noite/50 transition">
                                    <td class="px-6 py-4 text-white font-semibold"><?= htmlspecialchars($term['name']) ?></td>
                                    <td class="px-6 py-4 text-cinza-azulado"><?= htmlspecialchars($term['slug']) ?></td>
                                    <td class="px-6 py-4 text-cinza-azulado"><?= htmlspecialchars(substr($term['description'] ?? '', 0, 50)) ?><?= strlen($term['description'] ?? '') > 50 ? '...' : '' ?></td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2 justify-end">
                                            <a href="<?= base_url("admin/taxonomias/{$term['id']}/editar") ?>" 
                                               class="bg-azul-turquesa hover:bg-azul-turquesa/80 text-white px-4 py-2 rounded transition text-sm">
                                                Editar
                                            </a>
                                            <form method="POST" action="<?= base_url("admin/taxonomias/{$term['id']}/deletar") ?>" 
                                                  onsubmit="return confirm('Tem certeza que deseja deletar?');" class="inline">
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
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/views/admin/layout/footer.php'; ?>
