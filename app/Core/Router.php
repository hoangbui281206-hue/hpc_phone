<?php
namespace App\Core;

class Router
{
    private array $routes = ['GET' => [], 'POST' => []];

    public function get(string $path, string $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, string $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $uri = rtrim($uri, '/') ?: '/';
        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $pattern = preg_replace('#\{[a-zA-Z_][a-zA-Z0-9_]*\}#', '([0-9]+)', $route);
            if (preg_match('#^' . $pattern . '$#', $uri, $matches)) {
                array_shift($matches);
                [$controllerName, $action] = explode('@', $handler);
                $class = 'App\\Controllers\\' . $controllerName;
                if (!class_exists($class)) {
                    http_response_code(500);
                    exit('Controller không tồn tại.');
                }
                (new $class())->$action(...$matches);
                return;
            }
        }
        http_response_code(404);
        echo '404 - Không tìm thấy trang.';
    }
}
