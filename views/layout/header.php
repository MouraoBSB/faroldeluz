<?php
require_once BASE_PATH . '/models/Setting.php';
$settingModel = new Setting();
$siteFavicon = $settingModel->get('site_favicon');
$siteOgTitle = $settingModel->get('site_og_title');
$siteMetaDescription = $settingModel->get('site_meta_description');
$siteOgImage = $settingModel->get('site_og_image');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <title><?= $pageTitle ?? SITE_NAME ?></title>
    <meta name="description" content="<?= strip_tags($metaDescription ?? $siteMetaDescription ?? SITE_SLOGAN) ?>">
    <?php if (isset($metaKeywords)): ?>
    <meta name="keywords" content="<?= $metaKeywords ?>">
    <?php endif; ?>
    
    <?php if (isset($canonical)): ?>
    <link rel="canonical" href="<?= $canonical ?>">
    <?php endif; ?>
    
    <meta property="og:title" content="<?= $ogTitle ?? $siteOgTitle ?? $pageTitle ?? SITE_NAME ?>">
    <meta property="og:description" content="<?= strip_tags($ogDescription ?? $siteMetaDescription ?? $metaDescription ?? SITE_SLOGAN) ?>">
    <meta property="og:image" content="<?= $ogImage ?? ($siteOgImage ? base_url($siteOgImage) : asset_url('images/logo.png')) ?>">
    <meta property="og:url" content="<?= $ogUrl ?? base_url() ?>">
    <meta property="og:type" content="<?= $ogType ?? 'website' ?>">
    <meta property="og:site_name" content="<?= SITE_NAME ?>">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $ogTitle ?? $siteOgTitle ?? $pageTitle ?? SITE_NAME ?>">
    <meta name="twitter:description" content="<?= strip_tags($ogDescription ?? $siteMetaDescription ?? $metaDescription ?? SITE_SLOGAN) ?>">
    <meta name="twitter:image" content="<?= $ogImage ?? ($siteOgImage ? base_url($siteOgImage) : asset_url('images/logo.png')) ?>">
    
    <?php if ($siteFavicon): ?>
    <link rel="icon" href="<?= base_url($siteFavicon) ?>">
    <?php else: ?>
    <link rel="icon" type="image/png" href="<?= asset_url('images/logo.png') ?>">
    <?php endif; ?>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'azul-noite': '#0B1020',
                        'azul-cosmico': '#111C3A',
                        'dourado-luz': '#F2C97D',
                        'dourado-intenso': '#E6A94A',
                        'azul-turquesa': '#2FA4A9',
                        'azul-medio': '#1E6F8C',
                        'cinza-azulado': '#9AA4B2'
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="<?= asset_url('css/style.css') ?>">
    
    <?php if (isset($schemaMarkup)): ?>
    <script type="application/ld+json">
    <?= json_encode($schemaMarkup, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
    </script>
    <?php endif; ?>
</head>
<body class="bg-azul-noite text-white antialiased">
    <header class="fixed top-0 left-0 right-0 z-50 bg-azul-noite/90 backdrop-blur-sm border-b border-azul-cosmico">
        <nav class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <a href="<?= base_url() ?>" class="flex items-center space-x-3">
                    <img src="<?= asset_url('images/logo.png') ?>" alt="<?= SITE_NAME ?>" class="h-12 w-12">
                    <div>
                        <div class="text-xl font-bold text-dourado-luz">Farol de Luz</div>
                        <div class="text-xs text-cinza-azulado">A Luz do Consolador</div>
                    </div>
                </a>
                
                <button id="mobile-menu-toggle" class="lg:hidden text-dourado-luz">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
                <ul class="hidden lg:flex items-center space-x-6">
                    <li><a href="<?= base_url() ?>" class="hover:text-dourado-luz transition">Início</a></li>
                    <li><a href="<?= base_url('revista') ?>" class="hover:text-dourado-luz transition">Revista</a></li>
                    <li><a href="<?= base_url('dialogos') ?>" class="hover:text-dourado-luz transition">Diálogos do Farol</a></li>
                    <li><a href="<?= base_url('rajian') ?>" class="hover:text-dourado-luz transition">Grupo Rajian</a></li>
                    <li><a href="<?= base_url('blog') ?>" class="hover:text-dourado-luz transition">Blog</a></li>
                    <li class="relative group">
                        <button class="hover:text-dourado-luz transition flex items-center gap-1">
                            Sobre
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <ul class="absolute left-0 top-full hidden group-hover:block bg-azul-cosmico rounded-lg shadow-lg border border-azul-medio py-2 w-48 z-50">
                            <li><a href="<?= base_url('sobre') ?>" class="block px-4 py-2 hover:bg-azul-medio hover:text-dourado-luz transition">O Projeto</a></li>
                            <li><a href="<?= base_url('sobre/batuira') ?>" class="block px-4 py-2 hover:bg-azul-medio hover:text-dourado-luz transition">Guia Batuíra</a></li>
                        </ul>
                    </li>
                    <li><a href="<?= base_url('contato') ?>" class="bg-dourado-luz text-azul-noite px-4 py-2 rounded-lg hover:bg-dourado-intenso transition font-semibold">Contato</a></li>
                </ul>
            </div>
            
            <div id="mobile-menu" class="hidden lg:hidden mt-4 pb-4">
                <ul class="space-y-3">
                    <li><a href="<?= base_url() ?>" class="block hover:text-dourado-luz transition">Início</a></li>
                    <li><a href="<?= base_url('revista') ?>" class="block hover:text-dourado-luz transition">Revista</a></li>
                    <li><a href="<?= base_url('dialogos') ?>" class="block hover:text-dourado-luz transition">Diálogos do Farol</a></li>
                    <li><a href="<?= base_url('rajian') ?>" class="block hover:text-dourado-luz transition">Grupo Rajian</a></li>
                    <li><a href="<?= base_url('blog') ?>" class="block hover:text-dourado-luz transition">Blog</a></li>
                    <li><a href="<?= base_url('sobre') ?>" class="block hover:text-dourado-luz transition">O Projeto</a></li>
                    <li><a href="<?= base_url('sobre/batuira') ?>" class="block hover:text-dourado-luz transition">Guia Batuíra</a></li>
                    <li><a href="<?= base_url('contato') ?>" class="block bg-dourado-luz text-azul-noite px-4 py-2 rounded-lg hover:bg-dourado-intenso transition font-semibold text-center">Contato</a></li>
                </ul>
            </div>
        </nav>
    </header>
    
    <main class="pt-20">
