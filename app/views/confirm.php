<?php require_once "../app/views/partials/header.php"; ?>

<div class="flex h-screen">
    <?php require_once "../app/views/partials/sidebar.php"; ?>
    <div class="flex-1 lg:ml-64 flex items-center justify-center bg-gray-50 p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
            <h1 class="text-4xl font-camagru text-center text-blue-600 mb-4">CAMAGRU</h1>
            <h2 class="text-xl font-semibold text-center text-gray-900 mb-2">Confirmez votre email</h2>
            <p class="text-center text-gray-600 mb-6">Un email de confirmation vous a été envoyé à <strong><?= htmlspecialchars($_SESSION['user']['email'] ?? 'votre adresse email') ?></strong>.<br>Veuillez cliquer sur le lien dans l'email pour activer votre compte.</p>
            
            <!-- Alert Box -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Compte non vérifié</h3>
                        <p class="mt-1 text-sm text-yellow-700">Votre accès est limité jusqu'à la confirmation de votre adresse email.</p>
                    </div>
                </div>
            </div>
            
            <!-- Resend email button -->
            <div class="text-center">
                <p class="text-sm text-gray-500 mb-4">Vous n'avez pas reçu l'email ?</p>
                <form action="/user/resendVerification" method="POST">
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                        Renvoyer l'email de confirmation
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once "../app/views/partials/footer.php"; ?>
