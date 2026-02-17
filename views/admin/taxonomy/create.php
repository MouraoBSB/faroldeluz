<?php
/**
 * View Admin Taxonomias - Create
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-17 01:16:00
 */

$pageTitle = 'Nova Categoria/Tag - Admin';
require_once BASE_PATH . '/views/admin/layout/header.php';
?>

<div class="flex h-screen bg-azul-noite">
    <?php require_once BASE_PATH . '/views/admin/layout/sidebar.php'; ?>
    
    <div class="flex-1 overflow-auto">
        <div class="p-8">
            <div class="mb-8">
                <a href="<?= base_url('admin/taxonomias') ?>" class="text-azul-turquesa hover:text-dourado-luz transition">
                    ← Voltar
                </a>
                <h1 class="text-3xl font-bold text-dourado-luz mt-4">Nova Categoria/Tag</h1>
            </div>
            
            <form method="POST" action="<?= base_url('admin/taxonomias/criar') ?>" class="max-w-2xl">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                
                <div class="bg-azul-cosmico rounded-lg border border-azul-medio p-8 space-y-6">
                    <div>
                        <label class="block text-dourado-luz font-semibold mb-2">Tipo *</label>
                        <select name="type" required class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz">
                            <option value="category">Categoria</option>
                            <option value="tag">Tag</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-dourado-luz font-semibold mb-2">Nome *</label>
                        <input type="text" name="name" required 
                               class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                               placeholder="Ex: Espiritismo">
                    </div>
                    
                    <div>
                        <label class="block text-dourado-luz font-semibold mb-2">Descrição</label>
                        <textarea name="description" rows="4" 
                                  class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                  placeholder="Descrição opcional"></textarea>
                    </div>
                    
                    <div class="flex gap-4">
                        <button type="submit" class="bg-dourado-luz hover:bg-dourado-intenso text-azul-noite px-8 py-3 rounded-lg font-semibold transition">
                            Criar
                        </button>
                        <a href="<?= base_url('admin/taxonomias') ?>" class="bg-azul-medio hover:bg-azul-medio/80 text-white px-8 py-3 rounded-lg font-semibold transition">
                            Cancelar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/views/admin/layout/footer.php'; ?>
