<?php

class UserController {

	public function register()
	{
		require_once '../app/views/register.php';
	}

	public function registerSubmit()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$username = trim($_POST['username']);
			$email = trim($_POST['email']);
			$password = trim($_POST['password']);

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				die("Email invalide");
			}

			if (strlen($password) < 8) {
				die("Mot de passe trop court (min 8 caractères).");
			}

			$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
			$token = bin2hex(random_bytes(32)); // token sécurisé

			$userModel = $this->model('User');
			$existing = $userModel->getByEmail($email);

			if ($existing) {
				die("Cet email est déjà utilisé.");
			}

			$userModel->create($username, $email, $hashedPassword, $token);

			// Envoi d'un email de vérification
			require_once "../app/utils/Mailer.php"; // ou chemin relatif correct selon ton projet

			$link = "http://localhost:8080/user/verify/$token";
			$subject = "Confirme ton inscription à Camagru";

			$htmlContent = "
				<h1>Bienvenue sur Camagru !</h1>
				<p>Merci pour ton inscription, $username.</p>
				<p>Confirme ton adresse e-mail en cliquant sur le lien ci-dessous :</p>
				<a href='$link'>$link</a>
				<p>Si tu n'as pas demandé cette inscription, ignore simplement cet e-mail.</p>
			";

			Mailer::send($email, $subject, $htmlContent);


			echo "Compte créé ! Vérifie ta boîte mail pour activer ton compte.";
		}
	}

	public function verify($token) {
		$userModel = $this->model('User');
	
		if ($userModel->verifyByToken($token)) {
			echo "<h1>✅ Ton compte a été vérifié avec succès !</h1>";
			echo "<a href='/user/login'>Tu peux maintenant te connecter</a>";
		} else {
			echo "<h1>❌ Lien de vérification invalide ou expiré.</h1>";
		}
	}
	

	public function login()
	{
		require_once '../app/views/login.php';
	}

	public function loginSubmit()
	{
		// traitement du formulaire
	}
}