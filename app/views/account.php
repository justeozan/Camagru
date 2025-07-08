<?php require_once "../app/views/partials/header.php"; ?>

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
                
                <form action="/user/updateProfile" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <!-- Profile Picture -->
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
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Photo de profil</label>
                            <input type="file" name="avatar" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                    </div>

                    <!-- Username -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                        <!-- Email -->
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

                    <!-- Bio -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Biographie</label>
                        <textarea 
                            name="bio" 
                            rows="3" 
                            placeholder="Parlez-nous de vous..."
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:border-transparent resize-none"
                        ><?= htmlspecialchars($_SESSION['user']['bio'] ?? '') ?></textarea>
                    </div>

                    <!-- Save Button -->
                    <div class="pt-4">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                            Sauvegarder les modifications
                        </button>
                    </div>
                </form>
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
function confirmDeleteAccount() {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('confirmText').value = '';
    document.getElementById('confirmDeleteBtn').disabled = true;
}

// Enable delete button only when "SUPPRIMER" is typed
document.getElementById('confirmText').addEventListener('input', function(e) {
    const deleteBtn = document.getElementById('confirmDeleteBtn');
    if (e.target.value === 'SUPPRIMER') {
        deleteBtn.disabled = false;
    } else {
        deleteBtn.disabled = true;
    }
});

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});

// Close modal on background click
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>

<?php require_once "../app/views/partials/footer.php"; ?>
