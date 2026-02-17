/**
 * Configuração do Editor Quill
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-16 16:10:00
 */

var Parchment = Quill.import('parchment');
var lineHeightConfig = {
    scope: Parchment.Scope.BLOCK,
    whitelist: ['1', '1.5', '2', '2.5', '3']
};
var lineHeightClass = new Parchment.Attributor.Class('lineheight', 'ql-line-height', lineHeightConfig);
var lineHeightStyle = new Parchment.Attributor.Style('lineheight', 'line-height', lineHeightConfig);
Quill.register(lineHeightStyle, true);

var toolbarOptions = [
    [{ 'header': [3, false] }],
    ['bold', 'italic', 'underline'],
    [{ 'align': ['', 'center', 'right', 'justify'] }],
    [{ 'lineheight': ['1', '1.5', '2', '2.5', '3'] }],
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    ['link'],
    ['clean']
];

var quill = new Quill('#editor', {
    theme: 'snow',
    modules: {
        toolbar: {
            container: toolbarOptions,
            handlers: {
                'html': function() {
                    toggleHtmlMode('editor', 'html-editor', quill);
                }
            }
        }
    }
});

var quillRodape = new Quill('#editor-rodape', {
    theme: 'snow',
    modules: {
        toolbar: {
            container: toolbarOptions,
            handlers: {
                'html': function() {
                    toggleHtmlMode('editor-rodape', 'html-editor-rodape', quillRodape);
                }
            }
        }
    }
});

document.querySelector('#editor').previousElementSibling.innerHTML += '<button class="ql-html" type="button" title="Editar HTML"></button>';
document.querySelector('#editor-rodape').previousElementSibling.innerHTML += '<button class="ql-html" type="button" title="Editar HTML"></button>';

var isHtmlMode = false;
var isHtmlModeRodape = false;

function toggleHtmlMode(editorId, textareaId, quillInstance) {
    var editor = document.getElementById(editorId);
    var textarea = document.getElementById(textareaId);
    var isRodape = editorId.includes('rodape');
    
    if (isRodape ? !isHtmlModeRodape : !isHtmlMode) {
        textarea.value = quillInstance.root.innerHTML;
        editor.style.display = 'none';
        textarea.style.display = 'block';
        if (isRodape) {
            isHtmlModeRodape = true;
        } else {
            isHtmlMode = true;
        }
    } else {
        quillInstance.root.innerHTML = textarea.value;
        editor.style.display = 'block';
        textarea.style.display = 'none';
        if (isRodape) {
            isHtmlModeRodape = false;
        } else {
            isHtmlMode = false;
        }
    }
}

document.querySelectorAll('.ql-html').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var toolbar = this.closest('.ql-toolbar');
        var editorContainer = toolbar.nextElementSibling;
        var editorId = editorContainer.id;
        var textareaId = editorContainer.nextElementSibling.id;
        var quillInstance = editorId === 'editor' ? quill : quillRodape;
        toggleHtmlMode(editorId, textareaId, quillInstance);
    });
});

function initQuillEditors(existingContent, existingRodape) {
    if (existingContent) {
        quill.root.innerHTML = existingContent;
    }
    
    if (existingRodape) {
        quillRodape.root.innerHTML = existingRodape;
    }
    
    document.getElementById('settingsForm').addEventListener('submit', function(e) {
        if (isHtmlMode) {
            quill.root.innerHTML = document.getElementById('html-editor').value;
        }
        if (isHtmlModeRodape) {
            quillRodape.root.innerHTML = document.getElementById('html-editor-rodape').value;
        }
        document.getElementById('revista_descricao_input').value = quill.root.innerHTML;
        document.getElementById('revista_texto_adicional_input').value = quillRodape.root.innerHTML;
    });
}
