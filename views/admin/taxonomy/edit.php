<?php
/**
 * View Admin Taxonomias - Edit
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-17 01:16:00
 */

$pageTitle = 'Editar ' . ucfirst($term['taxonomy_type']) . ' - Admin';
require_once BASE_PATH . '/views/admin/layout/header.php';
?>

<div class="flex h-screen bg-azul-noite">
    <?php require_once BASE_PATH . '/views/admin/layout/sidebar.php'; ?>
    
    <div class="flex-1 overflow-auto">
        <div class="p-8">
            <div class="mb-8">
                <a href="<?= base_url('admin/taxonomias?type=' . $term['taxonomy_type']) ?>" class="text-azul-turquesa hover:text-dourado-luz transition">
                    ← Voltar
                </a>
                <h1 class="text-3xl font-bold text-dourado-luz mt-4">Editar <?= ucfirst($term['taxonomy_type']) ?></h1>
            </div>
            
            <form method="POST" action="<?= base_url("admin/taxonomias/{$term['id']}/editar") ?>" class="max-w-2xl">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                
                <div class="bg-azul-cosmico rounded-lg border border-azul-medio p-8 space-y-6">
                    <div>
                        <label class="block text-dourado-luz font-semibold mb-2">Tipo</label>
                        <input type="text" value="<?= ucfirst($term['taxonomy_type']) ?>" disabled 
                               class="w-full px-4 py-3 bg-azul-noite/50 border border-azul-medio rounded-lg text-cinza-azulado">
                    </div>
                    
                    <div>
                        <label class="block text-dourado-luz font-semibold mb-2">Nome *</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($term['name']) ?>" required 
                               class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz">
                    </div>
                    
                    <div>
                        <label class="block text-dourado-luz font-semibold mb-2">Slug</label>
                        <input type="text" value="<?= htmlspecialchars($term['slug']) ?>" disabled 
                               class="w-full px-4 py-3 bg-azul-noite/50 border border-azul-medio rounded-lg text-cinza-azulado">
                        <p class="text-sm text-cinza-azulado mt-2">O slug será gerado automaticamente a partir do nome</p>
                    </div>
                    
                    <div>
                        <label class="block text-dourado-luz font-semibold mb-2">Descrição</label>
                        <textarea name="description" rows="4" 
                                  class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"><?= htmlspecialchars($term['description'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="flex gap-4">
                        <button type="submit" class="bg-dourado-luz hover:bg-dourado-intenso text-azul-noite px-8 py-3 rounded-lg font-semibold transition">
                            Salvar
                        </button>
                        <a href="<?= base_url('admin/taxonomias?type=' . $term['taxonomy_type']) ?>" class="bg-azul-medio hover:bg-azul-medio/80 text-white px-8 py-3 rounded-lg font-semibold transition">
                            Cancelar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/views/admin/layout/footer.php'; ?>
