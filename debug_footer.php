<?php
/**
 * Debug do rodapé - verificar variáveis
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-18
 */

require_once __DIR__ . '/config/config.php';
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/models/Setting.php';

$settingModel = new Setting();

echo "<h1>Debug - Variáveis do Rodapé</h1>";
echo "<pre>";

$contactEmail = $settingModel->get('contact_email');
$whatsappGroupUrl = $settingModel->get('whatsapp_group_url');
$whatsappContactUrl = $settingModel->get('whatsapp_contact_url');

echo "contact_email: ";
var_dump($contactEmail);
echo "\n";

echo "whatsapp_group_url: ";
var_dump($whatsappGroupUrl);
echo "\n";

echo "whatsapp_contact_url: ";
var_dump($whatsappContactUrl);
echo "\n";

echo "\n--- Verificação de exibição ---\n";
echo "E-mail aparece? " . ($contactEmail ? "SIM ✅" : "NÃO ❌") . "\n";
echo "Grupo WhatsApp aparece? " . ($whatsappGroupUrl ? "SIM ✅" : "NÃO ❌") . "\n";
echo "WhatsApp individual aparece? " . ($whatsappContactUrl ? "SIM ✅" : "NÃO ❌") . "\n";

echo "</pre>";
echo "<br><a href='/'>← Voltar</a>";
