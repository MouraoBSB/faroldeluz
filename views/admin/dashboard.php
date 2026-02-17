<?php
/**
 * Dashboard Admin
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-17 01:11:00
 */

$pageTitle = 'Dashboard - Admin';
require_once BASE_PATH . '/views/admin/layout/header.php';
?>

<div class="flex h-screen bg-azul-noite">
    <?php require_once BASE_PATH . '/views/admin/layout/sidebar.php'; ?>
    
    <div class="flex-1 overflow-auto">
        <div class="p-8">
            <h1 class="text-3xl font-bold text-dourado-luz mb-8">Dashboard</h1>
            
            <!-- Cards de Estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-azul-cosmico rounded-lg border border-azul-medio p-6">
                    <h3 class="text-cinza-azulado text-sm font-semibold mb-2">Revistas</h3>
                    <p class="text-4xl font-bold text-dourado-luz"><?= $stats['magazines_total'] ?></p>
                </div>
                <div class="bg-azul-cosmico rounded-lg border border-azul-medio p-6">
                    <h3 class="text-cinza-azulado text-sm font-semibold mb-2">Diálogos</h3>
                    <p class="text-4xl font-bold text-dourado-luz"><?= $stats['dialogos_total'] ?></p>
                </div>
                <div class="bg-azul-cosmico rounded-lg border border-azul-medio p-6">
                    <h3 class="text-cinza-azulado text-sm font-semibold mb-2">Estudos Rajian</h3>
                    <p class="text-4xl font-bold text-dourado-luz"><?= $stats['rajian_total'] ?></p>
                </div>
                <div class="bg-azul-cosmico rounded-lg border border-azul-medio p-6">
                    <h3 class="text-cinza-azulado text-sm font-semibold mb-2">Posts do Blog</h3>
                    <p class="text-4xl font-bold text-dourado-luz"><?= $stats['blog_total'] ?></p>
                </div>
                <div class="bg-azul-cosmico rounded-lg border border-azul-medio p-6">
                    <h3 class="text-cinza-azulado text-sm font-semibold mb-2">Assinantes Newsletter</h3>
                    <p class="text-4xl font-bold text-dourado-luz"><?= $stats['newsletter_total'] ?></p>
                </div>
            </div>
            
            <!-- Acesso Rápido -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-azul-cosmico rounded-lg border border-azul-medio p-6">
                    <h3 class="text-xl font-bold text-dourado-luz mb-4">Gerenciar Conteúdo</h3>
                    <ul class="space-y-3">
                        <li><a href="<?= base_url('admin/revistas') ?>" class="text-azul-turquesa hover:text-dourado-luz transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Revistas
                        </a></li>
                        <li><a href="<?= base_url('admin/dialogos') ?>" class="text-azul-turquesa hover:text-dourado-luz transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            Diálogos do Farol
                        </a></li>
                        <li><a href="<?= base_url('admin/rajian') ?>" class="text-azul-turquesa hover:text-dourado-luz transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            Grupo de Estudos Rajian
                        </a></li>
                        <li><a href="<?= base_url('admin/blog') ?>" class="text-azul-turquesa hover:text-dourado-luz transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Blog
                        </a></li>
                        <li><a href="<?= base_url('admin/taxonomias') ?>" class="text-azul-turquesa hover:text-dourado-luz transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            Categorias e Tags
                        </a></li>
                    </ul>
                </div>
                
                <div class="bg-azul-cosmico rounded-lg border border-azul-medio p-6">
                    <h3 class="text-xl font-bold text-dourado-luz mb-4">Configurações</h3>
                    <ul class="space-y-3">
                        <li><a href="<?= base_url('admin/newsletter') ?>" class="text-azul-turquesa hover:text-dourado-luz transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            Newsletter
                        </a></li>
                        <li><a href="<?= base_url('admin/configuracoes') ?>" class="text-azul-turquesa hover:text-dourado-luz transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Configurações do Site
                        </a></li>
                        <li><a href="<?= base_url() ?>" target="_blank" class="text-azul-turquesa hover:text-dourado-luz transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            Ver Site
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/views/admin/layout/footer.php'; ?>
