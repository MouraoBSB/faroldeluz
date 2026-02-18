<?php
$pageTitle = SITE_NAME . ' - ' . SITE_SLOGAN;
$metaDescription = 'Projeto Espírita Farol de Luz: Revista Espírita, Diálogos do Farol, Grupo de Estudos Rajian e Blog com conteúdos que iluminam reflexões e fortalecem a fé.';
$ogUrl = base_url();

require_once BASE_PATH . '/models/Setting.php';
require_once BASE_PATH . '/views/layout/header.php';
?>

<section class="relative min-h-screen flex items-center justify-center overflow-hidden" id="hero-section">
    <div class="absolute inset-0 bg-cover bg-center bg-fixed opacity-40" style="background-image: url('<?= asset_url('images/background.jpg') ?>');"></div>
    <canvas id="particles-canvas"></canvas>
    
    <div class="relative z-10 container mx-auto px-4 text-center">
        <div class="mb-8 logo-glow">
            <img src="<?= asset_url('images/logo.png') ?>" alt="Farol de Luz" class="mx-auto h-32 w-32 md:h-48 md:w-48 animate-pulse">
        </div>
        
        <h1 class="text-3xl md:text-5xl lg:text-6xl font-bold text-dourado-luz mb-6">
            A Luz do Consolador para os dias de hoje!
        </h1>
        
        <p class="text-xl md:text-2xl text-cinza-azulado mb-8 max-w-3xl mx-auto">
            Conecte-se à espiritualidade e ao estudo transformador. O farol guia embarcações perdidas; o projeto Farol de Luz leva esclarecimento e consolo espiritual.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?= base_url('revista') ?>" class="bg-dourado-luz text-azul-noite px-8 py-4 rounded-lg hover:bg-dourado-intenso transition font-bold text-lg shadow-lg hover:shadow-dourado-luz/50">
                Ver a Revista
            </a>
            <a href="<?= base_url('sobre') ?>" class="bg-azul-turquesa text-white px-8 py-4 rounded-lg hover:bg-opacity-80 transition font-bold text-lg shadow-lg">
                Conhecer o Projeto
            </a>
        </div>
    </div>
</section>

