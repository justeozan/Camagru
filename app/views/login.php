<?php require_once "../app/views/partials/header.php"; ?>

<!-- Layout container -->
<div class="flex h-screen">
	<?php require_once "../app/views/partials/sidebar.php"; ?>
	<div class="flex-1 lg:ml-64 flex items-center justify-center bg-gray-50 p-4">
		<div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
			<h1 class="text-4xl font-camagru text-center text-blue-600 mb-8">CAMAGRU</h1>
			<form action="/user/loginSubmit" method="POST" class="space-y-4">
				<div>
					<label class="block text-sm font-medium mb-1">Email</label>
					<input type="email" name="email" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-transparent">
				</div>
				<div>
					<label class="block text-sm font-medium mb-1">Mot de passe</label>
					<input type="password" name="password" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-transparent">
				</div>
				<button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-200 font-medium">Se connecter</button>
			</form>
			<div class="text-center mt-4">
				<a href="/user/resetPassword" class="text-sm text-blue-600 hover:underline">Mot de passe oublié ?</a>
			</div>
			<p class="text-sm text-center text-gray-600 mt-6">Pas encore inscrit ? <a href="/user/register" class="text-blue-600 hover:underline font-medium">Créer un compte</a></p>
		</div>
	</div>
</div>

<?php require_once "../app/views/partials/footer.php"; ?>