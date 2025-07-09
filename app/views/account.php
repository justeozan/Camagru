<?php require_once "../app/views/partials/header.php"; ?>

<!-- Toast Notification -->
<?php if (isset($_SESSION['toast'])): ?>
<div id="toast" class="fixed top-4 right-4 z-50 max-w-sm w-full bg-white border border-gray-200 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out">
	<div class="flex items-center p-4">
		<div class="flex-shrink-0">
			<?php if ($_SESSION['toast']['type'] === 'success'): ?>
				<svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
					<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
				</svg>
			<?php else: ?>
				<svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
					<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
				</svg>
			<?php endif; ?>
		</div>
		<div class="ml-3 w-0 flex-1">
			<p class="text-sm font-medium text-gray-900">
				<?= htmlspecialchars($_SESSION['toast']['message']) ?>
			</p>
		</div>
		<div class="ml-4 flex-shrink-0 flex">
			<button onclick="closeToast()" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
				<span class="sr-only">Fermer</span>
				<svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
					<path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
				</svg>
			</button>
		</div>
	</div>
</div>
<?php unset($_SESSION['toast']); ?>
<?php endif; ?>

<!-- Layout container -->
<div class="flex h-screen">
	<?php require_once "../app/views/partials/sidebar.php"; ?>
	
	<!-- Main content area -->
	<div class="flex-1 lg:ml-64 overflow-auto bg-gray-50">
		<main class="max-w-4xl mx-auto px-4 py-6">
			<!-- Page Header -->
			<div class="mb-8">
				<h1 class="text-3xl font-bold text-gray-900 mb-2">Paramètres du compte</h1>
				<p class="text-gray-600">Gérez vos informations personnelles et vos préférences</p>
			</div>

			<!-- Profile Section -->
			<div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
				<h2 class="text-xl font-semibold text-gray-900 mb-6">Informations du profil</h2>
				
				<!-- Profile Picture Section -->
				<div class="mb-8 pb-6 border-b border-gray-200">
					<form action="/user/updateAvatar" method="POST" enctype="multipart/form-data">
						<div class="flex items-center space-x-6">
							<div class="w-24 h-24 bg-blue-600 rounded-full flex items-center justify-center">
								<?php if (isset($_SESSION['user']['avatar']) && $_SESSION['user']['avatar']): ?>
									<img src="<?= htmlspecialchars($_SESSION['user']['avatar']) ?>" alt="Avatar" class="w-full h-full rounded-full object-cover">
								<?php else: ?>
									<span class="text-white font-bold text-2xl">
										<?= isset($_SESSION['user']['username']) ? strtoupper(substr($_SESSION['user']['username'], 0, 2)) : 'U' ?>
									</span>
								<?php endif; ?>
							</div>
							<div class="flex-1">
								<label class="block text-sm font-medium text-gray-700 mb-2">Photo de profil</label>
								<div class="flex items-center space-x-4">
									<input type="file" name="avatar" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
									<button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200 font-medium whitespace-nowrap">
										Mettre à jour
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>

				<!-- User Information Display -->
				<div class="space-y-6">
					<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
						<div>
							<label class="block text-sm font-medium text-gray-700 mb-2">Nom d'utilisateur</label>
							<div class="w-full border border-gray-200 rounded-lg px-4 py-3 bg-gray-50 text-gray-700">
								<?= htmlspecialchars($_SESSION['user']['username'] ?? 'Non défini') ?>
							</div>
						</div>

						<div>
							<label class="block text-sm font-medium text-gray-700 mb-2">Adresse e-mail</label>
							<div class="w-full border border-gray-200 rounded-lg px-4 py-3 bg-gray-50 text-gray-700">
								<?= htmlspecialchars($_SESSION['user']['email'] ?? 'Non défini') ?>
							</div>
						</div>
					</div>

					<!-- Edit Profile Button -->
					<div class="pt-4">
						<button onclick="showEditProfileModal()" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
							Modifier les informations
						</button>
					</div>
				</div>
			</div>

			<!-- Security Section -->
			<div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
				<h2 class="text-xl font-semibold text-gray-900 mb-6">Sécurité</h2>
				
				<form action="/user/changePassword" method="POST" class="space-y-6">
					<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
						<!-- Current Password -->
						<div>
							<label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe actuel</label>
							<input 
								type="password" 
								name="current_password"
								class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:border-transparent"
								required
							>
						</div>

						<!-- New Password -->
						<div>
							<label class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe</label>
							<input 
								type="password" 
								name="new_password"
								class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:border-transparent"
								required
							>
						</div>
					</div>

					<!-- Confirm Password -->
					<div class="max-w-md">
						<label class="block text-sm font-medium text-gray-700 mb-2">Confirmer le nouveau mot de passe</label>
						<input 
							type="password" 
							name="confirm_password"
							class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:border-transparent"
							required
						>
					</div>

					<!-- Change Password Button -->
					<div class="pt-4">
						<button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition duration-200 font-medium">
							Changer le mot de passe
						</button>
					</div>
				</form>
			</div>

			<!-- Preferences Section -->
			<div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
				<h2 class="text-xl font-semibold text-gray-900 mb-6">Préférences</h2>
				
				<form action="/user/updatePreferences" method="POST" class="space-y-6">
					<!-- Privacy Settings -->
					<div>
						<h3 class="text-lg font-medium text-gray-900 mb-4">Confidentialité</h3>
						<div class="space-y-4">
							<div class="flex items-center justify-between">
								<div>
									<label class="text-sm font-medium text-gray-700">Profil public</label>
									<p class="text-sm text-gray-500">Permettre aux autres de voir votre profil</p>
								</div>
								<label class="relative inline-flex items-center cursor-pointer">
									<input type="checkbox" name="public_profile" class="sr-only peer" <?= isset($_SESSION['user']['public_profile']) && $_SESSION['user']['public_profile'] ? 'checked' : '' ?>>
									<div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
								</label>
							</div>

							<div class="flex items-center justify-between">
								<div>
									<label class="text-sm font-medium text-gray-700">Notifications par email</label>
									<p class="text-sm text-gray-500">Recevoir des notifications par email</p>
								</div>
								<label class="relative inline-flex items-center cursor-pointer">
									<input type="checkbox" name="email_notifications" class="sr-only peer" <?= isset($_SESSION['user']['email_notifications']) && $_SESSION['user']['email_notifications'] ? 'checked' : '' ?>>
									<div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
								</label>
							</div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Notifications de commentaires</label>
                                    <p class="text-sm text-gray-500">Être notifié par email quand quelqu'un commente vos photos</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="notify_on_comment" class="sr-only peer" <?= isset($_SESSION['user']['notify_on_comment']) && $_SESSION['user']['notify_on_comment'] ? 'checked' : '' ?>>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
						</div>
					</div>

					<!-- Save Preferences -->
					<div class="pt-4">
						<button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
							Sauvegarder les préférences
						</button>
					</div>
				</form>
			</div>

			<!-- Danger Zone -->
			<div class="bg-white rounded-xl border border-red-200 p-6">
				<h2 class="text-xl font-semibold text-red-900 mb-6">Zone dangereuse</h2>
				
				<div class="space-y-4">
					<!-- Delete Account -->
					<div class="flex items-center justify-between p-4 bg-red-50 rounded-lg border border-red-200">
						<div>
							<h3 class="text-lg font-medium text-red-900">Supprimer le compte</h3>
							<p class="text-sm text-red-600">Cette action est irréversible. Toutes vos données seront perdues.</p>
						</div>
						<button 
							onclick="confirmDeleteAccount()"
							class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-200 font-medium"
						>
							Supprimer le compte
						</button>
					</div>
				</div>
			</div>
		</main>
	</div>
