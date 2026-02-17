<?php
$pageTitle = 'Configurações - Admin';
require_once BASE_PATH . '/views/admin/layout/header.php';
?>


<div class="flex h-screen bg-azul-noite">
    <?php require_once BASE_PATH . '/views/admin/layout/sidebar.php'; ?>
    
    <div class="flex-1 overflow-auto">
        <div class="p-8">
            <h1 class="text-3xl font-bold text-dourado-luz mb-8">Configurações do Site</h1>
            
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
            
            <!-- Abas de Navegação -->
            <div class="mb-8 border-b border-azul-medio">
                <nav class="flex flex-wrap gap-2">
                    <button type="button" onclick="switchTab('revista')" class="tab-button px-6 py-3 font-semibold rounded-t-lg transition" data-tab="revista">
                        Revista
                    </button>
                    <button type="button" onclick="switchTab('dialogos')" class="tab-button px-6 py-3 font-semibold rounded-t-lg transition" data-tab="dialogos">
                        Diálogos do Farol
                    </button>
                    <button type="button" onclick="switchTab('rajian')" class="tab-button px-6 py-3 font-semibold rounded-t-lg transition" data-tab="rajian">
                        Grupo Rajian
                    </button>
                    <button type="button" onclick="switchTab('blog')" class="tab-button px-6 py-3 font-semibold rounded-t-lg transition" data-tab="blog">
                        Blog
                    </button>
                    <button type="button" onclick="switchTab('sobre')" class="tab-button px-6 py-3 font-semibold rounded-t-lg transition" data-tab="sobre">
                        O Projeto
                    </button>
                    <button type="button" onclick="switchTab('batuira')" class="tab-button px-6 py-3 font-semibold rounded-t-lg transition" data-tab="batuira">
                        Guia Batuíra
                    </button>
                    <button type="button" onclick="switchTab('contato')" class="tab-button px-6 py-3 font-semibold rounded-t-lg transition" data-tab="contato">
                        Contato
                    </button>
                </nav>
            </div>
            
            <form method="POST" action="<?= base_url('admin/configuracoes') ?>" enctype="multipart/form-data" class="space-y-8" id="settingsForm">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="revista_descricao" id="revista_descricao_input">
                <input type="hidden" name="revista_texto_adicional" id="revista_texto_adicional_input">
                <input type="hidden" name="dialogos_descricao" id="dialogos_descricao_input">
                <input type="hidden" name="dialogos_texto_adicional" id="dialogos_texto_adicional_input">
                <input type="hidden" name="rajian_descricao" id="rajian_descricao_input">
                <input type="hidden" name="rajian_texto_adicional" id="rajian_texto_adicional_input">
                <input type="hidden" name="blog_descricao" id="blog_descricao_input">
                <input type="hidden" name="blog_texto_adicional" id="blog_texto_adicional_input">
                <input type="hidden" name="sobre_texto" id="sobre_texto_input">
                <input type="hidden" name="batuira_texto" id="batuira_texto_input">
                
                <div class="settings-section bg-azul-cosmico rounded-lg border border-azul-medio p-8" data-section="revista">
                    <h2 class="text-2xl font-bold text-dourado-luz mb-6">Página da Revista</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Título da Página</label>
                            <input type="text" name="revista_titulo" value="<?= htmlspecialchars($settings['revista_titulo'] ?? '') ?>"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                   placeholder="Ex: Revista Espírita Farol de Luz">
                            <p class="text-sm text-cinza-azulado mt-2">Título principal que aparece no topo da página de revistas</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Imagem de Destaque</label>
                            <?php if (!empty($settings['revista_imagem_destaque'])): ?>
                                <div class="mb-3">
                                    <img src="<?= base_url($settings['revista_imagem_destaque']) ?>" 
                                         alt="Imagem de destaque atual" 
                                         class="max-w-xs rounded-lg border border-azul-medio">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="revista_imagem_destaque" accept="image/jpeg,image/jpg,image/png,image/webp"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-dourado-luz file:text-azul-noite file:font-semibold hover:file:bg-dourado-intenso">
                            <p class="text-sm text-cinza-azulado mt-2">Imagem que aparece ao lado do texto introdutório (recomendado: 800x600px)</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Descrição Introdutória (Editor Visual)</label>
                            <textarea id="editor"></textarea>
                            <p class="text-sm text-cinza-azulado mt-2">Use o editor para formatar o texto com negrito, listas, títulos, alinhamentos e muito mais</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Texto Adicional - Rodapé (Editor Visual)</label>
                            <textarea id="editor-rodape"></textarea>
                            <p class="text-sm text-cinza-azulado mt-2">Texto complementar exibido após a listagem de revistas (use formatação HTML)</p>
                        </div>
                    </div>
                </div>
                
                <div class="settings-section bg-azul-cosmico rounded-lg border border-azul-medio p-8" data-section="dialogos">
                    <h2 class="text-2xl font-bold text-dourado-luz mb-6">Página Diálogos do Farol</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Título da Página</label>
                            <input type="text" name="dialogos_titulo" value="<?= htmlspecialchars($settings['dialogos_titulo'] ?? '') ?>"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                   placeholder="Ex: Diálogos do Farol">
                            <p class="text-sm text-cinza-azulado mt-2">Título principal que aparece no topo da página de diálogos</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Imagem de Destaque</label>
                            <?php if (!empty($settings['dialogos_imagem_destaque'])): ?>
                                <div class="mb-3">
                                    <img src="<?= base_url($settings['dialogos_imagem_destaque']) ?>" 
                                         alt="Imagem de destaque atual" 
                                         class="max-w-xs rounded-lg border border-azul-medio">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="dialogos_imagem_destaque" accept="image/jpeg,image/jpg,image/png,image/webp"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-dourado-luz file:text-azul-noite file:font-semibold hover:file:bg-dourado-intenso">
                            <p class="text-sm text-cinza-azulado mt-2">Imagem que aparece ao lado do texto introdutório (recomendado: 800x600px)</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Descrição Introdutória (Editor Visual)</label>
                            <textarea id="editor-dialogos"></textarea>
                            <p class="text-sm text-cinza-azulado mt-2">Use o editor para formatar o texto com negrito, listas, títulos, alinhamentos e muito mais</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Texto Adicional - Rodapé (Editor Visual)</label>
                            <textarea id="editor-dialogos-rodape"></textarea>
                            <p class="text-sm text-cinza-azulado mt-2">Texto complementar exibido após a listagem de diálogos (use formatação HTML)</p>
                        </div>
                    </div>
                </div>
                
                <div class="settings-section bg-azul-cosmico rounded-lg border border-azul-medio p-8" data-section="rajian">
                    <h2 class="text-2xl font-bold text-dourado-luz mb-6">Página Rajian</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Título da Página</label>
                            <input type="text" name="rajian_titulo" value="<?= htmlspecialchars($settings['rajian_titulo'] ?? '') ?>"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                   placeholder="Ex: Grupo de Estudo Rajian">
                            <p class="text-sm text-cinza-azulado mt-2">Título principal que aparece no topo da página Rajian</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Imagem de Destaque</label>
                            <?php if (!empty($settings['rajian_imagem_destaque'])): ?>
                                <div class="mb-3">
                                    <img src="<?= base_url($settings['rajian_imagem_destaque']) ?>" 
                                         alt="Imagem de destaque atual" 
                                         class="max-w-xs rounded-lg border border-azul-medio">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="rajian_imagem_destaque" accept="image/jpeg,image/jpg,image/png,image/webp"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-dourado-luz file:text-azul-noite file:font-semibold hover:file:bg-dourado-intenso">
                            <p class="text-sm text-cinza-azulado mt-2">Imagem que aparece ao lado do texto introdutório (recomendado: 800x600px)</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Descrição Introdutória (Editor Visual)</label>
                            <textarea id="editor-rajian"></textarea>
                            <p class="text-sm text-cinza-azulado mt-2">Use o editor para formatar o texto com negrito, listas, títulos, alinhamentos e muito mais</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Texto Adicional - Rodapé (Editor Visual)</label>
                            <textarea id="editor-rajian-rodape"></textarea>
                            <p class="text-sm text-cinza-azulado mt-2">Texto complementar exibido após a listagem de estudos (use formatação HTML)</p>
                        </div>
                    </div>
                </div>
                
                <div class="settings-section bg-azul-cosmico rounded-lg border border-azul-medio p-8" data-section="blog">
                    <h2 class="text-2xl font-bold text-dourado-luz mb-6">Página Blog</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Título da Página</label>
                            <input type="text" name="blog_titulo" value="<?= htmlspecialchars($settings['blog_titulo'] ?? '') ?>"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                   placeholder="Ex: Blog Farol de Luz">
                            <p class="text-sm text-cinza-azulado mt-2">Título principal que aparece no topo da página do blog</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Imagem de Destaque</label>
                            <?php if (!empty($settings['blog_imagem_destaque'])): ?>
                                <div class="mb-3">
                                    <img src="<?= base_url($settings['blog_imagem_destaque']) ?>" 
                                         alt="Imagem de destaque atual" 
                                         class="max-w-xs rounded-lg border border-azul-medio">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="blog_imagem_destaque" accept="image/jpeg,image/jpg,image/png,image/webp"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-dourado-luz file:text-azul-noite file:font-semibold hover:file:bg-dourado-intenso">
                            <p class="text-sm text-cinza-azulado mt-2">Imagem que aparece ao lado do texto introdutório (recomendado: 800x600px)</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Descrição Introdutória (Editor Visual)</label>
                            <textarea id="editor-blog"></textarea>
                            <p class="text-sm text-cinza-azulado mt-2">Use o editor para formatar o texto com negrito, listas, títulos, alinhamentos e muito mais</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Texto Adicional - Rodapé (Editor Visual)</label>
                            <textarea id="editor-blog-rodape"></textarea>
                            <p class="text-sm text-cinza-azulado mt-2">Texto complementar exibido após a listagem de posts (use formatação HTML)</p>
                        </div>
                    </div>
                </div>
                
                <div class="settings-section bg-azul-cosmico rounded-lg border border-azul-medio p-8" data-section="sobre">
                    <h2 class="text-2xl font-bold text-dourado-luz mb-6">Página Sobre (O Projeto)</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Título da Página</label>
                            <input type="text" name="sobre_titulo" value="<?= htmlspecialchars($settings['sobre_titulo'] ?? '') ?>"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                   placeholder="Ex: O Projeto Farol de Luz">
                            <p class="text-sm text-cinza-azulado mt-2">Título H1 que aparece no topo da página</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Imagem Principal</label>
                            <?php if (!empty($settings['sobre_imagem'])): ?>
                                <div class="mb-3">
                                    <img src="<?= base_url($settings['sobre_imagem']) ?>" 
                                         alt="Imagem atual" 
                                         class="max-w-xs rounded-lg border border-azul-medio">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="sobre_imagem" accept="image/jpeg,image/jpg,image/png,image/webp"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-dourado-luz file:text-azul-noite file:font-semibold hover:file:bg-dourado-intenso">
                            <p class="text-sm text-cinza-azulado mt-2">Imagem que aparece abaixo do título (recomendado: 1200x600px)</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Texto da Página (Editor Visual)</label>
                            <textarea id="editor-sobre"></textarea>
                            <p class="text-sm text-cinza-azulado mt-2">Conteúdo principal da página com formatação HTML</p>
                        </div>
                    </div>
                </div>
                
                <div class="settings-section bg-azul-cosmico rounded-lg border border-azul-medio p-8" data-section="batuira">
                    <h2 class="text-2xl font-bold text-dourado-luz mb-6">Página Guia Batuíra</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Título da Página</label>
                            <input type="text" name="batuira_titulo" value="<?= htmlspecialchars($settings['batuira_titulo'] ?? '') ?>"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                   placeholder="Ex: Guia Batuíra">
                            <p class="text-sm text-cinza-azulado mt-2">Título H1 que aparece no topo da página</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Imagem (ao lado do texto)</label>
                            <?php if (!empty($settings['batuira_imagem'])): ?>
                                <div class="mb-3">
                                    <img src="<?= base_url($settings['batuira_imagem']) ?>" 
                                         alt="Imagem atual" 
                                         class="max-w-xs rounded-lg border border-azul-medio">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="batuira_imagem" accept="image/jpeg,image/jpg,image/png,image/webp"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-dourado-luz file:text-azul-noite file:font-semibold hover:file:bg-dourado-intenso">
                            <p class="text-sm text-cinza-azulado mt-2">Imagem que aparece ao lado do texto (recomendado: 600x800px)</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Texto da Página (Editor Visual)</label>
                            <textarea id="editor-batuira"></textarea>
                            <p class="text-sm text-cinza-azulado mt-2">Conteúdo principal da página com formatação HTML</p>
                        </div>
                    </div>
                </div>
                
                <div class="settings-section bg-azul-cosmico rounded-lg border border-azul-medio p-8" data-section="contato">
                    <h2 class="text-2xl font-bold text-dourado-luz mb-6">WhatsApp</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Link de Contato (WhatsApp)</label>
                            <input type="url" name="whatsapp_url" value="<?= htmlspecialchars($settings['whatsapp_url'] ?? '') ?>"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                   placeholder="https://wa.me/5511999999999">
                            <p class="text-sm text-cinza-azulado mt-2">Link direto para conversa no WhatsApp</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Link do Grupo WhatsApp</label>
                            <input type="url" name="whatsapp_group_url" value="<?= htmlspecialchars($settings['whatsapp_group_url'] ?? '') ?>"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                   placeholder="https://chat.whatsapp.com/...">
                            <p class="text-sm text-cinza-azulado mt-2">Link de convite para o grupo do WhatsApp</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Link do Canal WhatsApp</label>
                            <input type="url" name="whatsapp_channel_url" value="<?= htmlspecialchars($settings['whatsapp_channel_url'] ?? '') ?>"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                   placeholder="https://whatsapp.com/channel/...">
                            <p class="text-sm text-cinza-azulado mt-2">Link do canal do WhatsApp</p>
                        </div>
                    </div>
                </div>
                
                <div class="settings-section bg-azul-cosmico rounded-lg border border-azul-medio p-8" data-section="contato">
                    <h2 class="text-2xl font-bold text-dourado-luz mb-6">E-mail e Redes Sociais</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">E-mail de Contato</label>
                            <input type="email" name="contact_email" value="<?= htmlspecialchars($settings['contact_email'] ?? '') ?>"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                   placeholder="contato@faroldeluz.com.br">
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Facebook</label>
                            <input type="url" name="facebook_url" value="<?= htmlspecialchars($settings['facebook_url'] ?? '') ?>"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                   placeholder="https://facebook.com/faroldeluz">
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Instagram</label>
                            <input type="url" name="instagram_url" value="<?= htmlspecialchars($settings['instagram_url'] ?? '') ?>"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                   placeholder="https://instagram.com/faroldeluz">
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">YouTube</label>
                            <input type="url" name="youtube_url" value="<?= htmlspecialchars($settings['youtube_url'] ?? '') ?>"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                   placeholder="https://youtube.com/@faroldeluz">
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-4">
                    <button type="submit" class="bg-dourado-luz hover:bg-dourado-intenso text-azul-noite px-8 py-3 rounded-lg font-semibold transition">
                        Salvar Configurações
                    </button>
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
            var content = <?= json_encode($settings['revista_descricao'] ?? '') ?>;
            if (content) {
                editor.setContent(content);
            }
        });
    }
});

