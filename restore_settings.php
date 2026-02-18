<?php
/**
 * Script para restaurar configurações
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-18
 */

require_once __DIR__ . '/config/config.php';

try {
    $db = new PDO(
        "mysql:host=" . DB_CONFIG['host'] . ";port=" . DB_CONFIG['port'] . ";dbname=" . DB_CONFIG['database'] . ";charset=" . DB_CONFIG['charset'],
        DB_CONFIG['username'],
        DB_CONFIG['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "<h1>Restaurando Configurações</h1>";
    
    // Configurações da Revista
    $settings = [
        'revista_titulo' => 'Revista Espírita Farol de Luz: A Oficina do Pensamento',
        'revista_descricao' => '<p>A <strong>Revista Espírita Farol de Luz</strong> é o braço editorial do Projeto Farol de Luz. Mais do que uma simples publicação, ela funciona como uma verdadeira <em>oficina do pensamento</em>, onde o conhecimento espírita é trabalhado com profundidade, carinho e fidelidade doutrinária.</p>

<p>Cada edição é cuidadosamente preparada para oferecer aos leitores reflexões profundas sobre temas espíritas, sempre com o objetivo de iluminar mentes, fortalecer a fé e ampliar o entendimento sobre a vida, a morte, a reencarnação e a evolução espiritual.</p>

<h3>O que você encontra em nossas edições?</h3>

<p>Nossas edições abordam temas variados e atuais do Espiritismo, sempre com base nas obras de <strong>Allan Kardec</strong> e nos ensinamentos dos grandes espíritas consagrados como <strong>Bezerra de Menezes, Emmanuel, André Luiz</strong> e muitos outros. Você encontrará:</p>

<ul>
<li><strong>Estudos Doutrinários:</strong> Aprofundamento em temas fundamentais do Espiritismo.</li>
<li><strong>Reflexões sobre Atualidade:</strong> Análises sobre questões contemporâneas à luz da Doutrina Espírita.</li>
<li><strong>Mensagens de Amor e Consolo:</strong> Textos que aquecem o coração e fortalecem o espírito.</li>
<li><strong>Orientações Práticas:</strong> Conselhos para aplicar os ensinamentos espíritas no dia a dia.</li>
<li><strong>Entrevistas e Depoimentos:</strong> Experiências reais que inspiram e edificam.</li>
</ul>

<h3>Leitura Gratuita e Sustentabilidade</h3>

<p>Acreditamos que o conhecimento espírita deve ser acessível a todos. Por isso, disponibilizamos nossa revista <strong>gratuitamente em formato digital (PDF)</strong>, para que você possa ler em seu computador, tablet ou celular, em qualquer lugar do mundo.</p>

<p>Além da versão digital, o Projeto Farol de Luz produz <strong>edições impressas especiais</strong>. Ao adquirir uma versão física, você não apenas leva para casa um material de alta qualidade, mas também <strong>contribui diretamente para a manutenção de toda a nossa oficina</strong> e para a produção de nossos conteúdos audiovisuais.</p>',
        'revista_texto_adicional' => 'Explore nosso acervo e baixe gratuitamente as edições disponíveis. Que estas páginas sejam luz em sua jornada de renovação íntima.'
    ];
    
    foreach ($settings as $key => $value) {
        $stmt = $db->prepare("
            INSERT INTO settings (setting_key, setting_value) 
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE setting_value = ?
        ");
        $stmt->execute([$key, $value, $value]);
        echo "<p>✅ Restaurado: <strong>{$key}</strong></p>";
    }
    
    echo "<br><h2>✅ Configurações restauradas com sucesso!</h2>";
    echo "<p><a href='/revista'>Ver página de Revistas</a></p>";
    echo "<p><a href='/admin/configuracoes'>Ver Configurações no Admin</a></p>";
    
} catch (PDOException $e) {
    echo "<h2>❌ Erro ao restaurar configurações</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