</div>

<!-- Edit Profile Modal -->
<div id="editProfileModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
	<div class="bg-white rounded-xl p-6 max-w-lg w-full">
		<h3 class="text-lg font-semibold text-gray-900 mb-4">Modifier les informations du profil</h3>
		
		<form action="/user/updateProfile" method="POST">
			<div class="space-y-4">
				<div>
					<label class="block text-sm font-medium text-gray-700 mb-2">Nom d'utilisateur</label>
					<input 
						type="text" 
						name="username" 
						value="<?= htmlspecialchars($_SESSION['user']['username'] ?? '') ?>"
						class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:border-transparent"
						required
					>
				</div>

				<div>
					<label class="block text-sm font-medium text-gray-700 mb-2">Adresse e-mail</label>
					<input 
						type="email" 
						name="email" 
						value="<?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>"
						class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:border-transparent"
						required
					>
				</div>
			</div>
			
			<div class="flex space-x-4 mt-6">
				<button 
					type="button" 
					onclick="closeEditProfileModal()"
					class="flex-1 bg-gray-200 text-gray-800 py-2 rounded-lg hover:bg-gray-300 transition duration-200"
				>
					Annuler
				</button>
				<button 
					type="submit"
					class="flex-1 bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition duration-200"
				>
					Sauvegarder
				</button>
			</div>
		</form>
	</div>
