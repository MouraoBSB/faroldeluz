<?php
$pageTitle = 'Editar Diálogo - Admin';
require_once BASE_PATH . '/views/admin/layout/header.php';
?>

<div class="flex h-screen bg-azul-noite">
    <?php require_once BASE_PATH . '/views/admin/layout/sidebar.php'; ?>
    
    <div class="flex-1 overflow-auto">
        <div class="p-8">
            <div class="mb-8">
                <a href="<?= base_url('admin/dialogos') ?>" class="text-azul-turquesa hover:text-dourado-luz transition">
                    ← Voltar para Diálogos
                </a>
                <h1 class="text-3xl font-bold text-dourado-luz mt-4">Editar Diálogo</h1>
            </div>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-500/20 border border-red-500 text-red-300 px-6 py-4 rounded-lg mb-6">
                    <?= $_SESSION['error'] ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="<?= base_url("admin/dialogos/editar/{$dialogo['id']}") ?>" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="description" id="description_input">
                
                <div class="bg-azul-cosmico rounded-lg border border-azul-medio p-8">
                    <h2 class="text-2xl font-bold text-dourado-luz mb-6">Informações Básicas</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Título *</label>
                            <input type="text" name="title" value="<?= htmlspecialchars($dialogo['title']) ?>" required
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz">
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">URL do YouTube *</label>
                            <input type="url" name="youtube_url" value="<?= htmlspecialchars($dialogo['youtube_url']) ?>" required
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz">
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Descrição (Editor Visual)</label>
                            <textarea id="editor"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Imagem de Capa</label>
                            <?php if ($dialogo['cover_image_url']): ?>
                                <div class="mb-3">
                                    <img src="<?= base_url($dialogo['cover_image_url']) ?>" alt="Capa atual" class="max-w-xs rounded-lg border border-azul-medio">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="cover_image" accept="image/*"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-dourado-luz file:text-azul-noite file:font-semibold hover:file:bg-dourado-intenso">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-dourado-luz font-semibold mb-2">Data de Publicação</label>
                                <input type="date" name="published_date" value="<?= date('Y-m-d', strtotime($dialogo['published_at'])) ?>"
                                       class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz">
                            </div>
                            
                            <div>
                                <label class="block text-dourado-luz font-semibold mb-2">Status</label>
                                <select name="status"
                                        class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz">
                                    <option value="draft" <?= $dialogo['status'] === 'draft' ? 'selected' : '' ?>>Rascunho</option>
                                    <option value="published" <?= $dialogo['status'] === 'published' ? 'selected' : '' ?>>Publicado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-azul-cosmico rounded-lg border border-azul-medio p-8">
                    <h2 class="text-2xl font-bold text-dourado-luz mb-6">SEO</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Título SEO</label>
                            <input type="text" name="meta_title" value="<?= htmlspecialchars($dialogo['seo_title']) ?>"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz">
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Meta Descrição</label>
                            <textarea name="meta_description" rows="3"
                                      class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"><?= htmlspecialchars($dialogo['seo_meta_description']) ?></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-4">
                    <button type="submit" class="bg-dourado-luz hover:bg-dourado-intenso text-azul-noite px-8 py-3 rounded-lg font-semibold transition">
                        Salvar Alterações
                    </button>
                    <a href="<?= base_url('admin/dialogos') ?>" class="bg-azul-medio hover:bg-azul-medio/80 text-white px-8 py-3 rounded-lg font-semibold transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.tiny.cloud/1/2j407pazahvrhykfnuua7altaxeztkz9v6x5sx1zp2ocll7a/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#editor',
    skin: 'oxide-dark',
    content_css: 'dark',
    height: 400,
    menubar: false,
    language: 'pt_BR',
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'charmap', 'preview',
        'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | bold italic underline strikethrough | ' +
             'alignleft aligncenter alignright alignjustify | ' +
             'bullist numlist outdent indent | forecolor backcolor | ' +
             'link | removeformat | code | help',
    content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; color: #fff; background-color: #0B1020; }',
    setup: function(editor) {
        editor.on('init', function() {
            var content = <?= json_encode($dialogo['description_html'] ?? '') ?>;
            if (content) {
                editor.setContent(content);
            }
        });
    }
});

document.querySelector('form').addEventListener('submit', function(e) {
    document.getElementById('description_input').value = tinymce.get('editor').getContent();
});
</script>

<?php require_once BASE_PATH . '/views/admin/layout/footer.php'; ?>
