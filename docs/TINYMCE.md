# 📝 TinyMCE - Guia Completo de Implementação

**Editor WYSIWYG Avançado - Nível Gutenberg do WordPress**

---

## 📋 Índice

1. [Introdução](#introdução)
2. [Instalação e Configuração](#instalação-e-configuração)
3. [Configuração Básica](#configuração-básica)
4. [Configuração Avançada](#configuração-avançada)
5. [Plugins Essenciais](#plugins-essenciais)
6. [Toolbar Personalizada](#toolbar-personalizada)
7. [Upload de Imagens](#upload-de-imagens)
8. [Estilos Customizados](#estilos-customizados)
9. [Templates e Blocos](#templates-e-blocos)
10. [Integração com Backend](#integração-com-backend)
11. [Boas Práticas](#boas-práticas)
12. [Troubleshooting](#troubleshooting)

---

## 🎯 Introdução

O **TinyMCE** é um editor WYSIWYG (What You See Is What You Get) rico em recursos, usado no projeto Farol de Luz para criar e editar conteúdo de forma visual e intuitiva.

### Por que TinyMCE?

- ✅ **Gratuito e Open Source**
- ✅ **Altamente customizável**
- ✅ **Suporte a plugins**
- ✅ **Upload de imagens integrado**
- ✅ **Responsivo e moderno**
- ✅ **Compatível com todos os navegadores**
- ✅ **Documentação extensa**

---

## 🚀 Instalação e Configuração

### Método 1: CDN (Recomendado para produção)

```html
<!-- No <head> do seu HTML -->
<script src="https://cdn.tiny.cloud/1/YOUR-API-KEY/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
```

### Método 2: Self-hosted (Usado no Farol de Luz)

```html
<!-- Baixe o TinyMCE e coloque em assets/js/tinymce/ -->
<script src="<?= asset_url('js/tinymce/tinymce.min.js') ?>"></script>
```

**Vantagens do self-hosted:**
- ✅ Funciona offline
- ✅ Sem dependência de API key
- ✅ Controle total sobre versão
- ✅ Melhor performance

---

## ⚙️ Configuração Básica

### Inicialização Simples

```javascript
tinymce.init({
    selector: 'textarea#content',  // Seletor do textarea
    height: 500,                    // Altura do editor
    menubar: false,                 // Esconder menu superior
    plugins: 'link image code',     // Plugins básicos
    toolbar: 'undo redo | bold italic | link image | code'
});
```

### Configuração Atual do Farol de Luz

```javascript
tinymce.init({
    selector: 'textarea#content',
    height: 600,
    menubar: true,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
        'preview', 'anchor', 'searchreplace', 'visualblocks', 'code',
        'fullscreen', 'insertdatetime', 'media', 'table', 'help',
        'wordcount', 'emoticons', 'codesample'
    ],
    toolbar: 'undo redo | formatselect | bold italic underline strikethrough | ' +
             'alignleft aligncenter alignright alignjustify | ' +
             'bullist numlist outdent indent | link image media | ' +
             'forecolor backcolor | emoticons codesample | ' +
             'removeformat code fullscreen',
    
    // Configurações de conteúdo
    content_style: 'body { font-family: Poppins, sans-serif; font-size: 16px; }',
    
    // Configurações de imagem
    image_advtab: true,
    image_caption: true,
    
    // Configurações de link
    link_default_target: '_blank',
    link_assume_external_targets: true,
    
    // Idioma
    language: 'pt_BR',
    language_url: '<?= asset_url("js/tinymce/langs/pt_BR.js") ?>'
});
```

---

## 🔧 Configuração Avançada (Nível Gutenberg)

### 1. Content CSS Customizado

```javascript
tinymce.init({
    selector: 'textarea#content',
    content_css: [
        '<?= asset_url("css/tailwind.css") ?>',  // TailwindCSS
        '<?= asset_url("css/tinymce-custom.css") ?>'  // Estilos customizados
    ],
    content_style: `
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
        blockquote {
            border-left: 4px solid #FFD700;
            padding-left: 20px;
            margin: 20px 0;
            font-style: italic;
            color: #666;
        }
    `
});
```

### 2. Formatos Customizados

```javascript
tinymce.init({
    selector: 'textarea#content',
    style_formats: [
        {
            title: 'Títulos',
            items: [
                { title: 'Título 1', format: 'h1' },
                { title: 'Título 2', format: 'h2' },
                { title: 'Título 3', format: 'h3' },
                { title: 'Título 4', format: 'h4' }
            ]
        },
        {
            title: 'Blocos',
            items: [
                {
                    title: 'Destaque',
                    block: 'div',
                    classes: 'bg-yellow-100 border-l-4 border-yellow-500 p-4 my-4',
                    wrapper: true
                },
                {
                    title: 'Citação',
                    block: 'blockquote',
                    classes: 'border-l-4 border-blue-500 pl-4 italic'
                },
                {
                    title: 'Código',
                    block: 'pre',
                    classes: 'bg-gray-100 p-4 rounded overflow-x-auto'
                }
            ]
        },
        {
            title: 'Texto',
            items: [
                {
                    title: 'Texto Destacado',
                    inline: 'span',
                    classes: 'text-yellow-600 font-bold'
                },
                {
                    title: 'Texto Importante',
                    inline: 'span',
                    classes: 'text-red-600 font-semibold'
                }
            ]
        }
    ],
    style_formats_merge: false
});
```

### 3. Templates Prontos

```javascript
tinymce.init({
    selector: 'textarea#content',
    templates: [
        {
            title: 'Artigo Padrão',
            description: 'Template para artigos do blog',
            content: `
                <h2>Título do Artigo</h2>
                <p><em>Introdução do artigo...</em></p>
                <h3>Seção 1</h3>
                <p>Conteúdo da seção...</p>
                <h3>Seção 2</h3>
                <p>Conteúdo da seção...</p>
                <h3>Conclusão</h3>
                <p>Conclusão do artigo...</p>
            `
        },
        {
            title: 'Estudo Espiritual',
            description: 'Template para estudos',
            content: `
                <h2>Tema do Estudo</h2>
                <blockquote>
                    <p>"Citação espiritual relevante"</p>
                    <footer>- Autor</footer>
                </blockquote>
                <h3>Reflexão</h3>
                <p>Desenvolvimento da reflexão...</p>
                <h3>Aplicação Prática</h3>
                <p>Como aplicar no dia a dia...</p>
            `
        },
        {
            title: 'Diálogo',
            description: 'Template para diálogos do farol',
            content: `
                <h2>Título do Diálogo</h2>
                <p><strong>Data:</strong> [Data]</p>
                <p><strong>Tema:</strong> [Tema]</p>
                <hr>
                <h3>Introdução</h3>
                <p>Contexto do diálogo...</p>
                <h3>Desenvolvimento</h3>
                <p>Conteúdo principal...</p>
                <h3>Conclusão</h3>
                <p>Considerações finais...</p>
            `
        }
    ]
});
```

---

## 🔌 Plugins Essenciais

### Lista Completa de Plugins Recomendados

```javascript
plugins: [
    // Formatação
    'advlist',          // Listas avançadas
    'autolink',         // Links automáticos
    'lists',            // Listas
    'link',             // Inserir links
    'charmap',          // Caracteres especiais
    
    // Mídia
    'image',            // Inserir imagens
    'media',            // Vídeos e áudio
    'table',            // Tabelas
    
    // Edição
    'searchreplace',    // Buscar e substituir
    'visualblocks',     // Visualizar blocos HTML
    'code',             // Editor de código HTML
    'codesample',       // Blocos de código
    
    // Produtividade
    'fullscreen',       // Tela cheia
    'preview',          // Pré-visualização
    'anchor',           // Âncoras
    'insertdatetime',   // Inserir data/hora
    'wordcount',        // Contador de palavras
    
    // Extras
    'emoticons',        // Emojis
    'help',             // Ajuda
    'quickbars'         // Barra rápida de formatação
]
```

### Descrição Detalhada dos Plugins

#### 1. **advlist** - Listas Avançadas
```javascript
// Permite escolher estilo de marcadores
plugins: 'advlist',
advlist_bullet_styles: 'default,circle,square',
advlist_number_styles: 'default,lower-alpha,lower-roman,upper-alpha,upper-roman'
```

#### 2. **image** - Gerenciamento de Imagens
```javascript
plugins: 'image',
image_advtab: true,              // Aba avançada
image_caption: true,             // Legendas
image_title: true,               // Título da imagem
automatic_uploads: true,         // Upload automático
file_picker_types: 'image',      // Tipos de arquivo
images_upload_url: '/admin/upload-image',  // URL de upload
images_upload_handler: function (blobInfo, success, failure) {
    // Handler customizado de upload
    const formData = new FormData();
    formData.append('file', blobInfo.blob(), blobInfo.filename());
    
    fetch('/admin/upload-image', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            success(result.url);
        } else {
            failure('Upload falhou: ' + result.error);
        }
    })
    .catch(error => {
        failure('Erro de rede: ' + error);
    });
}
```

#### 3. **media** - Vídeos e Áudio
```javascript
plugins: 'media',
media_live_embeds: true,         // Embed ao vivo
media_dimensions: false,         // Dimensões automáticas
media_poster: false,             // Poster de vídeo
media_alt_source: false,         // Fonte alternativa
video_template_callback: function(data) {
    return '<video width="' + data.width + '" height="' + data.height + '"' + 
           (data.poster ? ' poster="' + data.poster + '"' : '') + ' controls="controls">\n' + 
           '<source src="' + data.source + '"' + 
           (data.sourcemime ? ' type="' + data.sourcemime + '"' : '') + ' />\n</video>';
}
```

#### 4. **table** - Tabelas
```javascript
plugins: 'table',
table_toolbar: 'tableprops tabledelete | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
table_appearance_options: true,
table_grid: true,
table_tab_navigation: true,
table_default_attributes: {
    border: '1'
},
table_default_styles: {
    'border-collapse': 'collapse',
    'width': '100%'
},
table_class_list: [
    { title: 'Nenhuma', value: '' },
    { title: 'Tabela Listrada', value: 'table-striped' },
    { title: 'Tabela Bordered', value: 'table-bordered' }
]
```

#### 5. **codesample** - Blocos de Código
```javascript
plugins: 'codesample',
codesample_languages: [
    { text: 'HTML/XML', value: 'markup' },
    { text: 'JavaScript', value: 'javascript' },
    { text: 'CSS', value: 'css' },
    { text: 'PHP', value: 'php' },
    { text: 'Python', value: 'python' },
    { text: 'Java', value: 'java' },
    { text: 'C', value: 'c' },
    { text: 'C#', value: 'csharp' },
    { text: 'C++', value: 'cpp' }
],
codesample_global_prismjs: true
```

---

## 🎨 Toolbar Personalizada

### Toolbar Completa (Nível Gutenberg)

```javascript
toolbar: [
    // Linha 1: Formatação básica
    'undo redo | formatselect | bold italic underline strikethrough | forecolor backcolor',
    
    // Linha 2: Alinhamento e listas
    'alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
    
    // Linha 3: Inserir elementos
    'link image media table | blockquote codesample emoticons charmap',
    
    // Linha 4: Ferramentas
    'searchreplace visualblocks code fullscreen | preview help'
],
toolbar_mode: 'sliding'  // 'floating', 'sliding', 'scrolling', 'wrap'
```

### Toolbar Simplificada (Para usuários básicos)

```javascript
toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image | removeformat'
```

### Toolbar Contextual (Quickbars)

```javascript
plugins: 'quickbars',
quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote',
quickbars_insert_toolbar: 'quickimage quicktable',
quickbars_image_toolbar: 'alignleft aligncenter alignright | rotateleft rotateright | imageoptions'
```

---

## 📤 Upload de Imagens

### Implementação Completa

#### Frontend (JavaScript)

```javascript
tinymce.init({
    selector: 'textarea#content',
    plugins: 'image',
    
    // Configurações de upload
    automatic_uploads: true,
    images_upload_url: '/admin/upload-image',
    images_reuse_filename: true,
    
    // Handler de upload customizado
    images_upload_handler: function (blobInfo, success, failure, progress) {
        const xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('POST', '/admin/upload-image');
        
        xhr.upload.onprogress = function (e) {
            progress(e.loaded / e.total * 100);
        };
        
        xhr.onload = function() {
            if (xhr.status === 403) {
                failure('HTTP Error: ' + xhr.status, { remove: true });
                return;
            }
            
            if (xhr.status < 200 || xhr.status >= 300) {
                failure('HTTP Error: ' + xhr.status);
                return;
            }
            
            const json = JSON.parse(xhr.responseText);
            
            if (!json || typeof json.url != 'string') {
                failure('Invalid JSON: ' + xhr.responseText);
                return;
            }
            
            success(json.url);
        };
        
        xhr.onerror = function () {
            failure('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
        };
        
        const formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());
        
        xhr.send(formData);
    },
    
    // Validação de imagens
    file_picker_types: 'image',
    images_file_types: 'jpg,jpeg,png,gif,webp',
    
    // Callback após inserir imagem
    images_upload_credentials: true,
    
    // Redimensionamento automático
    image_dimensions: true,
    image_class_list: [
        { title: 'Nenhuma', value: '' },
        { title: 'Responsiva', value: 'img-fluid' },
        { title: 'Arredondada', value: 'rounded' },
        { title: 'Circular', value: 'rounded-full' }
    ]
});
```

#### Backend (PHP)

```php
<?php
// controllers/Admin/UploadController.php

class UploadController extends Controller {
    
    public function uploadImage() {
        // Verificar autenticação
        if (!isset($_SESSION['admin_logged_in'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Não autorizado']);
            exit;
        }
        
        // Verificar se arquivo foi enviado
        if (!isset($_FILES['file'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Nenhum arquivo enviado']);
            exit;
        }
        
        $file = $_FILES['file'];
        
        // Validar tipo de arquivo
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            http_response_code(400);
            echo json_encode(['error' => 'Tipo de arquivo não permitido']);
            exit;
        }
        
        // Validar tamanho (máx 5MB)
        $maxSize = 5 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            http_response_code(400);
            echo json_encode(['error' => 'Arquivo muito grande (máx 5MB)']);
            exit;
        }
        
        // Gerar nome único
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        
        // Definir pasta de destino
        $uploadDir = BASE_PATH . '/assets/uploads/editor/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $destination = $uploadDir . $filename;
        
        // Mover arquivo
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // Retornar URL da imagem
            $url = base_url('assets/uploads/editor/' . $filename);
            echo json_encode(['url' => $url]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao salvar arquivo']);
        }
    }
}
```

---

## 🎨 Estilos Customizados

### CSS para o Editor

```css
/* assets/css/tinymce-custom.css */

/* Estilos gerais */
body {
    font-family: 'Poppins', sans-serif;
    font-size: 16px;
    line-height: 1.8;
    color: #333;
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background: #fff;
}

/* Títulos */
h1, h2, h3, h4, h5, h6 {
    font-weight: 700;
    margin-top: 1.5em;
    margin-bottom: 0.5em;
    color: #FFD700;
}

h1 { font-size: 2.5em; }
h2 { font-size: 2em; }
h3 { font-size: 1.75em; }
h4 { font-size: 1.5em; }

/* Parágrafos */
p {
    margin-bottom: 1em;
}

/* Links */
a {
    color: #4ECDC4;
    text-decoration: underline;
}

a:hover {
    color: #FFD700;
}

/* Imagens */
img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

img.img-fluid {
    width: 100%;
}

img.rounded {
    border-radius: 8px;
}

img.rounded-full {
    border-radius: 50%;
}

/* Citações */
blockquote {
    border-left: 4px solid #FFD700;
    padding-left: 20px;
    margin: 20px 0;
    font-style: italic;
    color: #666;
    background: #f9f9f9;
    padding: 15px 20px;
    border-radius: 4px;
}

blockquote footer {
    margin-top: 10px;
    font-size: 0.9em;
    color: #999;
}

/* Listas */
ul, ol {
    margin: 1em 0;
    padding-left: 2em;
}

li {
    margin-bottom: 0.5em;
}

/* Tabelas */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

table th,
table td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: left;
}

table th {
    background: #FFD700;
    color: #0A0E27;
    font-weight: 600;
}

table.table-striped tbody tr:nth-child(odd) {
    background: #f9f9f9;
}

/* Código */
code {
    background: #f4f4f4;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: 'Courier New', monospace;
    font-size: 0.9em;
    color: #e83e8c;
}

pre {
    background: #2d2d2d;
    color: #f8f8f2;
    padding: 15px;
    border-radius: 5px;
    overflow-x: auto;
    margin: 20px 0;
}

pre code {
    background: none;
    color: inherit;
    padding: 0;
}

/* Blocos customizados */
.destaque {
    background: #fff3cd;
    border-left: 4px solid #ffc107;
    padding: 15px;
    margin: 20px 0;
    border-radius: 4px;
}

.importante {
    background: #f8d7da;
    border-left: 4px solid #dc3545;
    padding: 15px;
    margin: 20px 0;
    border-radius: 4px;
}

.info {
    background: #d1ecf1;
    border-left: 4px solid #17a2b8;
    padding: 15px;
    margin: 20px 0;
    border-radius: 4px;
}

/* Responsividade */
@media (max-width: 768px) {
    body {
        padding: 10px;
        font-size: 14px;
    }
    
    h1 { font-size: 2em; }
    h2 { font-size: 1.75em; }
    h3 { font-size: 1.5em; }
    
    table {
        font-size: 0.9em;
    }
}
```

---

## 📦 Templates e Blocos

### Sistema de Templates

```javascript
tinymce.init({
    selector: 'textarea#content',
    plugins: 'template',
    
    templates: [
        {
            title: 'Artigo Completo',
            description: 'Template completo para artigos',
            content: `
                <div class="article-header">
                    <h1>Título do Artigo</h1>
                    <p class="meta">
                        <span class="author">Por: Autor</span> | 
                        <span class="date">${new Date().toLocaleDateString('pt-BR')}</span>
                    </p>
                </div>
                
                <div class="article-intro">
                    <p><strong>Introdução:</strong> Breve resumo do artigo...</p>
                </div>
                
                <div class="article-body">
                    <h2>Seção 1</h2>
                    <p>Conteúdo da primeira seção...</p>
                    
                    <h2>Seção 2</h2>
                    <p>Conteúdo da segunda seção...</p>
                </div>
                
                <div class="article-conclusion">
                    <h2>Conclusão</h2>
                    <p>Considerações finais...</p>
                </div>
            `
        },
        {
            title: 'Caixa de Destaque',
            description: 'Caixa para destacar informações importantes',
            content: `
                <div class="destaque">
                    <h3>💡 Destaque</h3>
                    <p>Informação importante aqui...</p>
                </div>
            `
        },
        {
            title: 'Citação com Autor',
            description: 'Bloco de citação formatado',
            content: `
                <blockquote>
                    <p>"Texto da citação aqui..."</p>
                    <footer>— <cite>Nome do Autor</cite></footer>
                </blockquote>
            `
        },
        {
            title: 'Lista de Recursos',
            description: 'Lista formatada com ícones',
            content: `
                <ul class="feature-list">
                    <li>✅ Recurso 1</li>
                    <li>✅ Recurso 2</li>
                    <li>✅ Recurso 3</li>
                </ul>
            `
        },
        {
            title: 'Chamada para Ação',
            description: 'Botão de call-to-action',
            content: `
                <div class="cta-box">
                    <h3>Título da Chamada</h3>
                    <p>Descrição breve...</p>
                    <a href="#" class="btn-cta">Clique Aqui</a>
                </div>
            `
        }
    ],
    
    // Permitir editar templates
    template_cdate_format: '[Data: %d/%m/%Y]',
    template_mdate_format: '[Modificado: %d/%m/%Y às %H:%M]',
    template_replace_values: {
        author: 'Nome do Autor',
        date: new Date().toLocaleDateString('pt-BR')
    }
});
```

---

## 🔗 Integração com Backend

### Salvando Conteúdo

```javascript
// Ao submeter formulário
document.querySelector('form').addEventListener('submit', function(e) {
    // TinyMCE já sincroniza automaticamente com o textarea
    // Mas você pode forçar a sincronização:
    tinymce.triggerSave();
    
    // Ou pegar o conteúdo diretamente:
    const content = tinymce.get('content').getContent();
    console.log(content);
});
```

### Carregando Conteúdo

```php
<!-- No formulário de edição -->
<textarea id="content" name="content"><?= htmlspecialchars($post['content']) ?></textarea>
```

### Validação

```javascript
// Validar se há conteúdo
function validateContent() {
    const content = tinymce.get('content').getContent({format: 'text'}).trim();
    
    if (content.length === 0) {
        alert('O conteúdo não pode estar vazio!');
        return false;
    }
    
    if (content.length < 50) {
        alert('O conteúdo deve ter pelo menos 50 caracteres!');
        return false;
    }
    
    return true;
}

// Usar na validação do formulário
document.querySelector('form').addEventListener('submit', function(e) {
    if (!validateContent()) {
        e.preventDefault();
    }
});
```

---

## ✅ Boas Práticas

### 1. Performance

```javascript
// Carregar TinyMCE apenas quando necessário
if (document.querySelector('textarea#content')) {
    // Carregar script dinamicamente
    const script = document.createElement('script');
    script.src = '/assets/js/tinymce/tinymce.min.js';
    script.onload = function() {
        initTinyMCE();
    };
    document.head.appendChild(script);
}

// Destruir instância ao sair da página
window.addEventListener('beforeunload', function() {
    tinymce.remove();
});
```

### 2. Segurança

```javascript
tinymce.init({
    selector: 'textarea#content',
    
    // Sanitizar conteúdo
    extended_valid_elements: 'a[href|target|title],img[src|alt|title|width|height|class],div[class],span[class]',
    
    // Remover scripts
    invalid_elements: 'script,iframe,object,embed',
    
    // Converter URLs relativas
    convert_urls: false,
    relative_urls: false,
    remove_script_host: false
});
```

### 3. Acessibilidade

```javascript
tinymce.init({
    selector: 'textarea#content',
    
    // Adicionar atributos alt automaticamente
    image_description: true,
    image_title: true,
    
    // Verificar acessibilidade
    a11y_advanced_options: true,
    
    // Atalhos de teclado
    setup: function(editor) {
        editor.addShortcut('ctrl+shift+h', 'Adicionar Heading 2', function() {
            editor.execCommand('mceToggleFormat', false, 'h2');
        });
    }
});
```

---

## 🐛 Troubleshooting

### Problema 1: Editor não carrega

**Solução:**
```javascript
// Verificar se o script foi carregado
if (typeof tinymce === 'undefined') {
    console.error('TinyMCE não foi carregado!');
}

// Verificar se o seletor existe
if (!document.querySelector('textarea#content')) {
    console.error('Textarea não encontrado!');
}

// Inicializar com timeout
setTimeout(function() {
    tinymce.init({...});
}, 100);
```

### Problema 2: Upload de imagens não funciona

**Solução:**
```javascript
// Adicionar logs para debug
images_upload_handler: function (blobInfo, success, failure) {
    console.log('Iniciando upload...', blobInfo.filename());
    
    // ... código de upload ...
    
    xhr.onload = function() {
        console.log('Response:', xhr.responseText);
        // ... resto do código ...
    };
}
```

### Problema 3: Conteúdo não salva

**Solução:**
```javascript
// Forçar sincronização antes de submeter
document.querySelector('form').addEventListener('submit', function(e) {
    tinymce.triggerSave();
    
    // Verificar se salvou
    const content = document.querySelector('textarea#content').value;
    console.log('Conteúdo a ser salvo:', content);
});
```

### Problema 4: Estilos não aparecem no editor

**Solução:**
```javascript
tinymce.init({
    selector: 'textarea#content',
    
    // Forçar carregamento de CSS
    content_css: [
        '/assets/css/tailwind.css',
        '/assets/css/tinymce-custom.css'
    ],
    
    // Adicionar estilos inline
    content_style: `
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
    `
});
```

---

## 📚 Recursos Adicionais

### Documentação Oficial
- [TinyMCE Documentation](https://www.tiny.cloud/docs/)
- [TinyMCE API Reference](https://www.tiny.cloud/docs/api/)
- [TinyMCE Plugins](https://www.tiny.cloud/docs/plugins/)

### Comunidade
- [TinyMCE Forum](https://community.tiny.cloud/)
- [Stack Overflow](https://stackoverflow.com/questions/tagged/tinymce)
- [GitHub Issues](https://github.com/tinymce/tinymce/issues)

### Exemplos
- [TinyMCE Fiddle](https://fiddle.tiny.cloud/)
- [CodePen Examples](https://codepen.io/tag/tinymce)

---

## 🎓 Treinamento para IA

### Prompt para IA

```
Você é um especialista em TinyMCE. Ao implementar um editor:

1. SEMPRE use a configuração completa com todos os plugins necessários
2. SEMPRE implemente upload de imagens com validação
3. SEMPRE adicione estilos customizados para o conteúdo
4. SEMPRE crie templates úteis para o contexto
5. SEMPRE valide o conteúdo antes de salvar
6. SEMPRE sanitize o HTML para segurança
7. SEMPRE teste a responsividade
8. SEMPRE adicione acessibilidade

Configuração base:
[Cole a configuração completa do Farol de Luz aqui]

Plugins essenciais: advlist, autolink, lists, link, image, charmap, preview, anchor, searchreplace, visualblocks, code, fullscreen, insertdatetime, media, table, help, wordcount, emoticons, codesample

Upload de imagens: Sempre implementar com validação de tipo, tamanho e segurança.

Estilos: Sempre criar CSS customizado que reflita o design do site.
```

---

**✨ Com este guia, você tem tudo para implementar um editor TinyMCE de nível profissional! ✨**
