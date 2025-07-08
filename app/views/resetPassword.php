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
            <p class="text-center text-gray-600 mb-8">Réinitialiser le mot de passe</p>
            
            <!-- Description -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                <p class="text-sm text-blue-800">
                    <strong>Mot de passe oublié ?</strong><br>
                    Saisissez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.
                </p>
            </div>
            
            <form action="/user/resetPasswordSubmit" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Adresse e-mail</label>
                    <input type="email" name="email" required 
                           placeholder="votre@email.com"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                </div>
                
                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                    Envoyer le lien de réinitialisation
                </button>
            </form>
            
            <!-- Liens de navigation -->
            <div class="text-center mt-6 space-y-2">
                <p class="text-sm text-gray-600">
                    Vous vous souvenez de votre mot de passe ? 
                    <a href="/user/login" class="text-blue-600 hover:underline font-medium">Se connecter</a>
                </p>
                <p class="text-sm text-gray-600">
                    Pas encore inscrit ? 
                    <a href="/user/register" class="text-blue-600 hover:underline font-medium">Créer un compte</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once "../app/views/partials/footer.php"; ?> 