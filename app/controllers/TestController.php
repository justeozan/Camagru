<?php

class TestController extends Controller
{
    public function pagination()
    {
        echo "<h1>🔍 Test de Pagination</h1>";
        echo "<h2>URL complète:</h2>";
        echo "<pre>" . $_SERVER['REQUEST_URI'] . "</pre>";
        
        echo "<h2>Paramètres GET reçus:</h2>";
        echo "<pre>" . print_r($_GET, true) . "</pre>";
        
        echo "<h2>Paramètre 'page' spécifique:</h2>";
        echo "<pre>";
        echo "isset(\$_GET['page']): " . (isset($_GET['page']) ? 'OUI' : 'NON') . "\n";
        echo "Valeur brute: " . ($_GET['page'] ?? 'NULL') . "\n";
        echo "Valeur nettoyée: " . (isset($_GET['page']) ? max(1, (int) $_GET['page']) : 'NULL') . "\n";
        echo "</pre>";
        
        // Test de la logique de pagination
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $perPage = 5;
        $offset = ($page - 1) * $perPage;
        
        echo "<h2>Calculs de pagination:</h2>";
        echo "<pre>";
        echo "Page calculée: $page\n";
        echo "Éléments par page: $perPage\n";
        echo "Offset: $offset\n";
        echo "</pre>";
        
        // Test avec le modèle Post
        $postModel = $this->model('Post');
        $total = $postModel->countAll();
        $pages = ceil($total / $perPage);
        
        // Test de récupération des posts
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $posts = $postModel->getPaginated($perPage, $offset, $userId);
        
        echo "<h2>Résultats de la base:</h2>";
        echo "<pre>";
        echo "Total posts dans la base: $total\n";
        echo "Nombre de pages calculé: $pages\n";
        echo "Posts récupérés pour cette page: " . count($posts) . "\n";
        echo "User ID: " . ($userId ?? 'Non connecté') . "\n";
        echo "</pre>";
        
        if (!empty($posts)) {
            echo "<h2>Posts récupérés:</h2>";
            echo "<ul>";
            foreach ($posts as $post) {
                echo "<li>Post #{$post['id']} - {$post['username']} - " . substr($post['created_at'], 0, 10) . "</li>";
            }
            echo "</ul>";
        }
        
        echo "<h2>Test de liens de pagination:</h2>";
        echo "<div style='margin: 20px 0;'>";
        for ($i = 1; $i <= min($pages, 5); $i++) {
            $class = ($i == $page) ? 'style="background: blue; color: white; padding: 8px 12px; margin: 5px; text-decoration: none; border-radius: 4px;"' : 'style="background: #ccc; color: black; padding: 8px 12px; margin: 5px; text-decoration: none; border-radius: 4px;"';
            echo "<a href='/test/pagination?page=$i' $class>Page $i</a> ";
        }
        echo "</div>";
        
        echo "<h2>Test direct avec l'accueil:</h2>";
        echo "<div style='margin: 20px 0;'>";
        for ($i = 1; $i <= min($pages, 3); $i++) {
            echo "<a href='/?page=$i' style='background: green; color: white; padding: 8px 12px; margin: 5px; text-decoration: none; border-radius: 4px;'>Accueil Page $i</a> ";
        }
        echo "</div>";
        
        echo "<h2>Raccourcis:</h2>";
        echo "<a href='/'>← Retour à l'accueil</a><br>";
        echo "<a href='/gallery'>← Voir la galerie</a>";
    }
    
    public function index()
    {
        $this->pagination();
    }
} 