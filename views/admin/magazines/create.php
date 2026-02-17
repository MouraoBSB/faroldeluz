<?php
$pageTitle = 'Nova Revista - Admin';
require_once BASE_PATH . '/views/admin/layout/header.php';
?>

<div class="flex h-screen bg-azul-noite">
    <?php require_once BASE_PATH . '/views/admin/layout/sidebar.php'; ?>
    
    <div class="flex-1 overflow-auto">
        <div class="p-8">
            <div class="mb-8">
                <a href="<?= base_url('admin/revistas') ?>" class="text-azul-turquesa hover:text-dourado-luz transition">
                    ← Voltar para Revistas
                </a>
                <h1 class="text-3xl font-bold text-dourado-luz mt-4">Nova Revista</h1>
            </div>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-500/20 border border-red-500 text-red-300 px-6 py-4 rounded-lg mb-6">
                    <?= $_SESSION['error'] ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="<?= base_url('admin/revistas/criar') ?>" enctype="multipart/form-data" class="bg-azul-cosmico rounded-lg border border-azul-medio p-8">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="md:col-span-2">
                        <label class="block text-dourado-luz font-semibold mb-2">Título *</label>
                        <input type="text" name="title" required 
                               class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                               placeholder="Digite o título da revista">
                    </div>
                    
                    <div>
                        <label class="block text-dourado-luz font-semibold mb-2">Imagem de Capa</label>
                        <input type="file" name="cover_image" accept="image/jpeg,image/jpg,image/png,image/webp"
                               class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz">
                        <p class="text-sm text-cinza-azulado mt-2">Formatos: JPG, PNG, WEBP (máx 10MB)</p>
                    </div>
                    
                    <div>
                        <label class="block text-dourado-luz font-semibold mb-2">Link do PDF (Google Drive)</label>
                        <input type="url" name="pdf_url"
                               class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                               placeholder="https://drive.google.com/...">
                        <p class="text-sm text-cinza-azulado mt-2">Cole o link compartilhável do Google Drive</p>
                    </div>
                    
                    <div>
                        <label class="block text-dourado-luz font-semibold mb-2">Data de Publicação *</label>
                        <input type="date" name="published_date" value="<?= date('Y-m-d') ?>" required
                               class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz">
                    </div>
                    
                    <div>
                        <label class="block text-dourado-luz font-semibold mb-2">Status *</label>
                        <select name="status" required
                                class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz">
                            <option value="draft">Rascunho</option>
                            <option value="published">Publicado</option>
                        </select>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-dourado-luz font-semibold mb-2">Descrição</label>
                        <textarea name="description" rows="6"
                                  class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                  placeholder="Digite a descrição da revista (aceita HTML)"></textarea>
                    </div>
                </div>
                
                <div class="border-t border-azul-medio pt-6 mb-6">
                    <h3 class="text-xl font-bold text-dourado-luz mb-4">SEO</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Meta Título</label>
                            <input type="text" name="meta_title" maxlength="60"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                   placeholder="Título para SEO (deixe vazio para usar o título da revista)">
                            <p class="text-sm text-cinza-azulado mt-2">Máximo 60 caracteres</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Meta Descrição</label>
                            <textarea name="meta_description" rows="3" maxlength="160"
                                      class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                      placeholder="Descrição para SEO"></textarea>
                            <p class="text-sm text-cinza-azulado mt-2">Máximo 160 caracteres</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-4">
                    <button type="submit" class="bg-dourado-luz hover:bg-dourado-intenso text-azul-noite px-8 py-3 rounded-lg font-semibold transition">
                        Criar Revista
                    </button>
                    <a href="<?= base_url('admin/revistas') ?>" class="bg-azul-medio hover:bg-azul-medio/80 text-white px-8 py-3 rounded-lg font-semibold transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/views/admin/layout/footer.php'; ?>
