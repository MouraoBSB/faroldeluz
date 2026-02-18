<?php
$pageTitle = 'Novo Post - Admin';
require_once BASE_PATH . '/views/admin/layout/header.php';
?>

<div class="flex h-screen bg-azul-noite">
    <?php require_once BASE_PATH . '/views/admin/layout/sidebar.php'; ?>
    
    <div class="flex-1 overflow-auto">
        <div class="p-8">
            <div class="mb-8">
                <a href="<?= base_url('admin/blog') ?>" class="text-azul-turquesa hover:text-dourado-luz transition">
                    ← Voltar para Blog
                </a>
                <h1 class="text-3xl font-bold text-dourado-luz mt-4">Novo Post</h1>
            </div>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-500/20 border border-red-500 text-red-300 px-6 py-4 rounded-lg mb-6">
                    <?= $_SESSION['error'] ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="<?= base_url('admin/blog/criar') ?>" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="content_html" id="content_input">
                
                <div class="bg-azul-cosmico rounded-lg border border-azul-medio p-8">
                    <h2 class="text-2xl font-bold text-dourado-luz mb-6">Informações Básicas</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Título *</label>
                            <input type="text" name="title" required
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                   placeholder="Ex: A Importância da Caridade no Espiritismo">
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Excerpt (Resumo) *</label>
                            <textarea name="excerpt" rows="3" required
                                      class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                      placeholder="Breve resumo do post (máx 300 caracteres)" maxlength="300"></textarea>
                            <p class="text-sm text-cinza-azulado mt-2">Este texto aparece nos cards de listagem</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Imagem Destacada *</label>
                            <input type="file" name="featured_image" accept="image/*" required
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-dourado-luz file:text-azul-noite file:font-semibold hover:file:bg-dourado-intenso">
                            <p class="text-sm text-cinza-azulado mt-2">Imagem principal do post (recomendado: 1200x630px)</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Conteúdo (Editor Visual Avançado) *</label>
                            <p class="text-sm text-cinza-azulado mb-3">
                                💡 <strong>Dica:</strong> Use o editor para inserir imagens, vídeos, tabelas e formatação rica diretamente no texto!
                            </p>
                            <textarea id="editor"></textarea>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-dourado-luz font-semibold mb-2">Data de Publicação</label>
                                <input type="datetime-local" name="published_at" value="<?= date('Y-m-d\TH:i') ?>"
                                       class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz">
                            </div>
                            
                            <div>
                                <label class="block text-dourado-luz font-semibold mb-2">Status</label>
                                <select name="status"
                                        class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz">
                                    <option value="draft">Rascunho</option>
                                    <option value="published">Publicado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-azul-cosmico rounded-lg border border-azul-medio p-8">
                    <h2 class="text-2xl font-bold text-dourado-luz mb-6">Categorias e Tags</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Categorias</label>
                            <div class="grid grid-cols-2 gap-3">
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <label class="flex items-center gap-2 text-white cursor-pointer hover:text-dourado-luz transition">
                                            <input type="checkbox" name="categories[]" value="<?= $category['id'] ?>" class="w-4 h-4 rounded border-azul-medio bg-azul-noite text-dourado-luz focus:ring-dourado-luz">
                                            <?= htmlspecialchars($category['name']) ?>
                                        </label>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-cinza-azulado col-span-2">Nenhuma categoria cadastrada. <a href="<?= base_url('admin/taxonomias?type=category') ?>" class="text-azul-turquesa hover:text-dourado-luz">Criar categoria</a></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Tags</label>
                            <div class="grid grid-cols-2 gap-3">
                                <?php if (!empty($tags)): ?>
                                    <?php foreach ($tags as $tag): ?>
                                        <label class="flex items-center gap-2 text-white cursor-pointer hover:text-dourado-luz transition">
                                            <input type="checkbox" name="tags[]" value="<?= $tag['id'] ?>" class="w-4 h-4 rounded border-azul-medio bg-azul-noite text-dourado-luz focus:ring-dourado-luz">
                                            <?= htmlspecialchars($tag['name']) ?>
                                        </label>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-cinza-azulado col-span-2">Nenhuma tag cadastrada. <a href="<?= base_url('admin/taxonomias?type=tag') ?>" class="text-azul-turquesa hover:text-dourado-luz">Criar tag</a></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-azul-cosmico rounded-lg border border-azul-medio p-8">
                    <h2 class="text-2xl font-bold text-dourado-luz mb-6">SEO e Otimização</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Título SEO</label>
                            <input type="text" name="seo_title"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                   placeholder="Deixe vazio para usar o título principal">
                            <p class="text-sm text-cinza-azulado mt-2">Título otimizado para motores de busca (máx 60 caracteres)</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Meta Descrição</label>
                            <textarea name="seo_meta_description" rows="3"
                                      class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                      placeholder="Descrição para motores de busca (máx 160 caracteres)" maxlength="160"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Palavras-chave</label>
                            <input type="text" name="seo_keywords"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                   placeholder="espiritismo, caridade, evangelho, kardec">
                            <p class="text-sm text-cinza-azulado mt-2">Separe as palavras-chave por vírgula</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-4">
                    <button type="submit" class="bg-dourado-luz hover:bg-dourado-intenso text-azul-noite px-8 py-3 rounded-lg font-semibold transition">
                        Criar Post
                    </button>
                    <a href="<?= base_url('admin/blog') ?>" class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-lg font-semibold transition inline-block">
                        ✕ Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '#editor',
    skin: 'oxide-dark',
    content_css: 'dark',
    height: 600,
    menubar: 'file edit view insert format tools table help',
    language: 'pt_BR',
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons'
    ],
    toolbar: 'undo redo | blocks | bold italic underline strikethrough | ' +
             'alignleft aligncenter alignright alignjustify | ' +
             'bullist numlist outdent indent | forecolor backcolor | ' +
             'link image media table emoticons | ' +
             'layout2cols layout3cols | ' +
             'removeformat code fullscreen | help',
    content_style: 'body { font-family: Arial, sans-serif; font-size: 16px; color: #fff; background-color: #0B1020; line-height: 1.6; } ' +
                   'img { max-width: 100%; height: auto; } ' +
                   '.row-2cols { display: flex; gap: 20px; margin: 20px 0; flex-wrap: wrap; } ' +
                   '.row-2cols .col { flex: 1; min-width: 250px; } ' +
                   '.row-3cols { display: flex; gap: 15px; margin: 20px 0; flex-wrap: wrap; } ' +
                   '.row-3cols .col { flex: 1; min-width: 200px; } ' +
                   '.col img { max-width: 100%; height: auto; display: block; }',
    
    image_advtab: true,
    image_caption: true,
    image_title: true,
    automatic_uploads: true,
    file_picker_types: 'image',
    
    setup: function(editor) {
        editor.ui.registry.addButton('layout2cols', {
            text: '2 Colunas',
            tooltip: 'Inserir layout de 2 colunas',
            onAction: function() {
                editor.insertContent(
                    '<div class="row-2cols">' +
                    '<div class="col"><p>Coluna 1 - Clique aqui para adicionar imagem ou texto</p></div>' +
                    '<div class="col"><p>Coluna 2 - Clique aqui para adicionar texto</p></div>' +
                    '</div><p>&nbsp;</p>'
                );
            }
        });
        
        editor.ui.registry.addButton('layout3cols', {
            text: '3 Colunas',
            tooltip: 'Inserir layout de 3 colunas',
            onAction: function() {
                editor.insertContent(
                    '<div class="row-3cols">' +
                    '<div class="col"><p>Coluna 1</p></div>' +
                    '<div class="col"><p>Coluna 2</p></div>' +
                    '<div class="col"><p>Coluna 3</p></div>' +
                    '</div><p>&nbsp;</p>'
                );
            }
        });
    },
    
    images_upload_handler: function (blobInfo, progress) {
        return new Promise(function(resolve, reject) {
            var xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '<?= base_url('admin/upload-image') ?>');
            
            xhr.upload.onprogress = function (e) {
                progress(e.loaded / e.total * 100);
            };
            
            xhr.onload = function() {
                if (xhr.status === 403) {
                    reject('Erro de autenticação. Faça login novamente.');
                    return;
                }
                
                if (xhr.status < 200 || xhr.status >= 300) {
                    reject('Erro HTTP: ' + xhr.status);
                    return;
                }
                
                try {
                    var json = JSON.parse(xhr.responseText);
                    if (!json || typeof json.location != 'string') {
                        reject('Resposta JSON inválida');
                        return;
                    }
                    resolve(json.location);
                } catch (e) {
                    reject('Erro ao processar resposta: ' + e.message);
                }
            };
            
            xhr.onerror = function () {
                reject('Erro de conexão ao fazer upload');
            };
            
            var formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData);
        });
    }
});

document.querySelector('form').addEventListener('submit', function(e) {
    document.getElementById('content_input').value = tinymce.get('editor').getContent();
});
</script>

<?php require_once BASE_PATH . '/views/admin/layout/footer.php'; ?>
