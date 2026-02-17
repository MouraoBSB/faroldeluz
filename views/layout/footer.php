    </main>
    
    <footer class="bg-azul-cosmico border-t border-azul-medio mt-20">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-dourado-luz font-bold text-lg mb-4">Farol de Luz</h3>
                    <p class="text-cinza-azulado text-sm mb-4"><?= SITE_SLOGAN ?></p>
                    <img src="<?= asset_url('images/logo.png') ?>" alt="<?= SITE_NAME ?>" class="h-20 w-20">
                </div>
                
                <div>
                    <h3 class="text-dourado-luz font-bold text-lg mb-4">Links Rápidos</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="<?= base_url() ?>" class="text-cinza-azulado hover:text-dourado-luz transition">Início</a></li>
                        <li><a href="<?= base_url('revista') ?>" class="text-cinza-azulado hover:text-dourado-luz transition">Revista</a></li>
                        <li><a href="<?= base_url('dialogos') ?>" class="text-cinza-azulado hover:text-dourado-luz transition">Diálogos do Farol</a></li>
                        <li><a href="<?= base_url('rajian') ?>" class="text-cinza-azulado hover:text-dourado-luz transition">Grupo de Estudos Rajian</a></li>
                        <li><a href="<?= base_url('blog') ?>" class="text-cinza-azulado hover:text-dourado-luz transition">Blog</a></li>
                        <li><a href="<?= base_url('sobre') ?>" class="text-cinza-azulado hover:text-dourado-luz transition">Sobre</a></li>
                        <li><a href="<?= base_url('contato') ?>" class="text-cinza-azulado hover:text-dourado-luz transition">Contato</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-dourado-luz font-bold text-lg mb-4">Newsletter</h3>
                    <p class="text-cinza-azulado text-sm mb-4">Receba a Luz do Consolador no seu e-mail</p>
                    <form id="footer-newsletter-form" class="space-y-2">
                        <input type="text" name="name" placeholder="Nome" required class="w-full px-3 py-2 bg-azul-noite border border-azul-medio rounded text-sm focus:outline-none focus:border-dourado-luz">
                        <input type="email" name="email" placeholder="E-mail" required class="w-full px-3 py-2 bg-azul-noite border border-azul-medio rounded text-sm focus:outline-none focus:border-dourado-luz">
                        <button type="submit" class="w-full bg-dourado-luz text-azul-noite px-4 py-2 rounded hover:bg-dourado-intenso transition font-semibold text-sm">Inscrever</button>
                    </form>
                    <div id="footer-newsletter-message" class="mt-2 text-sm"></div>
                </div>
                
                <div>
                    <h3 class="text-dourado-luz font-bold text-lg mb-4">Contato</h3>
                    <?php 
                    $settingModel = new Setting();
                    $contactEmail = $settingModel->get('contact_email');
                    $contactPhone = $settingModel->get('contact_phone');
                    $whatsappUrl = $settingModel->get('whatsapp_group_url');
                    ?>
                    <ul class="space-y-2 text-sm text-cinza-azulado">
                        <?php if ($contactEmail): ?>
                        <li>
                            <a href="mailto:<?= $contactEmail ?>" class="hover:text-dourado-luz transition">
                                <?= $contactEmail ?>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if ($contactPhone): ?>
                        <li><?= $contactPhone ?></li>
                        <?php endif; ?>
                        <?php if ($whatsappUrl): ?>
                        <li class="mt-4">
                            <a href="<?= $whatsappUrl ?>" target="_blank" class="inline-block bg-azul-turquesa text-white px-4 py-2 rounded hover:bg-opacity-80 transition font-semibold">
                                Grupo WhatsApp
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-azul-medio mt-8 pt-8 text-center text-sm text-cinza-azulado">
                <p>&copy; <?= date('Y') ?> <?= SITE_NAME ?>. Todos os direitos reservados.</p>
                <p class="mt-2">Desenvolvido por <a href="https://www.instagram.com/mouraoeguerin/" target="_blank" class="text-dourado-luz hover:text-dourado-intenso transition">Thiago Mourão</a></p>
            </div>
        </div>
    </footer>
    
    <script src="<?= asset_url('js/main.js') ?>"></script>
</body>
</html>
