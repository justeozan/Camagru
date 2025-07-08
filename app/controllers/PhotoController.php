<?php

// PhotoController pour la compatibilité avec les routes existantes
require_once 'PostController.php';

class PhotoController extends PostController
{
    // Hérite de toutes les méthodes de PostController
    // Notamment la méthode upload() qui gère l'upload d'images
} 