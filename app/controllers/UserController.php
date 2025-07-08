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
			<html>
			<head>
				<meta charset='UTF-8'>
				<meta name='viewport' content='width=device-width, initial-scale=1.0'>
				<script src='https://cdn.tailwindcss.com'></script>
				<link rel='preconnect' href='https://fonts.googleapis.com'>
				<link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
				<link href='https://fonts.googleapis.com/css2?family=Pacifico&display=swap' rel='stylesheet'>
				<script>
					tailwind.config = {
						theme: {
							extend: {
								fontFamily: {
									camagru: ['Pacifico', 'cursive'],
								}
							}
						}
					}
				</script>
			</head>
			<body style='margin: 0; padding: 20px; background-color: #f9fafb; font-family: system-ui, sans-serif;'>
				<div style='max-width: 600px; margin: 0 auto; background-color: white; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); padding: 32px;'>
					<h1 style='font-family: Pacifico, cursive; font-size: 36px; text-align: center; color: #2563eb; margin: 0 0 16px 0;'>CAMAGRU</h1>
					<h2 style='font-size: 24px; font-weight: 600; text-align: center; color: #111827; margin: 0 0 8px 0;'>Bienvenue !</h2>
					<p style='text-align: center; color: #6b7280; margin: 0 0 24px 0;'>Merci pour ton inscription, <strong style='color: #111827;'>$username</strong>.</p>
					<p style='text-align: center; color: #6b7280; margin: 0 0 24px 0;'>Pour confirmer ton compte, clique sur le bouton ci-dessous :</p>
					<div style='text-align: center; margin: 32px 0;'>
						<a href='$link' style='display: inline-block; background-color: #2563eb; color: white; padding: 12px 32px; border-radius: 8px; text-decoration: none; font-weight: 500; transition: background-color 0.2s;'>Confirmer mon compte</a>
					</div>
					<p style='text-align: center; color: #9ca3af; font-size: 14px; margin: 24px 0 0 0;'>Si tu n'as pas demandé cette inscription, ignore ce message.</p>
				</div>
			</body>
			</html>
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
		// session_start();

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
				<html>
				<head>
					<meta charset='UTF-8'>
					<meta name='viewport' content='width=device-width, initial-scale=1.0'>
					<script src='https://cdn.tailwindcss.com'></script>
					<link rel='preconnect' href='https://fonts.googleapis.com'>
					<link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
					<link href='https://fonts.googleapis.com/css2?family=Pacifico&display=swap' rel='stylesheet'>
					<script>
						tailwind.config = {
							theme: {
								extend: {
									fontFamily: {
										camagru: ['Pacifico', 'cursive'],
									}
								}
							}
						}
					</script>
				</head>
				<body style='margin: 0; padding: 20px; background-color: #f9fafb; font-family: system-ui, sans-serif;'>
					<div style='max-width: 600px; margin: 0 auto; background-color: white; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); padding: 32px;'>
						<h1 style='font-family: Pacifico, cursive; font-size: 36px; text-align: center; color: #2563eb; margin: 0 0 16px 0;'>CAMAGRU</h1>
						<h2 style='font-size: 24px; font-weight: 600; text-align: center; color: #111827; margin: 0 0 8px 0;'>Réinitialisation de mot de passe</h2>
						<p style='text-align: center; color: #6b7280; margin: 0 0 24px 0;'>Vous avez demandé la réinitialisation de votre mot de passe sur Camagru.</p>
						<p style='text-align: center; color: #6b7280; margin: 0 0 24px 0;'>Pour définir un nouveau mot de passe, cliquez sur le bouton ci-dessous :</p>
						<div style='text-align: center; margin: 32px 0;'>
							<a href='$link' style='display: inline-block; background-color: #2563eb; color: white; padding: 12px 32px; border-radius: 8px; text-decoration: none; font-weight: 500; transition: background-color 0.2s;'>Réinitialiser mon mot de passe</a>
						</div>
						<div style='background-color: #fef3c7; border: 1px solid #f59e0b; border-radius: 8px; padding: 12px; margin: 24px 0;'>
							<p style='margin: 0; color: #92400e; font-size: 14px; text-align: center;'><strong>Ce lien expirera dans 1 heure.</strong></p>
						</div>
						<p style='text-align: center; color: #9ca3af; font-size: 14px; margin: 24px 0 0 0;'>Si vous n'avez pas demandé cette réinitialisation, ignorez ce message.</p>
					</div>
				</body>
				</html>
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
