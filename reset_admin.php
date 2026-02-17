<?php
/**
 * Script para resetar senha do admin
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:38:00
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$db = Database::getInstance()->getConnection();

$email = 'admin@faroldeluz.com.br';
$senha = 'admin123';
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

$stmt = $db->prepare("UPDATE users_admin SET password = ? WHERE email = ?");
$result = $stmt->execute([$senhaHash, $email]);

if ($result) {
    echo "✓ Senha do admin resetada com sucesso!\n\n";
    echo "E-mail: {$email}\n";
    echo "Senha: {$senha}\n";
} else {
    echo "✗ Erro ao resetar senha\n";
}

$stmt = $db->prepare("SELECT id, name, email, status FROM users_admin WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user) {
    echo "\nUsuário encontrado:\n";
    print_r($user);
} else {
    echo "\n✗ Usuário não encontrado no banco de dados!\n";
    echo "Criando usuário...\n";
    
    $stmt = $db->prepare("INSERT INTO users_admin (name, email, password, status) VALUES (?, ?, ?, ?)");
    $stmt->execute(['Administrador', $email, $senhaHash, 'active']);
    
    echo "✓ Usuário criado com sucesso!\n";
}
