<?php
/**
 * Router
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 */

class Router {
    private $routes = [];
    
    public function get($path, $handler) {
        $this->addRoute('GET', $path, $handler);
    }
    
    public function post($path, $handler) {
        $this->addRoute('POST', $path, $handler);
    }
    
    private function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }
    
    public function dispatch($url) {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = '/' . trim($url, '/');
        if ($url !== '/') {
            $url = rtrim($url, '/');
        }
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            $routePath = '/' . trim($route['path'], '/');
            if ($routePath !== '/') {
                $routePath = rtrim($routePath, '/');
            }
            
            if (strpos($route['path'], '{') === false) {
                if ($url === $routePath) {
                    $this->callHandler($route['handler'], []);
                    return;
                }
            } else {
                $pattern = $this->convertToRegex($route['path']);
                if (preg_match($pattern, $url, $matches)) {
                    array_shift($matches);
                    $this->callHandler($route['handler'], $matches);
                    return;
                }
            }
        }
        
        http_response_code(404);
        require_once BASE_PATH . '/views/errors/404.php';
    }
    
    private function convertToRegex($path) {
        $path = '/' . trim($path, '/');
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $path);
        return '#^' . $pattern . '$#';
    }
    
    private function callHandler($handler, $params) {
        list($controllerName, $method) = explode('@', $handler);
        
        $controllerFile = BASE_PATH . '/controllers/' . $controllerName . '.php';
        
        if (!file_exists($controllerFile)) {
            throw new Exception("Controller não encontrado: {$controllerName} em {$controllerFile}");
        }
        
        require_once $controllerFile;
        
        $controllerClass = str_replace('/', '', $controllerName);
        $controllerClass = str_replace('Admin', 'Admin', $controllerClass);
        
        if (!class_exists($controllerClass)) {
            throw new Exception("Classe não encontrada: {$controllerClass}");
        }
        
        $controller = new $controllerClass();
        
        if (!method_exists($controller, $method)) {
            throw new Exception("Método não encontrado: {$method}");
        }
        
        call_user_func_array([$controller, $method], $params);
    }
}
