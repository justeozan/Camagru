<?php require_once "../app/views/partials/header.php"; ?>

<div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">
  <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
    <h1 class="text-2xl font-bold text-center mb-6">Créer un compte</h1>
    <form action="/user/registerSubmit" method="POST" class="space-y-4">
      <div>
        <label class="block text-sm font-medium mb-1">Nom d'utilisateur</label>
        <input type="text" name="username" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Adresse e-mail</label>
        <input type="email" name="email" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Mot de passe</label>
        <input type="password" name="password" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
      </div>
      <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
        S'inscrire
      </button>
    </form>
    <p class="text-sm text-center text-gray-600 mt-4">
      Déjà inscrit ? <a href="/user/login" class="text-blue-600 hover:underline">Connexion</a>
    </p>
  </div>
</div>

<?php require_once "../app/views/partials/footer.php"; ?>
