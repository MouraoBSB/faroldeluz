<?php
/**
 * Script para limpar cache do OPcache
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-18
 */

if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "✅ OPcache limpo com sucesso!<br>";
} else {
    echo "ℹ️ OPcache não está habilitado.<br>";
}

if (function_exists('apc_clear_cache')) {
    apc_clear_cache();
    echo "✅ APC cache limpo com sucesso!<br>";
} else {
    echo "ℹ️ APC não está habilitado.<br>";
}

echo "<br>✨ Cache do servidor limpo!<br>";
echo "<br><a href='/'>← Voltar para o site</a>";