tinymce.init({
    selector: '#editor-rodape',
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
            var content = <?= json_encode($settings['revista_texto_adicional'] ?? '') ?>;
            if (content) {
                editor.setContent(content);
            }
        });
    }
});

tinymce.init({
    selector: '#editor-dialogos',
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
            var content = <?= json_encode($settings['dialogos_descricao'] ?? '') ?>;
            if (content) {
                editor.setContent(content);
            }
        });
    }
});

tinymce.init({
    selector: '#editor-dialogos-rodape',
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
            var content = <?= json_encode($settings['dialogos_texto_adicional'] ?? '') ?>;
            if (content) {
                editor.setContent(content);
            }
        });
    }
});

tinymce.init({
    selector: '#editor-rajian',
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
            var content = <?= json_encode($settings['rajian_descricao'] ?? '') ?>;
            if (content) {
                editor.setContent(content);
            }
        });
    }
});

tinymce.init({
    selector: '#editor-rajian-rodape',
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
            var content = <?= json_encode($settings['rajian_texto_adicional'] ?? '') ?>;
            if (content) {
                editor.setContent(content);
            }
        });
    }
});

tinymce.init({
    selector: '#editor-blog',
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
            var content = <?= json_encode($settings['blog_descricao'] ?? '') ?>;
            if (content) {
                editor.setContent(content);
            }
        });
    }
});

