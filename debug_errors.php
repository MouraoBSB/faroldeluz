<?php
/**
 * Script de debug para verificar erros
 * Autor: Thiago Mourão
 * Data: 2026-02-17 04:10:00
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔍 Debug de Erros</h2>";
echo "<hr>";

echo "<h3>1. Testando DialogoController</h3>";
try {
    require_once __DIR__ . '/config/config.php';
    require_once __DIR__ . '/core/Controller.php';
    require_once __DIR__ . '/models/Dialogo.php';
    require_once __DIR__ . '/models/Setting.php';
    require_once __DIR__ . '/controllers/DialogoController.php';
    
    echo "✅ DialogoController carregado sem erros<br>";
    
    $controller = new DialogoController();
    echo "✅ DialogoController instanciado com sucesso<br>";
    
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";

echo "<h3>2. Testando MagazineController</h3>";
try {
    require_once __DIR__ . '/models/Magazine.php';
    require_once __DIR__ . '/controllers/MagazineController.php';
    
    echo "✅ MagazineController carregado sem erros<br>";
    
    $controller = new MagazineController();
    echo "✅ MagazineController instanciado com sucesso<br>";
    
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";

echo "<h3>3. Informações do Sistema</h3>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Server: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";

echo "<hr>";
echo "<a href='/'>← Voltar</a>";
