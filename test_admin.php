<?php
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "QUERY_STRING: " . ($_SERVER['QUERY_STRING'] ?? 'vazio') . "\n";
echo "_GET['url']: " . ($_GET['url'] ?? 'não definido') . "\n";

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
echo "URI processada: " . $uri . "\n";
