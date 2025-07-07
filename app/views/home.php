
<?php require_once "../app/views/partials/header.php"; ?>

<!-- Layout container -->
<div class="flex h-screen">
    <?php require_once "../app/views/partials/sidebar.php"; ?>
    
    <!-- Main content area -->
    <div class="flex-1 lg:ml-64 overflow-auto">
        <main class="container mx-auto px-4 py-8">
            <!-- Hero Section -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-6xl font-bold text-gray-800 mb-4">
                    Camagru
                </h1>
                <p class="text-xl text-gray-600 mb-8">
                    Partagez vos moments avec style
                </p>
                <a href="/camera" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                    Prendre une photo
                </a>
            </div>

            <!-- Photos Gallery -->
            <section class="mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">
                    Galerie publique
                </h2>
                
                <!-- Gallery Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php if (isset($photos) && !empty($photos)): ?>
                        <?php foreach ($photos as $photo): ?>
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                                <div class="aspect-square relative group">
                                    <img 
                                        src="<?= htmlspecialchars($photo['image_path']) ?>" 
                                        alt="Photo par <?= htmlspecialchars($photo['username']) ?>"
                                        class="w-full h-full object-cover"
                                    >
                                    <!-- Overlay with user info -->
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition duration-300 flex items-end">
                                        <div class="p-4 text-white opacity-0 group-hover:opacity-100 transition duration-300">
                                            <p class="font-semibold">@<?= htmlspecialchars($photo['username']) ?></p>
                                            <p class="text-sm"><?= date('d/m/Y', strtotime($photo['created_at'])) ?></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Photo interactions -->
                                <div class="p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <!-- Like button -->
                                            <button class="flex items-center space-x-1 text-gray-600 hover:text-red-500 transition duration-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                                <span><?= $photo['likes_count'] ?? 0 ?></span>
                                            </button>
                                            
                                            <!-- Comment button -->
                                            <button class="flex items-center space-x-1 text-gray-600 hover:text-blue-500 transition duration-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                <span><?= $photo['comments_count'] ?? 0 ?></span>
                                            </button>
                                        </div>
                                        
                                        <!-- View full size -->
                                        <button 
                                            onclick="openModal('<?= htmlspecialchars($photo['image_path']) ?>', '<?= htmlspecialchars($photo['username']) ?>')"
                                            class="text-gray-600 hover:text-gray-800 transition duration-200"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Empty state -->
                        <div class="col-span-full text-center py-12">
                            <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-500 mb-2">Aucune photo pour le moment</h3>
                            <p class="text-gray-400">Soyez le premier à partager une photo !</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Load More Button -->
                <?php if (isset($hasMore) && $hasMore): ?>
                    <div class="text-center mt-8">
                        <button 
                            onclick="loadMore()"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-lg transition duration-300"
                        >
                            Charger plus de photos
                        </button>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>
</div>

<!-- Modal for full size image -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
    <div class="max-w-4xl max-h-full relative">
        <button 
            onclick="closeModal()"
            class="absolute top-4 right-4 text-white hover:text-gray-300 z-10"
        >
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain">
        <div class="absolute bottom-4 left-4 text-white">
            <p id="modalUsername" class="font-semibold"></p>
        </div>
    </div>
</div>

<script>
function openModal(imagePath, username) {
    document.getElementById('modalImage').src = imagePath;
    document.getElementById('modalUsername').textContent = '@' + username;
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// Close modal on background click
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

function loadMore() {
    // Implement AJAX call to load more photos
    // This would need to be connected to your backend
}
</script>

<?php require_once "../app/views/partials/footer.php"; ?>