</div>

<!-- Delete Account Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
	<div class="bg-white rounded-xl p-6 max-w-md w-full">
		<h3 class="text-lg font-semibold text-gray-900 mb-4">Confirmer la suppression</h3>
		<p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.</p>
		
		<form action="/user/deleteAccount" method="POST">
			<div class="mb-4">
				<label class="block text-sm font-medium text-gray-700 mb-2">Tapez "SUPPRIMER" pour confirmer</label>
				<input 
					type="text" 
					id="confirmText"
					class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-400 focus:border-transparent"
					required
				>
			</div>
			
			<div class="flex space-x-4">
				<button 
					type="button" 
					onclick="closeDeleteModal()"
					class="flex-1 bg-gray-200 text-gray-800 py-2 rounded-lg hover:bg-gray-300 transition duration-200"
				>
					Annuler
				</button>
				<button 
					type="submit" 
					id="confirmDeleteBtn"
					disabled
					class="flex-1 bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
				>
					Supprimer définitivement
				</button>
			</div>
		</form>
	</div>
</div>

<script>
// Toast functionality
function showToast() {
	const toast = document.getElementById('toast');
	if (toast) {
		// Show the toast
		toast.classList.remove('translate-x-full');
		toast.classList.add('translate-x-0');
		
		// Auto-hide after 5 seconds
		setTimeout(() => {
			closeToast();
		}, 5000);
	}
}

function closeToast() {
	const toast = document.getElementById('toast');
	if (toast) {
		toast.classList.remove('translate-x-0');
		toast.classList.add('translate-x-full');
		
		// Remove from DOM after animation
		setTimeout(() => {
			toast.remove();
		}, 300);
	}
}

// Show toast on page load if it exists
document.addEventListener('DOMContentLoaded', function() {
	const toast = document.getElementById('toast');
	if (toast) {
		showToast();
	}
	
	// Add event listeners after DOM is loaded
	const confirmText = document.getElementById('confirmText');
	if (confirmText) {
		confirmText.addEventListener('input', function(e) {
			const deleteBtn = document.getElementById('confirmDeleteBtn');
			if (deleteBtn) {
				if (e.target.value === 'SUPPRIMER') {
					deleteBtn.disabled = false;
				} else {
					deleteBtn.disabled = true;
				}
			}
		});
	}
	
	// Modal background click handlers
	const deleteModal = document.getElementById('deleteModal');
	if (deleteModal) {
		deleteModal.addEventListener('click', function(e) {
			if (e.target === this) {
				closeDeleteModal();
			}
		});
	}
	
	const editProfileModal = document.getElementById('editProfileModal');
	if (editProfileModal) {
		editProfileModal.addEventListener('click', function(e) {
			if (e.target === this) {
				closeEditProfileModal();
			}
		});
	}
});

function showEditProfileModal() {
	const modal = document.getElementById('editProfileModal');
	if (modal) {
		modal.classList.remove('hidden');
		document.body.style.overflow = 'hidden';
	}
}

function closeEditProfileModal() {
	const modal = document.getElementById('editProfileModal');
	if (modal) {
		modal.classList.add('hidden');
		document.body.style.overflow = 'auto';
	}
}

function confirmDeleteAccount() {
	const modal = document.getElementById('deleteModal');
	if (modal) {
		modal.classList.remove('hidden');
		document.body.style.overflow = 'hidden';
	}
}

function closeDeleteModal() {
	const modal = document.getElementById('deleteModal');
	if (modal) {
		modal.classList.add('hidden');
		document.body.style.overflow = 'auto';
		
		const confirmText = document.getElementById('confirmText');
		const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
		
		if (confirmText) confirmText.value = '';
		if (confirmDeleteBtn) confirmDeleteBtn.disabled = true;
	}
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
	if (e.key === 'Escape') {
		closeDeleteModal();
		closeEditProfileModal();
	}
});
</script>

<?php require_once "../app/views/partials/footer.php"; ?>
