<aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-200 transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 z-50">
  <!-- Logo Section -->
  <div class="flex items-center justify-between h-20 px-6 border-b border-gray-100">
    <h1 class="text-2xl font-bold text-blue-600">CAMAGRU</h1>
    <!-- Close button for mobile -->
    <button class="lg:hidden text-gray-600 hover:text-gray-800" onclick="toggleSidebar()">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>

  <div class="flex flex-col justify-between overflow-y-auto" style="height: calc(100vh - 5rem)">      
    <!-- Main navigation -->
    <nav class="flex-1 pt-8">
      <ul class="space-y-1 px-3">
        <!-- Home -->
        <li>
          <a href="/" class="flex items-center px-3 py-3 text-gray-800 rounded-xl hover:bg-gray-50 transition duration-200">
            <span class="w-8 h-8 flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
            </span>
            <span class="font-normal text-base">Accueil</span>
          </a>
        </li>

        <!-- Create -->
        <li>
          <a href="/create" class="flex items-center px-3 py-3 text-gray-800 rounded-xl hover:bg-gray-50 transition duration-200">
            <span class="w-8 h-8 flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
            </span>
            <span class="font-normal text-base">Créer</span>
          </a>
        </li>

        <!-- User-specific links -->
        <?php if (isset($_SESSION['user_id'])): ?>
          <li>
            <a href="/user/account" class="flex items-center px-3 py-3 text-gray-800 rounded-xl hover:bg-gray-50 transition duration-200">
              <span class="w-8 h-8 flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </span>
              <span class="font-normal text-base">Mon compte</span>
            </a>
          </li>

          <li>
            <a href="/post/myGallery" class="flex items-center px-3 py-3 text-gray-800 rounded-xl hover:bg-gray-50 transition duration-200">
              <span class="w-8 h-8 flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
              </span>
              <span class="font-normal text-base">Mes Photos</span>
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </nav>

    <!-- Bottom Section -->
    <div class="p-3 border-t border-gray-100">
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="/user/logout" class="flex items-center px-3 py-3 text-red-600 rounded-xl hover:bg-red-50 transition duration-200">
          <span class="w-8 h-8 flex items-center justify-center mr-4">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
          </span>
          <span class="font-normal text-base">Se déconnecter</span>
        </a>
      <?php else: ?>
        <a href="/user/login" class="flex items-center px-3 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition duration-200">
          <span class="w-8 h-8 flex items-center justify-center mr-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
            </svg>
          </span>
          <span class="font-normal text-base">Se connecter</span>
        </a>
      <?php endif; ?>
    </div>
  </div>
</aside>

<!-- Overlay for mobile -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

<!-- Mobile menu button -->
<button id="mobileMenuButton" class="fixed top-4 left-4 z-50 lg:hidden bg-white border border-gray-200 text-gray-700 p-2 rounded-xl shadow-md hover:shadow-lg transition duration-200" onclick="toggleSidebar()">
  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
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
    const overlay = document.getElementById('sidebarOverlay');
    const mobileMenuButton = document.getElementById('mobileMenuButton');

    if (!sidebar.contains(event.target) && window.innerWidth < 1024) {
      sidebar.classList.add('-translate-x-full');
      overlay.classList.add('hidden');
      mobileMenuButton.classList.remove('hidden');
    }
  });

  window.addEventListener('resize', function() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const mobileMenuButton = document.getElementById('mobileMenuButton');

    if (window.innerWidth >= 1024) {
      sidebar.classList.remove('-translate-x-full');
      overlay.classList.add('hidden');
      mobileMenuButton.classList.remove('hidden');
    }
  });
</script>