tinymce.init({
    selector: '#editor-blog-rodape',
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
            var content = <?= json_encode($settings['blog_texto_adicional'] ?? '') ?>;
            if (content) {
                editor.setContent(content);
            }
        });
    }
});

tinymce.init({
    selector: '#editor-sobre',
    skin: 'oxide-dark',
    content_css: 'dark',
    height: 500,
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
            var content = <?= json_encode($settings['sobre_texto'] ?? '') ?>;
            if (content) {
                editor.setContent(content);
            }
        });
    }
});

tinymce.init({
    selector: '#editor-batuira',
    skin: 'oxide-dark',
    content_css: 'dark',
    height: 500,
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
            var content = <?= json_encode($settings['batuira_texto'] ?? '') ?>;
            if (content) {
                editor.setContent(content);
            }
        });
    }
});

document.getElementById('settingsForm').addEventListener('submit', function(e) {
    document.getElementById('revista_descricao_input').value = tinymce.get('editor').getContent();
    document.getElementById('revista_texto_adicional_input').value = tinymce.get('editor-rodape').getContent();
    document.getElementById('dialogos_descricao_input').value = tinymce.get('editor-dialogos').getContent();
    document.getElementById('dialogos_texto_adicional_input').value = tinymce.get('editor-dialogos-rodape').getContent();
    document.getElementById('rajian_descricao_input').value = tinymce.get('editor-rajian').getContent();
    document.getElementById('rajian_texto_adicional_input').value = tinymce.get('editor-rajian-rodape').getContent();
    document.getElementById('blog_descricao_input').value = tinymce.get('editor-blog').getContent();
    document.getElementById('blog_texto_adicional_input').value = tinymce.get('editor-blog-rodape').getContent();
    document.getElementById('sobre_texto_input').value = tinymce.get('editor-sobre').getContent();
    document.getElementById('batuira_texto_input').value = tinymce.get('editor-batuira').getContent();
});

