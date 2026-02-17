<aside class="w-64 bg-azul-cosmico border-r border-azul-medio flex-shrink-0">
    <div class="p-6">
        <div class="flex items-center space-x-3 mb-8">
            <img src="<?= asset_url('images/logo.png') ?>" alt="Farol de Luz" class="h-10 w-10">
            <div>
                <div class="text-lg font-bold text-dourado-luz">Farol de Luz</div>
                <div class="text-xs text-cinza-azulado">Painel Admin</div>
            </div>
        </div>
        
        <nav class="space-y-2">
            <a href="<?= base_url('admin/dashboard') ?>" class="block px-4 py-3 rounded-lg hover:bg-azul-noite transition <?= strpos($_SERVER['REQUEST_URI'], '/admin/dashboard') !== false ? 'bg-azul-noite text-dourado-luz' : 'text-white' ?>">
                📊 Dashboard
            </a>
            <a href="<?= base_url('admin/revistas') ?>" class="block px-4 py-3 rounded-lg hover:bg-azul-noite transition <?= strpos($_SERVER['REQUEST_URI'], '/admin/revistas') !== false ? 'bg-azul-noite text-dourado-luz' : 'text-white' ?>">
                📚 Revistas
            </a>
            <a href="<?= base_url('admin/dialogos') ?>" class="block px-4 py-3 rounded-lg hover:bg-azul-noite transition <?= strpos($_SERVER['REQUEST_URI'], '/admin/dialogos') !== false ? 'bg-azul-noite text-dourado-luz' : 'text-white' ?>">
                🎥 Diálogos
            </a>
            <a href="<?= base_url('admin/rajian') ?>" class="block px-4 py-3 rounded-lg hover:bg-azul-noite transition <?= strpos($_SERVER['REQUEST_URI'], '/admin/rajian') !== false ? 'bg-azul-noite text-dourado-luz' : 'text-white' ?>">
                📖 Estudos Rajian
            </a>
            <div class="blog-menu">
                <button onclick="toggleBlogMenu()" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-azul-noite transition <?= (strpos($_SERVER['REQUEST_URI'], '/admin/blog') !== false || strpos($_SERVER['REQUEST_URI'], '/admin/taxonomias') !== false) ? 'bg-azul-noite text-dourado-luz' : 'text-white' ?>">
                    <span>✍️ Blog</span>
                    <svg class="w-4 h-4 transition-transform" id="blog-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="blog-submenu" class="ml-4 mt-1 space-y-1 <?= (strpos($_SERVER['REQUEST_URI'], '/admin/blog') !== false || strpos($_SERVER['REQUEST_URI'], '/admin/taxonomias') !== false) ? '' : 'hidden' ?>">
                    <a href="<?= base_url('admin/blog') ?>" class="block px-4 py-2 rounded-lg hover:bg-azul-noite transition text-sm <?= strpos($_SERVER['REQUEST_URI'], '/admin/blog') !== false ? 'text-dourado-luz' : 'text-cinza-azulado' ?>">
                        📝 Posts
                    </a>
                    <a href="<?= base_url('admin/taxonomias') ?>" class="block px-4 py-2 rounded-lg hover:bg-azul-noite transition text-sm <?= strpos($_SERVER['REQUEST_URI'], '/admin/taxonomias') !== false ? 'text-dourado-luz' : 'text-cinza-azulado' ?>">
                        🏷️ Categorias/Tags
                    </a>
                </div>
            </div>
            <a href="<?= base_url('admin/newsletter') ?>" class="block px-4 py-3 rounded-lg hover:bg-azul-noite transition <?= strpos($_SERVER['REQUEST_URI'], '/admin/newsletter') !== false ? 'bg-azul-noite text-dourado-luz' : 'text-white' ?>">
                📧 Newsletter
            </a>
            <a href="<?= base_url('admin/configuracoes') ?>" class="block px-4 py-3 rounded-lg hover:bg-azul-noite transition <?= strpos($_SERVER['REQUEST_URI'], '/admin/configuracoes') !== false ? 'bg-azul-noite text-dourado-luz' : 'text-white' ?>">
                ⚙️ Configurações
            </a>
            
            <div class="border-t border-azul-medio my-4"></div>
            
            <a href="<?= base_url() ?>" target="_blank" class="block px-4 py-3 rounded-lg hover:bg-azul-noite transition text-azul-turquesa">
                🌐 Ver Site
            </a>
            <a href="<?= base_url('admin/logout') ?>" class="block px-4 py-3 rounded-lg hover:bg-azul-noite transition text-red-400">
                🚪 Sair
            </a>
        </nav>
    </div>
</aside>

<script>
function toggleBlogMenu() {
    const submenu = document.getElementById('blog-submenu');
    const arrow = document.getElementById('blog-arrow');
    
    if (submenu.classList.contains('hidden')) {
        submenu.classList.remove('hidden');
        arrow.style.transform = 'rotate(180deg)';
    } else {
        submenu.classList.add('hidden');
        arrow.style.transform = 'rotate(0deg)';
    }
}

// Manter submenu aberto se estiver em uma página relacionada ao blog
document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname;
    if (currentPath.includes('/admin/blog') || currentPath.includes('/admin/taxonomias')) {
        const arrow = document.getElementById('blog-arrow');
        if (arrow) {
            arrow.style.transform = 'rotate(180deg)';
        }
    }
});
</script>
