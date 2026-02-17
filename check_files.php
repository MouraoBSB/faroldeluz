<?php
/**
 * Script de diagnóstico para verificar versão dos arquivos
 * Autor: Thiago Mourão
 * Data: 2026-02-17 03:59:00
 */

echo "<h2>Diagnóstico de Arquivos</h2>";

$files = [
    'controllers/DialogoController.php',
    'controllers/MagazineController.php'
];

foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    
    echo "<h3>$file</h3>";
    
    if (file_exists($path)) {
        echo "✅ Arquivo existe<br>";
        echo "📅 Última modificação: " . date("Y-m-d H:i:s", filemtime($path)) . "<br>";
        echo "📏 Tamanho: " . filesize($path) . " bytes<br>";
        
        $content = file_get_contents($path);
        
        if (strpos($content, 'match($order)') !== false) {
            echo "✅ Contém <code>match()</code><br>";
        } elseif (strpos($content, 'switch($order)') !== false) {
            echo "⚠️ Contém <code>switch()</code> (versão antiga)<br>";
        } else {
            echo "❌ Não encontrou nem match() nem switch()<br>";
        }
        
        echo "<details><summary>Ver linhas 20-30</summary><pre>";
        $lines = explode("\n", $content);
        for ($i = 19; $i < 30 && $i < count($lines); $i++) {
            echo htmlspecialchars($lines[$i]) . "\n";
        }
        echo "</pre></details>";
    } else {
        echo "❌ Arquivo não encontrado<br>";
    }
    
    echo "<hr>";
}

echo "<br><strong>Versão PHP:</strong> " . phpversion();
echo "<br><strong>OPcache habilitado:</strong> " . (function_exists('opcache_get_status') ? 'Sim' : 'Não');

if (function_exists('opcache_get_status')) {
    $status = opcache_get_status();
    echo "<br><strong>OPcache ativo:</strong> " . ($status['opcache_enabled'] ? 'Sim' : 'Não');
}

echo "<br><br><a href='clear_cache.php'>🗑️ Limpar Cache</a> | <a href='/'>← Voltar</a>";
