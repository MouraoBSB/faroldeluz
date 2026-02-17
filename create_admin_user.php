<?php
/**
 * Criar novo usuário administrador
 * Autor: Thiago Mourão
 * Data: 2026-02-17 10:38:00
 */

require_once __DIR__ . '/config/config.php';

// Dados do novo administrador
$name = 'Administrador 2';
$email = 'admin2@faroldeluz.ong.br';
$password = 'Farol@2026'; // Senha temporária - ALTERAR após primeiro login

try {
    $db = new PDO(
        "mysql:host=" . DB_CONFIG['host'] . ";port=" . DB_CONFIG['port'] . ";dbname=" . DB_CONFIG['database'] . ";charset=" . DB_CONFIG['charset'],
        DB_CONFIG['username'],
        DB_CONFIG['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "<h2>Criando novo usuário administrador</h2>";
    echo "<hr>";
    
    // Verificar se usuário já existe
    $stmt = $db->prepare("SELECT id FROM users_admin WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        echo "<h3 style='color: orange;'>⚠️ Usuário já existe!</h3>";
        echo "<p>Email: <strong>{$email}</strong></p>";
        echo "<br>";
        echo "<a href='/admin'>← Ir para Admin</a>";
        exit;
    }
    
    // Hash da senha
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    // Inserir novo usuário
    $sql = "INSERT INTO users_admin (name, email, password, status, created_at, updated_at) 
            VALUES (?, ?, ?, 'active', NOW(), NOW())";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([$name, $email, $passwordHash]);
    
    echo "<h3 style='color: green;'>✅ Usuário administrador criado com sucesso!</h3>";
    echo "<hr>";
    echo "<h4>Credenciais de acesso:</h4>";
    echo "<p><strong>Nome:</strong> {$name}</p>";
    echo "<p><strong>Email:</strong> {$email}</p>";
    echo "<p><strong>Senha:</strong> {$password}</p>";
    echo "<hr>";
    echo "<p style='color: red;'><strong>⚠️ IMPORTANTE:</strong> Altere a senha após o primeiro login!</p>";
    echo "<br>";
    echo "<a href='/admin'>← Ir para Admin</a>";
    echo "<br><br>";
    echo "<p style='color: gray; font-size: 12px;'>Por segurança, delete este arquivo (create_admin_user.php) após criar o usuário.</p>";
    
} catch (PDOException $e) {
    echo "<h3 style='color: red;'>❌ Erro ao criar usuário:</h3>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
