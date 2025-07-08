<?php

class ErrorController extends Controller
{
    // Afficher la page 404
    public function notFound($message = null)
    {
        // Définir le code de statut HTTP 404
        http_response_code(404);
        
        // Message d'erreur optionnel (pour les logs)
        if ($message) {
            error_log("404 Error: " . $message);
        }
        
        // Afficher la page 404
        require_once "../app/views/404.php";
        exit; // Empêcher l'exécution de code supplémentaire
    }
    
    // Méthode par défaut (si /error est appelé)
    public function index()
    {
        $this->notFound("Page d'erreur générique appelée");
    }
    
    // Gérer les erreurs de méthode non trouvée
    public function methodNotFound($controller, $method)
    {
        $message = "Méthode '$method' introuvable dans le contrôleur '$controller'";
        $this->notFound($message);
    }
    
    // Gérer les erreurs de contrôleur non trouvé
    public function controllerNotFound($controller)
    {
        $message = "Contrôleur '$controller' introuvable";
        $this->notFound($message);
    }
    
    // Erreur générale (500, etc.)
    public function serverError($message = "Erreur interne du serveur")
    {
        http_response_code(500);
        error_log("500 Error: " . $message);
        
        // Pour l'instant, rediriger vers 404 - on pourrait créer une page 500 séparée
        $this->notFound("Erreur serveur: " . $message);
    }
} 