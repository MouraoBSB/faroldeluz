<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Setting.php';

$setting = new Setting();
$imagemDestaque = $setting->get('revista_imagem_destaque');

echo "Valor no banco: " . ($imagemDestaque ?: 'VAZIO') . "\n";

if ($imagemDestaque && file_exists(BASE_PATH . '/' . $imagemDestaque)) {
    echo "✅ Arquivo existe: " . BASE_PATH . '/' . $imagemDestaque . "\n";
} else {
    echo "❌ Arquivo NÃO existe\n";
}

$uploadDir = BASE_PATH . '/assets/uploads/settings/';
if (is_dir($uploadDir)) {
    echo "\nArquivos na pasta settings:\n";
    $files = scandir($uploadDir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "  - $file\n";
        }
    }
} else {
    echo "❌ Pasta settings não existe\n";
}
