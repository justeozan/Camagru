<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page introuvable - Camagru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center px-4">
    <div class="max-w-2xl mx-auto text-center">
        
        <!-- Error Illustration -->
        <div class="mb-8">
            <div class="w-64 h-64 mx-auto relative">
                <!-- Camera Icon -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-32 h-32 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                              d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                              d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                
                <!-- 404 Text -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-6xl font-bold text-blue-600 opacity-20 transform rotate-12">404</span>
                </div>
                
                <!-- Floating Elements -->
                <div class="absolute top-4 left-4 w-4 h-4 bg-blue-400 rounded-full animate-bounce"></div>
                <div class="absolute top-12 right-8 w-3 h-3 bg-indigo-400 rounded-full animate-bounce" style="animation-delay: 0.5s;"></div>
                <div class="absolute bottom-8 left-12 w-2 h-2 bg-purple-400 rounded-full animate-bounce" style="animation-delay: 1s;"></div>
            </div>
        </div>

        <!-- Error Message -->
        <div class="mb-8">
            <h1 class="text-4xl md:text-6xl font-bold text-gray-800 mb-4">
                Oups ! <span class="text-blue-600">404</span>
            </h1>
            <h2 class="text-xl md:text-2xl font-semibold text-gray-600 mb-4">
                Page introuvable
            </h2>
            <p class="text-gray-500 text-lg max-w-md mx-auto">
                La page que vous recherchez n'existe pas ou a été déplacée. 
                Peut-être qu'elle est partie prendre une photo ! 📸
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-4 md:space-y-0 md:space-x-4 md:flex md:justify-center">
            <a href="/" 
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition duration-200 transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Retour à l'accueil
            </a>
            
            <button onclick="history.back()" 
                    class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-300 transition duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour en arrière
            </button>
        </div>

        <!-- Helpful Links -->
        <div class="mt-12 pt-8 border-t border-gray-200">
            <p class="text-sm text-gray-500 mb-4">Vous cherchez peut-être :</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="/create" class="text-blue-600 hover:text-blue-700 text-sm font-medium transition duration-200">
                    📷 Prendre une photo
                </a>
                <a href="/gallery" class="text-blue-600 hover:text-blue-700 text-sm font-medium transition duration-200">
                    🖼️ Galerie
                </a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/user/account" class="text-blue-600 hover:text-blue-700 text-sm font-medium transition duration-200">
                        ⚙️ Mon compte
                    </a>
                <?php else: ?>
                    <a href="/user/login" class="text-blue-600 hover:text-blue-700 text-sm font-medium transition duration-200">
                        🔐 Se connecter
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Fun Footer -->
        <div class="mt-8 text-center">
            <p class="text-xs text-gray-400">
                "Une photo vaut mille mots, mais cette page n'en vaut aucun !" 😄
            </p>
        </div>
    </div>

    <!-- Custom Animations -->
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
    </style>

    <script>
        // Petit effet sur le titre
        document.addEventListener('DOMContentLoaded', function() {
            const title = document.querySelector('h1');
            title.classList.add('animate-float');
            
            // Ajouter un effet de parallax léger sur les éléments flottants
            const floatingElements = document.querySelectorAll('.animate-bounce');
            
            document.addEventListener('mousemove', function(e) {
                const { clientX, clientY } = e;
                const centerX = window.innerWidth / 2;
                const centerY = window.innerHeight / 2;
                
                floatingElements.forEach((element, index) => {
                    const speed = (index + 1) * 0.01;
                    const x = (clientX - centerX) * speed;
                    const y = (clientY - centerY) * speed;
                    
                    element.style.transform = `translate(${x}px, ${y}px)`;
                });
            });
        });
    </script>
</body>
</html> 