<?php

// Chargement manuel des variables d'environnement depuis .env
if (file_exists(__DIR__ . '/../.env')) {
    foreach (explode("\n", file_get_contents(__DIR__ . '/../.env')) as $line) {
        if (trim($line) && strpos($line, '=') !== false) {
            $parts = explode('=', trim($line), 2);
            $_ENV[$parts[0]] = $parts[1];
        }
    }
}

require_once "../core/Router.php";

// Récupère la vraie URL demandée (ex: /user/login)
$request = $_SERVER['REQUEST_URI'];
$request = parse_url($request, PHP_URL_PATH); // on ignore les ?query=...
$url = explode('/', trim($request, '/'));     // ['user', 'login']

// Appelle le routeur avec ce chemin
Router::route($url);
