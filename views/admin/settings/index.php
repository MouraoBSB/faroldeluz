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
                    <button type="button" onclick="switchTab('geral')" class="tab-button px-6 py-3 font-semibold rounded-t-lg transition" data-tab="geral">
                        Configurações Gerais
                    </button>
                    <button type="button" onclick="switchTab('email')" class="tab-button px-6 py-3 font-semibold rounded-t-lg transition" data-tab="email">
                        📧 Email/SMTP
                    </button>
                    <button type="button" onclick="switchTab('backup')" class="tab-button px-6 py-3 font-semibold rounded-t-lg transition" data-tab="backup">
                        🔐 Backup
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
                <input type="hidden" name="site_meta_description" id="site_meta_description_input">
                
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
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Link do Grupo WhatsApp</label>
                            <input type="url" name="rajian_whatsapp_group_url" value="<?= htmlspecialchars($settings['rajian_whatsapp_group_url'] ?? '') ?>"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                   placeholder="https://chat.whatsapp.com/...">
                            <p class="text-sm text-cinza-azulado mt-2">Link do grupo de WhatsApp para estudo Rajian. Aparecerá como botão verde na página</p>
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
                
                <!-- Seção: Configurações Gerais -->
                <div class="settings-section bg-azul-cosmico rounded-lg border border-azul-medio p-8" data-section="geral">
                    <h2 class="text-2xl font-bold text-dourado-luz mb-6">Configurações Gerais do Site</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Título do Site (OG Title)</label>
                            <input type="text" name="site_og_title" value="<?= htmlspecialchars($settings['site_og_title'] ?? '') ?>"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                   placeholder="Ex: Farol de Luz - A Luz do Consolador para os dias de hoje!">
                            <p class="text-sm text-cinza-azulado mt-2">Título que aparece quando o link do site é compartilhado em redes sociais</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Descrição do Site (Meta Description)</label>
                            <textarea id="editor-meta-description" rows="4"></textarea>
                            <p class="text-sm text-cinza-azulado mt-2">Descrição que aparece quando o link do site é compartilhado (WhatsApp, Facebook, etc). Recomendado: 150-160 caracteres</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Favicon (Ícone do Site)</label>
                            <?php if (!empty($settings['site_favicon'])): ?>
                                <div class="mb-3 flex items-center gap-4">
                                    <img src="<?= base_url($settings['site_favicon']) ?>" 
                                         alt="Favicon atual" 
                                         class="w-16 h-16 rounded border border-azul-medio">
                                    <span class="text-cinza-azulado">Favicon atual</span>
                                </div>
                            <?php endif; ?>
                            <input type="file" name="site_favicon" accept="image/x-icon,image/png,image/svg+xml"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-dourado-luz file:text-azul-noite file:font-semibold hover:file:bg-dourado-intenso">
                            <p class="text-sm text-cinza-azulado mt-2">Ícone que aparece na aba do navegador. Formatos aceitos: .ico, .png, .svg (recomendado: 32x32px ou 64x64px)</p>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Imagem de Compartilhamento (OG Image)</label>
                            <?php if (!empty($settings['site_og_image'])): ?>
                                <div class="mb-3">
                                    <img src="<?= base_url($settings['site_og_image']) ?>" 
                                         alt="Imagem de compartilhamento atual" 
                                         class="max-w-md rounded-lg border border-azul-medio">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="site_og_image" accept="image/jpeg,image/jpg,image/png,image/webp"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-dourado-luz file:text-azul-noite file:font-semibold hover:file:bg-dourado-intenso">
                            <p class="text-sm text-cinza-azulado mt-2">Imagem que aparece quando o link é compartilhado em redes sociais (recomendado: 1200x630px)</p>
                        </div>
                    </div>
                </div>
                
                <!-- Seção: Email/SMTP -->
                <div class="settings-section bg-azul-cosmico rounded-lg border border-azul-medio p-8" data-section="email">
                    <h2 class="text-2xl font-bold text-dourado-luz mb-6">📧 Configurações de Email (SMTP)</h2>
                    
                    <div class="space-y-6">
                        <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4 mb-6">
                            <p class="text-blue-300 text-sm">
                                ℹ️ Configure o servidor SMTP para envio de emails (relatórios de backup, notificações, etc.)
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-dourado-luz font-semibold mb-2">Host SMTP</label>
                                <input type="text" name="smtp_host" value="<?= htmlspecialchars($settings['smtp_host'] ?? '') ?>"
                                       class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                       placeholder="mail.faroldeluz.ong.br">
                                <p class="text-sm text-cinza-azulado mt-2">Endereço do servidor SMTP</p>
                            </div>
                            
                            <div>
                                <label class="block text-dourado-luz font-semibold mb-2">Porta SMTP</label>
                                <input type="number" name="smtp_port" value="<?= htmlspecialchars($settings['smtp_port'] ?? '') ?>"
                                       class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                       placeholder="587">
                                <p class="text-sm text-cinza-azulado mt-2">Porta: 587 (TLS) ou 465 (SSL)</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-dourado-luz font-semibold mb-2">Usuário SMTP</label>
                                <input type="text" name="smtp_user" value="<?= htmlspecialchars($settings['smtp_user'] ?? '') ?>"
                                       class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                       placeholder="contato@faroldeluz.ong.br">
                                <p class="text-sm text-cinza-azulado mt-2">Email de login no servidor SMTP</p>
                            </div>
                            
                            <div>
                                <label class="block text-dourado-luz font-semibold mb-2">Senha SMTP</label>
                                <div class="relative">
                                    <input type="password" id="smtp_password" name="smtp_password" value="<?= htmlspecialchars($settings['smtp_password'] ?? '') ?>"
                                           class="w-full px-4 py-3 pr-12 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                           placeholder="••••••••">
                                    <button type="button" onclick="togglePasswordVisibility()" 
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-cinza-azulado hover:text-dourado-luz transition-colors">
                                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-sm text-cinza-azulado mt-2">Senha do email</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-dourado-luz font-semibold mb-2">Criptografia</label>
                                <select name="smtp_encryption" class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz">
                                    <option value="tls" <?= ($settings['smtp_encryption'] ?? '') === 'tls' ? 'selected' : '' ?>>TLS</option>
                                    <option value="ssl" <?= ($settings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                                </select>
                                <p class="text-sm text-cinza-azulado mt-2">Tipo de criptografia</p>
                            </div>
                            
                            <div>
                                <label class="block text-dourado-luz font-semibold mb-2">Nome do Remetente</label>
                                <input type="text" name="smtp_from_name" value="<?= htmlspecialchars($settings['smtp_from_name'] ?? '') ?>"
                                       class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                       placeholder="Farol de Luz">
                                <p class="text-sm text-cinza-azulado mt-2">Nome que aparece no email</p>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-dourado-luz font-semibold mb-2">Email do Remetente</label>
                            <input type="email" name="smtp_from_email" value="<?= htmlspecialchars($settings['smtp_from_email'] ?? '') ?>"
                                   class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                   placeholder="contato@faroldeluz.ong.br">
                            <p class="text-sm text-cinza-azulado mt-2">Email que aparece como remetente</p>
                        </div>
                        
                        <div class="bg-green-500/10 border border-green-500/30 rounded-lg p-4">
                            <p class="text-green-300 text-sm mb-4">
                                <strong>✉️ Testar Envio de Email:</strong>
                            </p>
                            <div class="flex gap-3">
                                <input type="email" id="test_email" 
                                       class="flex-1 px-4 py-2 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                       placeholder="Digite o email para teste"
                                       value="<?= htmlspecialchars($settings['backup_notification_email'] ?? 'contato@faroldeluz.ong.br') ?>">
                                <button type="button" onclick="testEmail()" 
                                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                                    Enviar Teste
                                </button>
                            </div>
                            <div id="email-test-result" class="mt-3 text-sm"></div>
                            <p class="text-green-200 text-sm mt-3">
                                ⚠️ Salve as configurações SMTP antes de testar o envio
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Seção: Backup -->
                <div class="settings-section bg-azul-cosmico rounded-lg border border-azul-medio p-8" data-section="backup">
                    <h2 class="text-2xl font-bold text-dourado-luz mb-6">🔐 Backup e Segurança</h2>
                    
                    <div class="space-y-8">
                        <!-- Backup do Banco de Dados -->
                        <div>
                            <h3 class="text-xl font-bold text-azul-turquesa mb-4">💾 Backup do Banco de Dados</h3>
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="backup_enabled" value="1" 
                                           <?= ($settings['backup_enabled'] ?? '0') === '1' ? 'checked' : '' ?>
                                           class="w-5 h-5 bg-azul-noite border-azul-medio rounded focus:ring-dourado-luz">
                                    <label class="text-white font-semibold">Ativar backup automático do banco de dados</label>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-dourado-luz font-semibold mb-2">Horário do Backup</label>
                                        <input type="time" name="backup_time" value="<?= htmlspecialchars($settings['backup_time'] ?? '03:00') ?>"
                                               class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz">
                                        <p class="text-sm text-cinza-azulado mt-2">Horário diário para backup automático</p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-dourado-luz font-semibold mb-2">Retenção (dias)</label>
                                        <input type="number" name="backup_retention_days" value="<?= htmlspecialchars($settings['backup_retention_days'] ?? '30') ?>"
                                               class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                               min="7" max="90">
                                        <p class="text-sm text-cinza-azulado mt-2">Quantos dias manter os backups</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Backup de Arquivos -->
                        <div>
                            <h3 class="text-xl font-bold text-azul-turquesa mb-4">📁 Backup de Arquivos</h3>
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="backup_files_enabled" value="1" 
                                           <?= ($settings['backup_files_enabled'] ?? '0') === '1' ? 'checked' : '' ?>
                                           class="w-5 h-5 bg-azul-noite border-azul-medio rounded focus:ring-dourado-luz">
                                    <label class="text-white font-semibold">Ativar backup de arquivos (uploads)</label>
                                </div>
                                
                                <div>
                                    <label class="block text-dourado-luz font-semibold mb-2">Frequência</label>
                                    <select name="backup_files_frequency" class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz">
                                        <option value="daily" <?= ($settings['backup_files_frequency'] ?? '') === 'daily' ? 'selected' : '' ?>>Diário</option>
                                        <option value="weekly" <?= ($settings['backup_files_frequency'] ?? 'weekly') === 'weekly' ? 'selected' : '' ?>>Semanal (Domingo)</option>
                                    </select>
                                    <p class="text-sm text-cinza-azulado mt-2">Com que frequência fazer backup dos arquivos</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Google Drive -->
                        <div>
                            <h3 class="text-xl font-bold text-azul-turquesa mb-4">☁️ Google Drive</h3>
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="backup_gdrive_enabled" value="1" 
                                           <?= ($settings['backup_gdrive_enabled'] ?? '0') === '1' ? 'checked' : '' ?>
                                           class="w-5 h-5 bg-azul-noite border-azul-medio rounded focus:ring-dourado-luz">
                                    <label class="text-white font-semibold">Enviar backups para Google Drive</label>
                                </div>
                                
                                <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4">
                                    <p class="text-blue-300 text-sm mb-2">
                                        <strong>ℹ️ Como configurar:</strong>
                                    </p>
                                    <ol class="text-blue-200 text-sm space-y-1 list-decimal list-inside">
                                        <li>Acesse <a href="https://console.cloud.google.com" target="_blank" class="underline">Google Cloud Console</a></li>
                                        <li>Crie um projeto e ative a Google Drive API</li>
                                        <li>Crie credenciais OAuth 2.0</li>
                                        <li>Cole as credenciais abaixo</li>
                                    </ol>
                                    <p class="text-blue-200 text-sm mt-2">
                                        📚 <a href="<?= base_url('docs/BACKUP_SYSTEM.md') ?>" target="_blank" class="underline">Ver documentação completa</a>
                                    </p>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-dourado-luz font-semibold mb-2">Client ID</label>
                                        <input type="text" name="backup_gdrive_client_id" value="<?= htmlspecialchars($settings['backup_gdrive_client_id'] ?? '') ?>"
                                               class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                               placeholder="123456789-abc.apps.googleusercontent.com">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-dourado-luz font-semibold mb-2">Client Secret</label>
                                        <input type="password" name="backup_gdrive_client_secret" value="<?= htmlspecialchars($settings['backup_gdrive_client_secret'] ?? '') ?>"
                                               class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                               placeholder="GOCSPX-...">
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-dourado-luz font-semibold mb-2">Refresh Token</label>
                                        <input type="password" name="backup_gdrive_refresh_token" value="<?= htmlspecialchars($settings['backup_gdrive_refresh_token'] ?? '') ?>"
                                               class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                               placeholder="1//...">
                                        <p class="text-sm text-cinza-azulado mt-2">Obtido após autenticação OAuth</p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-dourado-luz font-semibold mb-2">ID da Pasta (opcional)</label>
                                        <input type="text" name="backup_gdrive_folder_id" value="<?= htmlspecialchars($settings['backup_gdrive_folder_id'] ?? '') ?>"
                                               class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                               placeholder="1ABC...XYZ">
                                        <p class="text-sm text-cinza-azulado mt-2">ID da pasta no Google Drive</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Notificações -->
                        <div>
                            <h3 class="text-xl font-bold text-azul-turquesa mb-4">📧 Notificações</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-dourado-luz font-semibold mb-2">Email para Notificações</label>
                                    <input type="email" name="backup_notification_email" value="<?= htmlspecialchars($settings['backup_notification_email'] ?? '') ?>"
                                           class="w-full px-4 py-3 bg-azul-noite border border-azul-medio rounded-lg text-white focus:outline-none focus:border-dourado-luz"
                                           placeholder="contato@faroldeluz.ong.br">
                                    <p class="text-sm text-cinza-azulado mt-2">Email que receberá os relatórios e alertas</p>
                                </div>
                                
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="backup_weekly_report" value="1" 
                                           <?= ($settings['backup_weekly_report'] ?? '0') === '1' ? 'checked' : '' ?>
                                           class="w-5 h-5 bg-azul-noite border-azul-medio rounded focus:ring-dourado-luz">
                                    <label class="text-white font-semibold">Enviar relatório semanal</label>
                                </div>
                                
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="backup_alert_on_failure" value="1" 
                                           <?= ($settings['backup_alert_on_failure'] ?? '0') === '1' ? 'checked' : '' ?>
                                           class="w-5 h-5 bg-azul-noite border-azul-medio rounded focus:ring-dourado-luz">
                                    <label class="text-white font-semibold">Alertar em caso de falha</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Backup Manual -->
                        <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-6">
                            <h3 class="text-xl font-bold text-dourado-luz mb-4">🚀 Backup Manual</h3>
                            <p class="text-cinza-azulado mb-4">Execute um backup imediato do banco de dados e envie para o Google Drive.</p>
                            
                            <button type="button" onclick="runBackupNow()" id="backup-btn"
                                    class="bg-dourado-luz hover:bg-dourado-intenso text-azul-noite px-6 py-3 rounded-lg font-semibold transition">
                                ▶️ Fazer Backup Agora
                            </button>
                            
                            <!-- Barra de Progresso -->
                            <div id="backup-progress" class="hidden mt-4">
                                <div class="bg-azul-medio rounded-full h-4 overflow-hidden">
                                    <div id="backup-progress-bar" class="bg-dourado-luz h-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                                <p id="backup-status" class="text-cinza-azulado text-sm mt-2"></p>
                            </div>
                            
                            <!-- Resultado -->
                            <div id="backup-result" class="mt-4"></div>
                        </div>
                        
                        <!-- Informações Importantes -->
                        <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-4">
                            <p class="text-yellow-300 text-sm mb-2">
                                <strong>⚠️ Configuração do Cron:</strong>
                            </p>
                            <p class="text-yellow-200 text-sm mb-3">
                                Para que os backups funcionem automaticamente, você precisa configurar os cron jobs no servidor.
                            </p>
                            <p class="text-yellow-200 text-sm">
                                📚 <a href="<?= base_url('docs/BACKUP_SYSTEM.md#configuração-do-cron') ?>" target="_blank" class="underline">Ver instruções de configuração do Cron</a>
                            </p>
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

<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
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

tinymce.init({
    selector: '#editor-meta-description',
    skin: 'oxide-dark',
    content_css: 'dark',
    height: 200,
    menubar: false,
    language: 'pt_BR',
    plugins: ['wordcount', 'charmap'],
    toolbar: 'bold italic | removeformat',
    content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; color: #fff; background-color: #0B1020; }',
    setup: function(editor) {
        editor.on('init', function() {
            var content = <?= json_encode($settings['site_meta_description'] ?? '') ?>;
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
    document.getElementById('site_meta_description_input').value = tinymce.get('editor-meta-description').getContent();
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

// Alternar visibilidade da senha SMTP
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('smtp_password');
    const eyeIcon = document.getElementById('eye-icon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
    } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
    }
}

// Executar backup manual
function runBackupNow() {
    const btn = document.getElementById('backup-btn');
    const progressDiv = document.getElementById('backup-progress');
    const progressBar = document.getElementById('backup-progress-bar');
    const statusText = document.getElementById('backup-status');
    const resultDiv = document.getElementById('backup-result');
    
    btn.disabled = true;
    btn.innerHTML = '⏳ Executando...';
    btn.classList.add('opacity-50', 'cursor-not-allowed');
    
    progressDiv.classList.remove('hidden');
    progressBar.style.width = '0%';
    statusText.textContent = 'Iniciando backup...';
    resultDiv.innerHTML = '';
    
    let progress = 0;
    const progressInterval = setInterval(() => {
        if (progress < 90) {
            progress += Math.random() * 15;
            if (progress > 90) progress = 90;
            progressBar.style.width = progress + '%';
            
            if (progress < 30) {
                statusText.textContent = '📋 Exportando tabelas do banco de dados...';
            } else if (progress < 60) {
                statusText.textContent = '🗜️ Comprimindo arquivo...';
            } else {
                statusText.textContent = '☁️ Enviando para Google Drive...';
            }
        }
    }, 500);
    
    fetch('<?= base_url("backup/backup_database_pdo.php") ?>', {
        method: 'GET'
    })
    .then(response => response.text())
    .then(output => {
        clearInterval(progressInterval);
        progressBar.style.width = '100%';
        statusText.textContent = '✅ Backup concluído!';
        
        setTimeout(() => {
            progressDiv.classList.add('hidden');
            
            if (output.includes('✅ Enviado para Google Drive')) {
                resultDiv.innerHTML = '<div class="bg-green-500/10 border border-green-500/30 rounded-lg p-4"><p class="text-green-400 font-semibold">✅ Backup criado e enviado para o Google Drive com sucesso!</p><p class="text-cinza-azulado text-sm mt-2">Verifique seu Google Drive para confirmar o arquivo.</p></div>';
            } else if (output.includes('✅ Backup criado com sucesso')) {
                resultDiv.innerHTML = '<div class="bg-green-500/10 border border-green-500/30 rounded-lg p-4"><p class="text-green-400 font-semibold">✅ Backup criado com sucesso!</p><p class="text-cinza-azulado text-sm mt-2">Backup salvo localmente no servidor.</p></div>';
            } else {
                resultDiv.innerHTML = '<div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4"><p class="text-red-400 font-semibold">❌ Erro ao criar backup</p><p class="text-cinza-azulado text-sm mt-2">Verifique os logs do servidor.</p></div>';
            }
            
            btn.disabled = false;
            btn.innerHTML = '▶️ Fazer Backup Agora';
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        }, 1000);
    })
    .catch(error => {
        clearInterval(progressInterval);
        progressDiv.classList.add('hidden');
        resultDiv.innerHTML = '<div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4"><p class="text-red-400 font-semibold">❌ Erro: ' + error.message + '</p></div>';
        
        btn.disabled = false;
        btn.innerHTML = '▶️ Fazer Backup Agora';
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
    });
}

// Testar envio de email
function testEmail() {
    const emailInput = document.getElementById('test_email');
    const resultDiv = document.getElementById('email-test-result');
    const email = emailInput.value.trim();
    
    if (!email) {
        resultDiv.innerHTML = '<p class="text-red-400">❌ Digite um email válido</p>';
        return;
    }
    
    resultDiv.innerHTML = '<p class="text-blue-400">⏳ Enviando email de teste...</p>';
    
    fetch('<?= base_url("test_email_direct.php") ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'email=' + encodeURIComponent(email)
    })
    .then(response => {
        return response.text().then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Resposta do servidor:', text);
                throw new Error('Resposta invalida do servidor. Verifique o console.');
            }
        });
    })
    .then(data => {
        if (data.success) {
            resultDiv.innerHTML = '<p class="text-green-400">✅ ' + data.message + '</p>';
        } else {
            resultDiv.innerHTML = '<p class="text-red-400">❌ ' + data.message + '</p>';
        }
    })
    .catch(error => {
        resultDiv.innerHTML = '<p class="text-red-400">❌ Erro: ' + error.message + '</p>';
    });
}
</script>

<?php require_once BASE_PATH . '/views/admin/layout/footer.php'; ?>
