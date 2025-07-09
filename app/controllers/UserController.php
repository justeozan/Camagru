<?php

class UserController extends Controller {

	public function login() { require_once '../app/views/login.php'; }
	public function register() { require_once '../app/views/register.php'; }
	public function resetPassword() { require_once '../app/views/resetPassword.php'; }

	public function loginSubmit() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$email = trim($_POST['email']);
			$password = trim($_POST['password']);

			$userModel = $this->model('User');
			$user = $userModel->getByEmail($email);

			if (!$user) $this->setToastError('Aucun compte trouvé avec cette adresse email.', '/user/login');
			if (!password_verify($password, $user['password'])) $this->setToastError('Mot de passe incorrect.', '/user/login');

			$_SESSION['user_id'] = $user['id'];
			$_SESSION['username'] = $user['username'];
			$_SESSION['email'] = $user['email'];

			$_SESSION['user'] = $user;

			if (!$user['is_verified'])
				header("Location: /user/confirm");
			else
				header("Location: /");
			exit();
		}
	}

	public function registerSubmit() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$username = trim($_POST['username']);
			$email = trim($_POST['email']);
			$password = trim($_POST['password']);

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $this->setToastError('Email invalide.', '/user/register');
			if (strlen($password) < 8) $this->setToastError('Mot de passe trop court (min 8 caractères).', '/user/register');

			$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
			$token = bin2hex(random_bytes(32)); // token sécurisé
			$userModel = $this->model('User');
			$existing = $userModel->getByEmail($email);

			if ($existing) $this->setToastError('Un compte existe déjà avec cette adresse email.', '/user/register');

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

			if (mail($email, $subject, $message, $headers)) {
				require_once '../app/models/User.php';
				$newUser = $userModel->getByEmail($email);
				$_SESSION['user_id'] = $newUser['id'];
				$_SESSION['username'] = $newUser['username'];
				$_SESSION['email'] = $newUser['email'];
				header("Location: /user/confirm");
				exit();
			} else { $this->setToastError("Erreur lors de l'envoi du mail de confirmation.", '/user/register'); }
		}
	}


	public function verify($token) {
		$userModel = $this->model('User');

		if ($userModel->verifyByToken($token))
			$this->setToastSuccess('Ton compte a été vérifié avec succès !', '/');
		else
			$this->setToastError('Lien de vérification invalide ou expiré.', '/user/login');
	}

	public function logout() {
		$_SESSION = [];
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
		}
		session_destroy();
		header("Location: /user/login");
	}

	public function account() {
		$this->requireVerify(); // Require verified user to access account
		$userModel = $this->model('User');
		$user = $userModel->getById($_SESSION['user_id']);
		if (!$user) { $this->setToastError('Utilisateur non trouvé.', '/'); exit(); }
		$_SESSION['user'] = $user;
		require_once '../app/views/account.php';
	}

	public function resetPasswordSubmit() {
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

	public function newPassword($token = null) {
		if (!$token) {
			header("Location: /user/login");
			exit();
		}

		$userModel = $this->model('User');
		$user = $userModel->verifyResetToken($token);
		if (!$user) $this->setToastError('Lien de réinitialisation invalide ou expiré.', '/user/resetPassword');
		require_once '../app/views/newPassword.php';
	}

	public function newPasswordSubmit() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$token = trim($_POST['token']);
			$password = trim($_POST['password']);
			$confirmPassword = trim($_POST['confirm_password']);

			if (strlen($password) < 8) $this->setToastError("Mot de passe trop court (min 8 caractères).", '/user/newPassword/' . $token);
			if ($password !== $confirmPassword) $this->setToastError("Les mots de passe ne correspondent pas.", '/user/newPassword/' . $token);

			$userModel = $this->model('User');
			
			if ($userModel->resetPassword($token, $password))
				$this->setToastSuccess("Votre mot de passe a été réinitialisé avec succès !", '/user/login');
			else
				$this->setToastError("Erreur lors de la réinitialisation. Le lien a peut-être expiré.", '/user/resetPassword');
		}
	}

	public function changePassword() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$userModel = $this->model('User');
			
			if (isset($_POST['token'])) { // Check if this is a password reset with token or password change from account page
				$token = trim($_POST['token']);
				$password = trim($_POST['password']);
				$confirmPassword = trim($_POST['confirm_password']);

				if (strlen($password) < 8) $this->setToastError("Mot de passe trop court (min 8 caractères).", '/user/resetPassword');
				if ($password !== $confirmPassword) $this->setToastError("Les mots de passe ne correspondent pas.", '/user/resetPassword');
				if ($userModel->resetPassword($token, $password))
					$this->setToastSuccess("Votre mot de passe a été réinitialisé avec succès !\n Connectez vous !", '/user/login');
				else
					$this->setToastError("Erreur lors de la réinitialisation. Le lien a peut-être expiré.", '/user/resetPassword');
			} else { // Password change from account page
				if (!isset($_SESSION['user_id'])) $this->setToastError("Vous devez être connecté pour changer votre mot de passe.", '/user/login');

				$currentPassword = trim($_POST['current_password']);
				$newPassword = trim($_POST['new_password']);
				$confirmPassword = trim($_POST['confirm_password']);

				if (strlen($newPassword) < 8) $this->setToastError("Le nouveau mot de passe doit contenir au moins 8 caractères.", '/user/account');
				if ($newPassword !== $confirmPassword) $this->setToastError("Les nouveaux mots de passe ne correspondent pas.", '/user/account');
				if (!$userModel->verifyCurrentPassword($_SESSION['user_id'], $currentPassword)) $this->setToastError("Le mot de passe actuel est incorrect.", '/user/account');

				if ($userModel->updatePassword($_SESSION['user_id'], $newPassword))
					$this->setToastSuccess("Votre mot de passe a été modifié avec succès !", '/user/account');
				else
					$this->setToastError("Une erreur est survenue lors de la modification du mot de passe.", '/user/account');
			}
		}
	}

	public function updateAvatar()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if (!isset($_SESSION['user_id'])) $this->setToastError("Vous devez être connecté pour modifier votre avatar.", '/user/login');
			if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) $this->setToastError("Aucune image sélectionnée ou erreur lors du téléchargement.", '/user/account');

			$file = $_FILES['avatar'];
			$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
			if (!in_array($file['type'], $allowedTypes)) $this->setToastError("Format d'image non supporté. Utilisez JPG, PNG, GIF ou WebP.", '/user/account');
			if ($file['size'] > 5 * 1024 * 1024) $this->setToastError("L'image est trop grande. Taille maximum: 5MB.", '/user/account');

			$uploadDir = '/var/www/html/uploads/avatars/';
			if (!file_exists($uploadDir) && !mkdir($uploadDir, 0755, true)) $this->setToastError("Impossible de créer le dossier de destination.", '/user/account');
			if (!is_writable($uploadDir)) $this->setToastError("Le dossier de destination n'est pas accessible en écriture.", '/user/account');

			// Generate unique filename
			$fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
			$fileName = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $fileExtension;
			$filePath = $uploadDir . $fileName;
			$webPath = '/uploads/avatars/' . $fileName;

			if (move_uploaded_file($file['tmp_name'], $filePath)) {
				$userModel = $this->model('User');
				
				if (!empty($_SESSION['user']['avatar'])) {
					$oldAvatarPath = '/var/www/html' . $_SESSION['user']['avatar'];
					if (file_exists($oldAvatarPath))
						unlink($oldAvatarPath);
				}
				
				if ($userModel->updateAvatar($_SESSION['user_id'], $webPath)) {
					$_SESSION['user']['avatar'] = $webPath;
					$this->setToastSuccess("Votre avatar a été mis à jour avec succès !");
				} else {
					unlink($filePath);
					$this->setToastError("Erreur lors de l'enregistrement de l'avatar.");
				}
			} else {
				// Get more specific error information
				$error = error_get_last();
				$errorMsg = 'Erreur lors de l\'enregistrement de l\'image.';
				if ($error && strpos($error['message'], 'Permission denied') !== false)
					$errorMsg .= ' Permissions insuffisantes.';
				$this->setToastError($errorMsg);
			}

			header('Location: /user/account');
		}
	}

	public function updateProfile() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if (!isset($_SESSION['user_id']))
				$this->setToastError("Vous devez être connecté pour modifier votre profil.", '/user/login');
			$username = trim($_POST['username']);
			$email = trim($_POST['email']);

			if (empty($username) || empty($email)) $this->setToastError("Le nom d'utilisateur et l'email sont obligatoires.", '/user/account');
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $this->setToastError("L'adresse email est invalide.", '/user/account');

			$userModel = $this->model('User');
			
			// Check if email is already taken by another user
			$existingUser = $userModel->getByEmail($email);
			if ($existingUser && $existingUser['id'] != $_SESSION['user_id']) $this->setToastError("Cette adresse email est déjà utilisée par un autre compte.", '/user/account');

			// Update profile
			if ($userModel->updateProfile($_SESSION['user_id'], $username, $email)) {
				$_SESSION['user']['username'] = $username;
				$_SESSION['user']['email'] = $email;

				$this->setToastSuccess("Votre profil a été mis à jour avec succès !", '/user/account');
			} else {
				$this->setToastError("Une erreur est survenue lors de la mise à jour du profil.", '/user/account');
			}
		}
	}

	public function updatePreferences() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if (!isset($_SESSION['user_id'])) $this->setToastError("Vous devez être connecté pour modifier vos préférences.", '/user/login');

			$notifyOnComment = isset($_POST['notify_on_comment']) ? 1 : 0;
			$userModel = $this->model('User');
			
			if ($userModel->updatePreferences($_SESSION['user_id'], $notifyOnComment)) {
				$_SESSION['user']['notify_on_comment'] = $notifyOnComment;
				$this->setToastSuccess("Vos préférences ont été mises à jour avec succès !", '/user/account');
			} else
				$this->setToastError("Une erreur est survenue lors de la mise à jour des préférences.", '/user/account');
		}
	}

	public function confirm() {
		$this->requireAuth();

		$userModel = $this->model('User');
		$user = $userModel->getById($_SESSION['user_id']);
		if (!$user) $this->setToastError("Utilisateur non trouvé", '/user/account');
		require_once '../app/views/confirm.php';
	}
}
