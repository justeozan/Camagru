<?php require_once "../app/views/partials/header.php"; ?>

<!-- Layout container -->
<div class="flex h-screen">
    <?php require_once "../app/views/partials/sidebar.php"; ?>
    
    <!-- Main content area -->
    <div class="flex-1 lg:ml-64 flex items-center justify-center bg-gray-50 p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
            
            <!-- Titre -->
            <h1 class="text-4xl font-camagru text-center text-blue-600 mb-2">
                CAMAGRU
            </h1>
            <p class="text-center text-gray-600 mb-8">Créer un compte</p>
            
            <form action="/user/registerSubmit" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Nom d'utilisateur</label>
                    <input type="text" name="username" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Adresse e-mail</label>
                    <input type="email" name="email" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Mot de passe</label>
                    <input type="password" name="password" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                    S'inscrire
                </button>
            </form>
            
            <p class="text-sm text-center text-gray-600 mt-6">
                Déjà inscrit ? <a href="/user/login" class="text-blue-600 hover:underline font-medium">Connexion</a>
            </p>
        </div>
    </div>
</div>

<?php require_once "../app/views/partials/footer.php"; ?>