// Sistema de Abas
function switchTab(tabName) {
    // Esconder todas as seções
    document.querySelectorAll('.settings-section').forEach(section => {
        section.style.display = 'none';
    });
    
    // Mostrar seções da aba selecionada
    document.querySelectorAll(`[data-section="${tabName}"]`).forEach(section => {
        section.style.display = 'block';
    });
    
    // Atualizar estilo dos botões
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('bg-dourado-luz', 'text-azul-noite');
        button.classList.add('text-cinza-azulado', 'hover:text-dourado-luz');
    });
    
    // Destacar botão ativo
    const activeButton = document.querySelector(`[data-tab="${tabName}"]`);
    if (activeButton) {
        activeButton.classList.remove('text-cinza-azulado', 'hover:text-dourado-luz');
        activeButton.classList.add('bg-dourado-luz', 'text-azul-noite');
    }
    
    // Salvar aba ativa no localStorage
    localStorage.setItem('activeSettingsTab', tabName);
}

// Restaurar última aba ativa ou mostrar primeira
document.addEventListener('DOMContentLoaded', function() {
    const savedTab = localStorage.getItem('activeSettingsTab') || 'revista';
    switchTab(savedTab);
});
</script>

<?php require_once BASE_PATH . '/views/admin/layout/footer.php'; ?>
