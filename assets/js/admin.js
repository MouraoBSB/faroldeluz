/**
 * JavaScript Admin Farol de Luz
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 17:17:00
 */

document.addEventListener('DOMContentLoaded', function() {
    initFileUploadValidation();
    initFormSubmitProgress();
});

function initFileUploadValidation() {
    const pdfInput = document.querySelector('input[name="pdf_file"]');
    const coverInput = document.querySelector('input[name="cover_image"]');
    
    if (pdfInput) {
        pdfInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const maxSize = 50 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert('O arquivo PDF é muito grande. Tamanho máximo: 50MB');
                    e.target.value = '';
                    return;
                }
                
                const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
                console.log(`PDF selecionado: ${file.name} (${sizeInMB}MB)`);
            }
        });
    }
    
    if (coverInput) {
        coverInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const maxSize = 10 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert('A imagem é muito grande. Tamanho máximo: 10MB');
                    e.target.value = '';
                    return;
                }
                
                const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
                console.log(`Imagem selecionada: ${file.name} (${sizeInMB}MB)`);
            }
        });
    }
}

function initFormSubmitProgress() {
    const forms = document.querySelectorAll('form[enctype="multipart/form-data"]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            const pdfInput = form.querySelector('input[name="pdf_file"]');
            
            if (pdfInput && pdfInput.files.length > 0) {
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Enviando PDF...';
                }
                
                showUploadProgress();
            }
        });
    });
}

function showUploadProgress() {
    const progressDiv = document.createElement('div');
    progressDiv.id = 'upload-progress';
    progressDiv.className = 'fixed top-4 right-4 bg-azul-cosmico border border-dourado-luz rounded-lg p-4 shadow-lg z-50';
    progressDiv.innerHTML = `
        <div class="flex items-center space-x-3">
            <div class="animate-spin text-2xl">⏳</div>
            <div>
                <div class="text-dourado-luz font-semibold">Enviando arquivo...</div>
                <div class="text-sm text-cinza-azulado">Aguarde, isso pode levar alguns minutos</div>
            </div>
        </div>
    `;
    document.body.appendChild(progressDiv);
}
