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
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fonction pour récupérer un utilisateur par son ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

	// Fonction pour vérifier si un utilisateur existe avec un token spécifique
	public function verifyByToken($token) {
		$stmt = $this->db->prepare("SELECT * FROM users WHERE verify_token = ?");
		$stmt->execute([$token]);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($user) {
			$update = $this->db->prepare("UPDATE users SET is_verified = 1, verify_token = NULL WHERE id = ?");
			$update->execute([$user['id']]);
			return true;
		}
		return false;
	}

	// Fonction pour générer un token de réinitialisation de mot de passe
	public function generateResetToken($email) {
		$user = $this->getByEmail($email);
		if (!$user)
			return false;

		$resetToken = bin2hex(random_bytes(32));
		$expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token valide 1 heure
		$stmt = $this->db->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
		$stmt->execute([$resetToken, $expiry, $email]);
		return $resetToken;
	}

	// Fonction pour vérifier la validité d'un token de réinitialisation
	public function verifyResetToken($token) {
		$stmt = $this->db->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
		$stmt->execute([$token]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	// Fonction pour réinitialiser le mot de passe
	public function resetPassword($token, $newPassword) {
		$user = $this->verifyResetToken($token);
		if (!$user) {
			return false;
		}

		$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
		$stmt = $this->db->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
		
		return $stmt->execute([$hashedPassword, $user['id']]);
	}
	
	// Fonction pour vérifier le mot de passe actuel d'un utilisateur
	public function verifyCurrentPassword($userId, $currentPassword) {
		$stmt = $this->db->prepare("SELECT password FROM users WHERE id = ?");
		$stmt->execute([$userId]);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		
		if ($user)
			return password_verify($currentPassword, $user['password']);
		return false;
	}
	
	// Fonction pour mettre à jour le mot de passe d'un utilisateur connecté
	public function updatePassword($userId, $newPassword) {
		$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
		$stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
		return $stmt->execute([$hashedPassword, $userId]);
	}
	
	// Fonction pour mettre à jour l'avatar d'un utilisateur
	public function updateAvatar($userId, $avatarPath) {
		$stmt = $this->db->prepare("UPDATE users SET avatar = ? WHERE id = ?");
		return $stmt->execute([$avatarPath, $userId]);
	}
	
	// Fonction pour mettre à jour le profil d'un utilisateur
	public function updateProfile($userId, $username, $email) {
		$stmt = $this->db->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
		return $stmt->execute([$username, $email, $userId]);
	}
	
	// Fonction pour mettre à jour le profil avec vérification email (quand l'email change)
	public function updateProfileWithEmailVerification($userId, $username, $email, $token) {
		$stmt = $this->db->prepare("UPDATE users SET username = ?, email = ?, is_verified = 0, verify_token = ? WHERE id = ?");
		return $stmt->execute([$username, $email, $token, $userId]);
	}
	
	// Fonction pour mettre à jour le token de vérification
	public function updateVerificationToken($userId, $token) {
		$stmt = $this->db->prepare("UPDATE users SET verify_token = ? WHERE id = ?");
		return $stmt->execute([$token, $userId]);
	}
	
	// Fonction pour mettre à jour les préférences d'un utilisateur
	public function updatePreferences($userId, $notifyOnComment) {
		$stmt = $this->db->prepare("UPDATE users SET notify_on_comment = ? WHERE id = ?");
		return $stmt->execute([$notifyOnComment, $userId]);
	}

	// Fonction pour mettre à jour les préférences de notification
	public function updateNotificationPreference($userId, $notifyOnComment) {
		$stmt = $this->db->prepare("UPDATE users SET notify_on_comment = ? WHERE id = ?");
		return $stmt->execute([(int)$notifyOnComment, $userId]);
	}

	// Fonction pour récupérer l'auteur d'un post avec ses préférences
	public function getPostAuthor($postId) {
		$stmt = $this->db->prepare("SELECT users.* FROM users JOIN posts ON users.id = posts.user_id WHERE posts.id = ?");
		$stmt->execute([$postId]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	// Fonction pour supprimer un utilisateur et toutes ses données associées
	public function deleteAccount($userId) {
		try {
			$this->db->beginTransaction();

			$stmt = $this->db->prepare("SELECT image_path FROM posts WHERE user_id = ?");
			$stmt->execute([$userId]);
			$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

			foreach ($posts as $post) {
				if (!empty($post['image_path'])) {
					$fullPath = $_SERVER['DOCUMENT_ROOT'] . $post['image_path'];
					if (file_exists($fullPath)) {
						unlink($fullPath);
					}
				}
			}

			$stmt = $this->db->prepare("SELECT avatar FROM users WHERE id = ?");
			$stmt->execute([$userId]);
			$user = $stmt->fetch(PDO::FETCH_ASSOC);

			if (!empty($user['avatar'])) {
				$fullPath = $_SERVER['DOCUMENT_ROOT'] . $user['avatar'];
				if (file_exists($fullPath))
					unlink($fullPath);
			}

			$stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
			$stmt->execute([$userId]);

			$this->db->commit();
			return true;

		} catch (Exception $e) {
			$this->db->rollback();
			return false;
		}
	}
}
