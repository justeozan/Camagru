
<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 h-full" id="sidebar">
    <div class="flex items-center justify-between h-16 px-6 bg-blue-600">
        <h2 class="text-xl font-bold text-white">Camagru</h2>
        <!-- Close button for mobile -->
        <button class="lg:hidden text-white hover:text-gray-200" onclick="toggleSidebar()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    
    <!-- Navigation container with flex grow -->
    <div class="flex flex-col h-full">
        <nav class="flex-1 mt-8">
            <ul class="space-y-2 px-4">
                <!-- Home -->
                <li>
                    <a href="/" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition duration-200 group">
                        <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="font-medium">Accueil</span>
                    </a>
                </li>
                
                <!-- Create -->
                <li>
                    <a href="/camera" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition duration-200 group">
                        <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span class="font-medium">Créer</span>
                    </a>
                </li>
                
                <!-- Account -->
                <li>
                    <a href="/profile" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition duration-200 group">
                        <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="font-medium">Mon compte</span>
                    </a>
                </li>
            </ul>
            
            <!-- Separator -->
            <div class="my-6 px-4">
                <div class="border-t border-gray-200"></div>
            </div>
            
            <!-- Disconnect -->
            <ul class="px-4">
                <li>
                    <a href="/logout" class="flex items-center px-4 py-3 text-red-600 rounded-lg hover:bg-red-50 hover:text-red-700 transition duration-200 group">
                        <svg class="w-5 h-5 mr-3 text-red-500 group-hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="font-medium">Se déconnecter</span>
                    </a>
                </li>
            </ul>
        </nav>
        
        <!-- User info at bottom -->
        <?php if (isset($_SESSION['user'])): ?>
        <div class="p-4 border-t border-gray-200 bg-gray-50 mt-auto">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                    <span class="text-white font-semibold text-sm">
                        <?= strtoupper(substr($_SESSION['user']['username'], 0, 2)) ?>
                    </span>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-700"><?= htmlspecialchars($_SESSION['user']['username']) ?></p>
                    <p class="text-xs text-gray-500">En ligne</p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</aside>

<!-- Overlay for mobile -->
<div class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- Mobile menu button -->
<button class="fixed top-4 left-4 z-50 lg:hidden bg-blue-600 text-white p-2 rounded-lg shadow-lg" onclick="toggleSidebar()">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
    </svg>
</button>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const menuButton = event.target.closest('button');
    
    if (!sidebar.contains(event.target) && !menuButton && window.innerWidth < 1024) {
        sidebar.classList.add('-translate-x-full');
        document.getElementById('sidebarOverlay').classList.add('hidden');
    }
});

// Handle window resize
window.addEventListener('resize', function() {
    if (window.innerWidth >= 1024) {
        document.getElementById('sidebar').classList.remove('-translate-x-full');
        document.getElementById('sidebarOverlay').classList.add('hidden');
    }
});
</script>