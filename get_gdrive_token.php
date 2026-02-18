<?php
/**
 * Script para obter Refresh Token do Google Drive
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-17
 */

// CREDENCIAIS DO GOOGLE CLOUD CONSOLE
// SUBSTITUA COM SUAS CREDENCIAIS ANTES DE USAR
$clientId = 'SEU_CLIENT_ID_AQUI';
$clientSecret = 'SEU_CLIENT_SECRET_AQUI';
$redirectUri = 'urn:ietf:wg:oauth:2.0:oob'; // Para aplicações desktop

echo "<h1>Obter Refresh Token do Google Drive</h1>";

// Passo 1: Se não tem o código de autorização
if (!isset($_GET['code'])) {
    $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
        'client_id' => $clientId,
        'redirect_uri' => $redirectUri,
        'response_type' => 'code',
        'scope' => 'https://www.googleapis.com/auth/drive.file',
        'access_type' => 'offline',
        'prompt' => 'consent'
    ]);
    
    echo "<h2>Passo 1: Autorizar Aplicação</h2>";
    echo "<p>Clique no link abaixo para autorizar o acesso ao Google Drive:</p>";
    echo "<p><a href='{$authUrl}' target='_blank' style='background: #4285f4; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Autorizar Google Drive</a></p>";
    echo "<hr>";
    echo "<h2>Passo 2: Cole o Código de Autorização</h2>";
    echo "<form method='get'>";
    echo "<input type='text' name='code' placeholder='Cole o código aqui' style='width: 400px; padding: 10px;'><br><br>";
    echo "<button type='submit' style='background: #34a853; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>Obter Refresh Token</button>";
    echo "</form>";
    
} else {
    // Passo 2: Trocar código por tokens
    $code = $_GET['code'];
    
    $ch = curl_init('https://oauth2.googleapis.com/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'code' => $code,
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'redirect_uri' => $redirectUri,
        'grant_type' => 'authorization_code'
    ]));
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $tokens = json_decode($response, true);
    
    if (isset($tokens['refresh_token'])) {
        echo "<h2 style='color: green;'>✅ Sucesso!</h2>";
        echo "<p><strong>Refresh Token:</strong></p>";
        echo "<textarea readonly style='width: 100%; height: 100px; padding: 10px; font-family: monospace;'>{$tokens['refresh_token']}</textarea>";
        echo "<p><strong>Copie o Refresh Token acima e cole nas configurações de Backup no admin!</strong></p>";
        echo "<hr>";
        echo "<h3>Próximos passos:</h3>";
        echo "<ol>";
        echo "<li>Copie o Refresh Token acima</li>";
        echo "<li>Acesse: <a href='https://faroldeluz.ong.br/admin/configuracoes'>Configurações Admin</a></li>";
        echo "<li>Aba: Backup</li>";
        echo "<li>Cole o Refresh Token no campo apropriado</li>";
        echo "<li>Salve as configurações</li>";
        echo "</ol>";
    } else {
        echo "<h2 style='color: red;'>❌ Erro ao obter tokens</h2>";
        echo "<pre>" . print_r($tokens, true) . "</pre>";
    }
}
?>
