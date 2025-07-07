<?php

class Model {
    protected $db;

    public function __construct() {
        try {
            $this->db = new PDO("mysql:host=db;dbname=camagru", "root", "root");
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur connexion BDD : " . $e->getMessage());
        }
    }
}
