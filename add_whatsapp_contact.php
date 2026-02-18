<?php
/**
 * Script para adicionar whatsapp_contact_url nas configurações
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
    
    echo "<h1>Adicionando whatsapp_contact_url</h1>";
    
    // Verificar se já existe
    $stmt = $db->prepare("SELECT * FROM settings WHERE setting_key = 'whatsapp_contact_url'");
    $stmt->execute();
    $exists = $stmt->fetch();
    
    if ($exists) {
        echo "<p>⚠️ whatsapp_contact_url já existe no banco de dados.</p>";
        echo "<p>Valor atual: " . ($exists['setting_value'] ?: 'NULL') . "</p>";
    } else {
        // Inserir com valor vazio
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('whatsapp_contact_url', '')");
        $stmt->execute();
        echo "<p>✅ whatsapp_contact_url adicionado com sucesso!</p>";
    }
    
    echo "<br><p><strong>Próximo passo:</strong></p>";
    echo "<ol>";
    echo "<li>Vá em <strong>Admin → Configurações → WhatsApp</strong></li>";
    echo "<li>Preencha o campo <strong>\"Link de Contato (WhatsApp)\"</strong></li>";
    echo "<li>Salve as configurações</li>";
    echo "<li>O terceiro botão deve aparecer no rodapé</li>";
    echo "</ol>";
    
    echo "<br><a href='/admin/configuracoes'>→ Ir para Configurações</a>";
    echo "<br><a href='/'>← Voltar para o site</a>";
    
} catch (PDOException $e) {
    echo "<h2>❌ Erro</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
