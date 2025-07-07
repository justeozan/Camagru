<?php
require_once "../core/Router.php";

// Récupère la vraie URL demandée (ex: /user/login)
$request = $_SERVER['REQUEST_URI'];
$request = parse_url($request, PHP_URL_PATH); // on ignore les ?query=...
$url = explode('/', trim($request, '/'));     // ['user', 'login']

// Appelle le routeur avec ce chemin
Router::route($url);
