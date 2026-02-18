<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Teste de Conexão SMTP - Debug</h2>";

$host = 'pro115.dnspro.com.br';
$port = 465;

echo "<p><strong>Testando conexão SSL com {$host}:{$port}...</strong></p>";

$context = stream_context_create([
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    ]
]);

$socket = @stream_socket_client(
    "ssl://{$host}:{$port}",
    $errno,
    $errstr,
    10,
    STREAM_CLIENT_CONNECT,
    $context
);

if (!$socket) {
    echo "<p style='color: red;'>❌ Erro ao conectar: {$errstr} ({$errno})</p>";
    echo "<p>Tentando porta 587 com TLS...</p>";
    
    // Tentar porta 587
    $socket = @stream_socket_client(
        "{$host}:587",
        $errno,
        $errstr,
        10,
        STREAM_CLIENT_CONNECT
    );
    
    if (!$socket) {
        echo "<p style='color: red;'>❌ Porta 587 também falhou: {$errstr} ({$errno})</p>";
        exit;
    }
    
    echo "<p style='color: green;'>✅ Conectado na porta 587!</p>";
} else {
    echo "<p style='color: green;'>✅ Conectado na porta 465 SSL!</p>";
}

// Ler resposta inicial
$response = fgets($socket, 515);
echo "<p><strong>Resposta do servidor:</strong><br><code>{$response}</code></p>";

// Enviar EHLO
fputs($socket, "EHLO {$host}\r\n");
echo "<p><strong>Enviado:</strong> EHLO {$host}</p>";

$response = '';
while ($line = fgets($socket, 515)) {
    $response .= $line;
    if (substr($line, 3, 1) == ' ') break;
}
echo "<p><strong>Resposta EHLO:</strong><br><code>" . nl2br(htmlspecialchars($response)) . "</code></p>";

fclose($socket);

echo "<hr>";
echo "<h3>Configurações Atuais:</h3>";
echo "<ul>";
echo "<li>Host: {$host}</li>";
echo "<li>Porta testada: 465 (SSL) e 587 (TLS)</li>";
echo "<li>Usuário: contato@faroldeluz.ong.br</li>";
echo "</ul>";

echo "<p><strong>Conclusão:</strong> Se conectou com sucesso, o problema pode ser:</p>";
echo "<ul>";
echo "<li>Senha incorreta</li>";
echo "<li>Autenticação SMTP não configurada corretamente</li>";
echo "<li>Gmail bloqueando emails do domínio (falta SPF/DKIM)</li>";
echo "</ul>";
