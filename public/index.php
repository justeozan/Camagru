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

spl_autoload_register(function ($class) {
    if (file_exists("../core/$class.php")) require_once "../core/$class.php";
    if (file_exists("../app/models/$class.php")) require_once "../app/models/$class.php";
    if (file_exists("../app/controllers/$class.php")) require_once "../app/controllers/$class.php";
});

require_once "../core/Router.php";

// Récupère la vraie URL demandée (ex: /user/login)
$request = $_SERVER['REQUEST_URI'];
$request = parse_url($request, PHP_URL_PATH); // on ignore les ?query=...
$url = explode('/', trim($request, '/'));     // ['user', 'login']

// Appelle le routeur avec ce chemin
Router::route($url);
