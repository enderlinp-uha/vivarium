<?php

declare(strict_types=1);

// Instanciation d'un nouveau routeur
$router = new \App\Core\Router();

// Chargement des routes
$router->load_routes();

// Récupération de la requête
$path = $router->get_request_path();

// Dispatch de la requête
$router->dispatch($path);