<?php if ($latestMagazine): ?>
<section class="py-20 bg-azul-cosmico">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-dourado-luz mb-4">Revista Espírita Farol de Luz</h2>
            <p class="text-cinza-azulado text-lg">Edições que iluminam reflexões, fortalecem a fé e ampliam o entendimento da vida</p>
        </div>
        
        <div class="max-w-4xl mx-auto bg-azul-noite rounded-2xl overflow-hidden shadow-2xl border border-azul-medio hover:border-dourado-luz transition">
            <div class="grid md:grid-cols-2 gap-8 p-8">
                <div class="flex items-center justify-center">
                    <img src="<?= $latestMagazine['cover_image_url'] ?>" alt="<?= htmlspecialchars($latestMagazine['title']) ?>" class="w-full max-h-[500px] object-contain rounded-lg shadow-lg">
                </div>
                <div class="flex flex-col justify-center">
                    <h3 class="text-2xl font-bold text-dourado-luz mb-4"><?= htmlspecialchars($latestMagazine['title']) ?></h3>
                    <div class="text-cinza-azulado mb-6">
                        <?= substr(strip_tags($latestMagazine['description_html']), 0, 200) ?>...
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="<?= $latestMagazine['pdf_url'] ?>" target="_blank" class="bg-dourado-luz text-azul-noite px-6 py-3 rounded-lg hover:bg-dourado-intenso transition font-semibold text-center">
                            Baixar PDF
                        </a>
                        <a href="<?= base_url('revista/' . $latestMagazine['slug']) ?>" class="bg-azul-medio text-white px-6 py-3 rounded-lg hover:bg-azul-turquesa transition font-semibold text-center">
                            Ver Detalhes
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-8">
            <a href="<?= base_url('revista') ?>" class="text-dourado-luz hover:text-dourado-intenso transition font-semibold">
                Ver todas as edições →
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($dialogos)): ?>
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-dourado-luz mb-4">Diálogos do Farol</h2>
            <p class="text-cinza-azulado text-lg">Conversas que iluminam e transformam</p>
        </div>
        
        <div id="dialogos-carousel" class="relative">
            <div class="overflow-hidden">
                <div class="flex transition-transform duration-500" id="dialogos-track">
                    <?php foreach ($dialogos as $dialogo): ?>
                    <div class="min-w-full md:min-w-[50%] lg:min-w-[33.333%] px-4">
                        <div class="bg-azul-cosmico rounded-xl overflow-hidden shadow-lg border border-azul-medio hover:border-dourado-luz transition h-full">
                            <div class="aspect-video bg-azul-noite">
                                <img src="https://img.youtube.com/vi/<?= $dialogo['youtube_video_id'] ?>/hqdefault.jpg" 
                                     alt="<?= htmlspecialchars($dialogo['title']) ?>" 
                                     class="w-full h-full object-cover"
                                     onerror="this.src='https://img.youtube.com/vi/<?= $dialogo['youtube_video_id'] ?>/mqdefault.jpg'">
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-dourado-luz mb-3"><?= htmlspecialchars($dialogo['title']) ?></h3>
                                <a href="<?= base_url('dialogos/' . $dialogo['slug']) ?>" class="inline-block bg-azul-turquesa text-white px-4 py-2 rounded hover:bg-opacity-80 transition font-semibold">
                                    Assistir
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <button id="dialogos-prev" class="absolute left-0 top-1/2 -translate-y-1/2 bg-dourado-luz text-azul-noite p-3 rounded-r-lg hover:bg-dourado-intenso transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button id="dialogos-next" class="absolute right-0 top-1/2 -translate-y-1/2 bg-dourado-luz text-azul-noite p-3 rounded-l-lg hover:bg-dourado-intenso transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>
        
        <div class="text-center mt-8">
            <a href="<?= base_url('dialogos') ?>" class="text-dourado-luz hover:text-dourado-intenso transition font-semibold">
                Ver todos os diálogos →
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($rajianStudies)): ?>
<section class="py-20 bg-azul-cosmico">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-dourado-luz mb-4">Grupo de Estudos Rajian</h2>
            <p class="text-cinza-azulado text-lg">Aprofunde seu conhecimento espiritual</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($rajianStudies as $study): ?>
            <div class="bg-azul-noite rounded-xl overflow-hidden shadow-lg border border-azul-medio hover:border-dourado-luz transition">
                <div class="aspect-video relative overflow-hidden">
                    <?php if (!empty($study['youtube_video_id'])): ?>
                        <img src="https://img.youtube.com/vi/<?= htmlspecialchars($study['youtube_video_id']) ?>/hqdefault.jpg" 
                             alt="<?= htmlspecialchars($study['title']) ?>" 
                             class="w-full h-full object-cover"
                             loading="lazy">
                    <?php elseif (!empty($study['cover_image_url'])): ?>
                        <img src="<?= base_url($study['cover_image_url']) ?>" 
                             alt="<?= htmlspecialchars($study['title']) ?>" 
                             class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full bg-azul-medio flex items-center justify-center">
                            <span class="text-4xl">📚</span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-dourado-luz mb-3"><?= htmlspecialchars($study['title']) ?></h3>
                    <p class="text-sm text-cinza-azulado mb-4"><?= date('d/m/Y', strtotime($study['published_at'])) ?></p>
                    <a href="<?= base_url('rajian/' . $study['slug']) ?>" class="inline-block bg-azul-turquesa text-white px-4 py-2 rounded hover:bg-opacity-80 transition font-semibold">
                        Ver Estudo
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-8">
            <a href="<?= base_url('rajian') ?>" class="text-dourado-luz hover:text-dourado-intenso transition font-semibold">
                Ver todos os estudos →
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($blogPosts)): ?>
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-dourado-luz mb-4">Blog Farol de Luz</h2>
            <p class="text-cinza-azulado text-lg">Reflexões e ensinamentos para o dia a dia</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($blogPosts as $post): ?>
            <article class="bg-azul-cosmico rounded-xl overflow-hidden shadow-lg border border-azul-medio hover:border-dourado-luz transition">
                <?php if ($post['featured_image_url']): ?>
                <div class="aspect-video">
                    <img src="<?= $post['featured_image_url'] ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="w-full h-full object-cover">
                </div>
                <?php endif; ?>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-dourado-luz mb-3"><?= htmlspecialchars($post['title']) ?></h3>
                    <p class="text-cinza-azulado mb-4"><?= htmlspecialchars($post['excerpt']) ?></p>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-cinza-azulado"><?= date('d/m/Y', strtotime($post['published_at'])) ?></span>
                        <a href="<?= base_url('blog/' . $post['slug']) ?>" class="text-dourado-luz hover:text-dourado-intenso transition font-semibold">
                            Ler mais →
                        </a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-8">
            <a href="<?= base_url('blog') ?>" class="text-dourado-luz hover:text-dourado-intenso transition font-semibold">
                Ir para o Blog →
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="py-20 bg-azul-cosmico">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-dourado-luz mb-4">Receba a Luz do Consolador no seu e-mail</h2>
            <p class="text-cinza-azulado text-lg mb-8">Inscreva-se em nossa newsletter e receba conteúdos exclusivos</p>
            
            <form id="newsletter-form" class="space-y-4">
                <input type="text" name="name" placeholder="Seu nome" required class="w-full px-6 py-4 bg-azul-noite border border-azul-medio rounded-lg focus:outline-none focus:border-dourado-luz text-white">
                <input type="email" name="email" placeholder="Seu e-mail" required class="w-full px-6 py-4 bg-azul-noite border border-azul-medio rounded-lg focus:outline-none focus:border-dourado-luz text-white">
                <button type="submit" class="w-full bg-dourado-luz text-azul-noite px-8 py-4 rounded-lg hover:bg-dourado-intenso transition font-bold text-lg">
                    Inscrever-se
                </button>
            </form>
            <div id="newsletter-message" class="mt-4 text-lg"></div>
        </div>
    </div>
</section>

<?php require_once BASE_PATH . '/views/layout/footer.php'; ?>
