<?php require_once "../app/views/partials/header.php"; ?>

<!-- Layout container -->
<div class="flex h-screen">
    <?php require_once "../app/views/partials/sidebar.php"; ?>
    
    <!-- Main content area -->
    <div class="flex-1 lg:ml-64 flex items-center justify-center bg-gray-50 p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
            
            <!-- Titre -->
            <h1 class="text-4xl font-bold text-center text-blue-600 mb-2">
                CAMAGRU
            </h1>
            <p class="text-center text-gray-600 mb-8">Nouveau mot de passe</p>
            
            <!-- Instructions -->
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                <p class="text-sm text-green-800">
                    <strong>Presque terminé !</strong><br>
                    Choisissez un nouveau mot de passe sécurisé pour votre compte.
                </p>
            </div>
            
            <form action="/user/newPasswordSubmit" method="POST" class="space-y-4">
                <!-- Token caché -->
                <input type="hidden" name="token" value="<?= htmlspecialchars($user['reset_token'] ?? '') ?>">
                
                <div>
                    <label class="block text-sm font-medium mb-1">Nouveau mot de passe</label>
                    <input type="password" name="password" required 
                           placeholder="Minimum 8 caractères"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Au moins 8 caractères</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-1">Confirmer le mot de passe</label>
                    <input type="password" name="confirm_password" required 
                           placeholder="Retapez le mot de passe"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                </div>
                
                <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition duration-200 font-medium">
                    Réinitialiser le mot de passe
                </button>
            </form>
            
            <!-- Sécurité -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <p class="text-xs text-gray-600">
                        Ce lien de réinitialisation expirera dans 1 heure pour votre sécurité.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "../app/views/partials/footer.php"; ?> 