<?php

// La classe User hérite de la classe Model (définie dans core/Model.php)
// Cela lui donne accès à la base de données via $this->db
class User extends Model {

    // Fonction pour créer un nouvel utilisateur dans la base de données
    // On lui passe le pseudo, l'email, le mot de passe hashé et un token unique pour la vérification
    public function create($username, $email, $hashedPassword, $token) {
        // Préparation d'une requête SQL pour insérer un nouvel utilisateur
        // Les "?" sont des paramètres sécurisés pour éviter les injections SQL
        $stmt = $this->db->prepare("
            INSERT INTO users (username, email, password, verify_token)
            VALUES (?, ?, ?, ?)
        ");

        // On exécute la requête avec les valeurs données
        // Si tout se passe bien, la fonction renverra "true", sinon "false"
        return $stmt->execute([$username, $email, $hashedPassword, $token]);
    }

    // Fonction pour récupérer un utilisateur existant à partir de son adresse e-mail
    public function getByEmail($email) {
        // On prépare une requête pour chercher l'utilisateur dont l'e-mail correspond
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");

        // On exécute la requête avec l'adresse e-mail
        $stmt->execute([$email]);

        // On retourne la première ligne trouvée, sous forme de tableau associatif
        // Par exemple : ['id' => 1, 'username' => 'john', ...]
        // Si aucun résultat, ça renvoie "false"
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

	// Fonction pour vérifier si un utilisateur existe avec un token spécifique
	public function verifyByToken($token)
	{
		$stmt = $this->db->prepare("SELECT * FROM users WHERE verify_token = ?");
		$stmt->execute([$token]);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($user) {
			// On valide le compte
			$update = $this->db->prepare("UPDATE users SET is_verified = 1, verify_token = NULL WHERE id = ?");
			$update->execute([$user['id']]);
			return true;
		}
		return false;
	}
}
