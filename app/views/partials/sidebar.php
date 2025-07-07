
<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 h-full" id="sidebar">
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
    
    <!-- Navigation container with flex grow -->
    <div class="flex flex-col h-full">
        <nav class="flex-1 pt-8">
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
                    <a href="/camera" class="flex items-center px-3 py-3 text-gray-800 rounded-xl hover:bg-gray-50 transition duration-200 group">
                        <div class="w-8 h-8 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <span class="font-normal text-base">Créer</span>
                    </a>
                </li>
                
                <!-- Search -->
                <li>
                    <a href="/search" class="flex items-center px-3 py-3 text-gray-800 rounded-xl hover:bg-gray-50 transition duration-200 group">
                        <div class="w-8 h-8 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <span class="font-normal text-base">Rechercher</span>
                    </a>
                </li>
                
                <!-- Explore -->
                <li>
                    <a href="/explore" class="flex items-center px-3 py-3 text-gray-800 rounded-xl hover:bg-gray-50 transition duration-200 group">
                        <div class="w-8 h-8 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="font-normal text-base">Explorer</span>
                    </a>
                </li>
                
                <!-- Messages -->
                <li>
                    <a href="/messages" class="flex items-center px-3 py-3 text-gray-800 rounded-xl hover:bg-gray-50 transition duration-200 group">
                        <div class="w-8 h-8 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <span class="font-normal text-base">Messages</span>
                    </a>
                </li>
                
                <!-- Profile -->
                <li>
                    <a href="/profile" class="flex items-center px-3 py-3 text-gray-800 rounded-xl hover:bg-gray-50 transition duration-200 group">
                        <div class="w-8 h-8 flex items-center justify-center mr-4">
                            <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <span class="font-normal text-base">Profil</span>
                    </a>
                </li>
            </ul>
        </nav>
        
        <!-- Bottom Section -->
        <div class="p-3 border-t border-gray-100">
            <!-- Settings -->
            <a href="/settings" class="flex items-center px-3 py-3 text-gray-800 rounded-xl hover:bg-gray-50 transition duration-200 mb-2">
                <div class="w-8 h-8 flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <span class="font-normal text-base">Paramètres</span>
            </a>
            
            <!-- Logout -->
            <a href="/logout" class="flex items-center px-3 py-3 text-red-600 rounded-xl hover:bg-red-50 transition duration-200">
                <div class="w-8 h-8 flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </div>
                <span class="font-normal text-base">Se déconnecter</span>
            </a>
        </div>
    </div>
</aside>

<!-- Overlay for mobile -->
<div class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- Mobile menu button -->
<button class="fixed top-4 left-4 z-50 lg:hidden bg-white border border-gray-200 text-gray-700 p-2 rounded-xl shadow-md hover:shadow-lg transition duration-200" onclick="toggleSidebar()">
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