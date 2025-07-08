<?php

class UserController extends Controller {

	public function register()
	{
		require_once '../app/views/register.php';
	}

	public function registerSubmit()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

			// 🔥 Envoi du mail de vérification via mail()
			$link = "http://localhost:8080/user/verify/$token";
			$subject = "Confirme ton inscription à Camagru";

			$message = "
			<html><body>
				<h1>Bienvenue sur Camagru !</h1>
				<p>Merci pour ton inscription, <strong>$username</strong>.</p>
				<p>Pour confirmer ton compte, clique ici :</p>
				<p><a href=\"$link\">$link</a></p>
				<p>Si tu n'as pas demandé cette inscription, ignore ce message.</p>
			</body></html>
			";

			$headers  = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=UTF-8\r\n";
			$headers .= "From: Camagru <noreply@camagru.local>\r\n";

			// ✅ Appel à mail() — fonctionne avec MailHog dans Docker
			if (mail($email, $subject, $message, $headers)) {
				echo "Compte créé ! Vérifie ta boîte mail pour activer ton compte.";
			} else {
				echo "Erreur lors de l'envoi du mail.";
			}
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
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$email = trim($_POST['email']);
			$password = trim($_POST['password']);

			$userModel = $this->model('User');
			$user = $userModel->getByEmail($email);

			if (!$user)
				die("Utilisateur non trouvé");

			if (!password_verify($password, $user['password']))
				die("Mot de passe incorrect");

			if (!$user['is_verified'])
				die("Compte non vérifié. Vérifie ta boîte mail.");

			session_start();

			$_SESSION['user_id'] = $user['id'];
			$_SESSION['username'] = $user['username'];
			$_SESSION['email'] = $user['email'];

			header("Location: /");
			exit();
		}
	}

	public function account()
	{
		if (!isset($_SESSION['user_id'])) {
			header("Location: /user/login");
			exit();
		}

		$userModel = $this->model('User');
		$user = $userModel->getById($_SESSION['user_id']);

		require_once '../app/views/account.php';
	}

	public function confirm()
	{
		if (!isset($_SESSION['user_id'])) {
			header("Location: /user/login");
			exit();
		}

		$userModel = $this->model('User');
		$user = $userModel->getById($_SESSION['user_id']);
		if (!$user) {
			die("Utilisateur non trouvé");
		}
		require_once '../app/views/confirm.php';
	}
}
