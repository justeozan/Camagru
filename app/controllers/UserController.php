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
				// Start session and set user data
				require_once '../app/models/User.php';
				$newUser = $userModel->getByEmail($email);
				$_SESSION['user_id'] = $newUser['id'];
				$_SESSION['username'] = $newUser['username'];
				$_SESSION['email'] = $newUser['email'];
				
				// Redirect to confirm page
				header("Location: /user/confirm");
				exit();
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

			$_SESSION['user_id'] = $user['id'];
			$_SESSION['username'] = $user['username'];
			$_SESSION['email'] = $user['email'];

			if (!$user['is_verified']) {
				header("Location: /user/confirm");
			} else {
				header("Location: /");
			}
			exit();
		}
	}

	public function logout(){
		session_start();

		// Supprimer toutes les variables de session
		$_SESSION = [];

		// Supprimer le cookie de session
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
		}

		// Détruire la session
		session_destroy();

		header("Location: /");
		exit();
	}

	public function account()
	{
		$this->requireVerify(); // Require verified user to access account

		$userModel = $this->model('User');
		$user = $userModel->getById($_SESSION['user_id']);

		require_once '../app/views/account.php';
	}

	public function resetPassword()
	{
		require_once '../app/views/resetPassword.php';
	}

	public function resetPasswordSubmit()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$email = trim($_POST['email']);

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				die("Email invalide");
			}

			$userModel = $this->model('User');
			$resetToken = $userModel->generateResetToken($email);

			if ($resetToken) {
				// Envoi du mail de réinitialisation
				$link = "http://localhost:8080/user/newPassword/$resetToken";
				$subject = "Réinitialisation de votre mot de passe - Camagru";

				$message = "
				<html><body>
					<h1>Réinitialisation de mot de passe</h1>
					<p>Vous avez demandé la réinitialisation de votre mot de passe sur Camagru.</p>
					<p>Pour définir un nouveau mot de passe, cliquez sur le lien ci-dessous :</p>
					<p><a href=\"$link\">$link</a></p>
					<p><strong>Ce lien expirera dans 1 heure.</strong></p>
					<p>Si vous n'avez pas demandé cette réinitialisation, ignorez ce message.</p>
				</body></html>
				";

				$headers  = "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=UTF-8\r\n";
				$headers .= "From: Camagru <noreply@camagru.local>\r\n";

				if (mail($email, $subject, $message, $headers)) {
					echo "<div class='alert-success'>Un email de réinitialisation a été envoyé à votre adresse. Vérifiez votre boîte mail.</div>";
				} else {
					echo "<div class='alert-error'>Erreur lors de l'envoi du mail.</div>";
				}
			} else {
				echo "<div class='alert-error'>Aucun compte associé à cette adresse email.</div>";
			}
		}
	}

	public function newPassword($token = null)
	{
		if (!$token) {
			header("Location: /user/login");
			exit();
		}

		$userModel = $this->model('User');
		$user = $userModel->verifyResetToken($token);

		if (!$user) {
			echo "<div class='alert-error'>Lien de réinitialisation invalide ou expiré.</div>";
			echo "<a href='/user/resetPassword'>Demander un nouveau lien</a>";
			return;
		}

		require_once '../app/views/newPassword.php';
	}

	public function newPasswordSubmit()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$token = trim($_POST['token']);
			$password = trim($_POST['password']);
			$confirmPassword = trim($_POST['confirm_password']);

			if (strlen($password) < 8) {
				die("Mot de passe trop court (min 8 caractères).");
			}

			if ($password !== $confirmPassword) {
				die("Les mots de passe ne correspondent pas.");
			}

			$userModel = $this->model('User');
			
			if ($userModel->resetPassword($token, $password)) {
				echo "<div class='alert-success'>Votre mot de passe a été réinitialisé avec succès !</div>";
				echo "<a href='/user/login'>Se connecter avec le nouveau mot de passe</a>";
			} else {
				echo "<div class='alert-error'>Erreur lors de la réinitialisation. Le lien a peut-être expiré.</div>";
				echo "<a href='/user/resetPassword'>Demander un nouveau lien</a>";
			}
		}
	}

	public function confirm()
	{
		$this->requireAuth();

		$userModel = $this->model('User');
		$user = $userModel->getById($_SESSION['user_id']);
		if (!$user) {
			die("Utilisateur non trouvé");
		}
		require_once '../app/views/confirm.php';
	}
}
