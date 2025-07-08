<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0" id="sidebar">
    <!-- Logo Section -->
    <div class="flex items-center justify-between h-20 px-6 border-b border-gray-100">
        <h1 class="text-2xl font-bold text-blue-600">CAMAGRU</h1>
        <!-- Close button for mobile -->
        <button class="lg:hidden text-gray-600 hover:text-gray-800" onclick="toggleSidebar()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Navigation container with flex grow and vertical distribution -->
    <div class="flex flex-col justify-between overflow-y-auto" style="height: calc(100vh - 5rem);">
        <nav class="flex-1 pt-8 overflow-y-auto">
            <ul class="space-y-1 px-3">
                <!-- Home -->
                <li>
                    <a href="/" class="flex items-center px-3 py-3 text-gray-800 rounded-xl hover:bg-gray-50 transition duration-200 group">
                        <div class="w-8 h-8 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                        <span class="font-normal text-base">Accueil</span>
                    </a>
                </li>
                <!-- Create -->
                <li>
                    <a href="/create" class="flex items-center px-3 py-3 text-gray-800 rounded-xl hover:bg-gray-50 transition duration-200 group">
                        <div class="w-8 h-8 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <span class="font-normal text-base">Créer</span>
                    </a>
                </li>
                <!-- Account -->
                <li>
                    <a href="/user/account" class="flex items-center px-3 py-3 text-gray-800 rounded-xl hover:bg-gray-50 transition duration-200 group">
                        <div class="w-8 h-8 flex items-center justify-center mr-4">
                            <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <span class="font-normal text-base">Compte</span>
                    </a>
                </li>
            </ul>
        </nav>

        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Bottom Section -->
            <div class="p-3 border-t border-gray-100">
                <a href="/user/logout" class="flex items-center px-3 py-3 text-red-600 rounded-xl hover:bg-red-50 transition duration-200">
                    <div class="w-8 h-8 flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </div>
                    <span class="font-normal text-base">Se déconnecter</span>
                </a>
            </div>
        <?php else: ?>
            <!-- Bottom Section - Login Button -->
            <div class="p-3 border-t border-gray-100">
                <a href="/user/login" class="flex items-center px-3 py-3 text-blue-600 rounded-xl hover:bg-blue-50 transition duration-200">
                    <div class="w-8 h-8 flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                    </div>
                    <span class="font-normal text-base">Se connecter</span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</aside>

<!-- Overlay for mobile -->
<div class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- Mobile menu button -->
<button id="mobileMenuButton" class="fixed top-4 left-4 z-50 lg:hidden bg-white border border-gray-200 text-gray-700 p-2 rounded-xl shadow-md hover:shadow-lg transition duration-200" onclick="toggleSidebar()">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
    </svg>
</button>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const menuButton = document.getElementById('mobileMenuButton');
    
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');

    if (sidebar.classList.contains('-translate-x-full')) {
        menuButton.classList.remove('hidden');
    } else {
        menuButton.classList.add('hidden');
    }
}

document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const menuButton = event.target.closest('button');
    const mobileMenuButton = document.getElementById('mobileMenuButton');

    if (!sidebar.contains(event.target) && !menuButton && window.innerWidth < 1024) {
        sidebar.classList.add('-translate-x-full');
        document.getElementById('sidebarOverlay').classList.add('hidden');
        mobileMenuButton.classList.remove('hidden');
    }
});

window.addEventListener('resize', function() {
    const mobileMenuButton = document.getElementById('mobileMenuButton');

    if (window.innerWidth >= 1024) {
        document.getElementById('sidebar').classList.remove('-translate-x-full');
        document.getElementById('sidebarOverlay').classList.add('hidden');
        mobileMenuButton.classList.remove('hidden');
    }
});
</script>
