<?php

declare(strict_types=1);

namespace App\Core;

class Router 
{
    private $routes = [];

    /**
     * Fonction permettant d'ajouter une route
     *
     * @param string $method
     * @param string $path
     * @param array $handler
     * @return self
     */
    public function add(string $method, string $path, array $handler): self
    {
        $method = strtoupper($method);

        if (! isset($this->routes[$path])) {
            $this->routes[$path] = [];
        }

        $this->routes[$path][$method] = $handler;

        return $this;
    }

    /**
     * Fonction permettant de dispatcher une requête
     *
     * @param string $path
     * @return void
     */
    public function dispatch(string $path): void
    {
        $request_method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        foreach ($this->routes as $route => $methods)
        {
            $pattern = preg_replace('#\{\w+\}#', '([^\/]+)', $route);
            
            if (preg_match("#^$pattern$#", $path, $matches))
            {
                if (! isset($methods[$request_method])) {
                    error_page(405);
                }

                [$controller, $method] = $methods[$request_method];

                if (! class_exists($controller)) {
                    error_page(500);
                }

                $instance = new $controller();

                if (! method_exists($instance, $method)) {
                    error_page(500);
                }

                array_shift($matches);
                call_user_func_array([$instance, $method], $matches);  
                
                return;
            }
        }
        
        error_404();
    }

    /**
     * Fonction permettant de retourner le chemin de la requête en supprimant le '/' final
     *
     * @return string
     */
    public function get_request_path(): string 
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        return rtrim($path, '/') ?: '/';
    }

    /**
     * Fonction permettant de charger les routes de l'application
     *
     * @return void
     */
    public function load_routes(): void
    {
        $routes = require(APPPATH . 'Config/Routes.php');

        foreach ($routes as [$method, $path, $handler]) 
        {
            $this->add($method, $path, $handler);
        }
    }
}
