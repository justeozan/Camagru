<?php require_once "../app/views/partials/header.php"; ?>

<!-- Layout container -->
<div class="flex h-screen">
    <?php require_once "../app/views/partials/sidebar.php"; ?>
    
    <!-- Main content area -->
    <div class="flex-1 lg:ml-64 overflow-auto bg-gray-50">
        <main class="max-w-2xl mx-auto px-4 py-6">
                
                <!-- Feed Posts -->
                <div class="space-y-6">
                    <?php if (isset($photos) && !empty($photos)): ?>
                        <?php foreach ($photos as $photo): ?>
                            <!-- Post Card -->
                            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                                <!-- Post Header -->
                                <div class="flex items-center justify-between p-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                            <span class="text-white font-semibold text-sm">
                                                <?= strtoupper(substr($photo['username'], 0, 1)) ?>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-sm">@<?= htmlspecialchars($photo['username']) ?></p>
                                            <p class="text-xs text-gray-500"><?= date('d/m/Y', strtotime($photo['created_at'])) ?></p>
                                        </div>
                                    </div>
                                    <button class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                        </svg>
                                    </button>
                                </div>
                                
                                <!-- Post Image -->
                                <div class="aspect-square relative">
                                    <img 
                                        src="<?= htmlspecialchars($photo['image_path']) ?>" 
                                        alt="Photo par <?= htmlspecialchars($photo['username']) ?>"
                                        class="w-full h-full object-cover cursor-pointer"
                                        onclick="openModal('<?= htmlspecialchars($photo['image_path']) ?>', '<?= htmlspecialchars($photo['username']) ?>')"
                                    >
                                </div>
                                
                                <!-- Post Actions -->
                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-4">
                                            <!-- Like button -->
                                            <button class="flex items-center space-x-1 hover:scale-105 transition duration-200">
                                                <svg class="w-6 h-6 text-gray-700 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                            </button>
                                            
                                            <!-- Comment button -->
                                            <button class="flex items-center space-x-1 hover:scale-105 transition duration-200">
                                                <svg class="w-6 h-6 text-gray-700 hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                            </button>
                                            
                                            <!-- Share button -->
                                            <button class="flex items-center space-x-1 hover:scale-105 transition duration-200">
                                                <svg class="w-6 h-6 text-gray-700 hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <!-- Save button -->
                                        <button class="hover:scale-105 transition duration-200">
                                            <svg class="w-6 h-6 text-gray-700 hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <!-- Likes count -->
                                    <p class="font-semibold text-sm mb-2"><?= $photo['likes_count'] ?? 0 ?> J'aime</p>
                                    
                                    <!-- Caption -->
                                    <div class="text-sm">
                                        <span class="font-semibold">@<?= htmlspecialchars($photo['username']) ?></span>
                                        <span class="ml-1"><?= htmlspecialchars($photo['caption'] ?? '') ?></span>
                                    </div>
                                    
                                    <!-- Comments -->
                                    <?php if (isset($photo['comments_count']) && $photo['comments_count'] > 0): ?>
                                    <button class="text-gray-500 text-sm mt-1 hover:text-gray-700">
                                        Voir les <?= $photo['comments_count'] ?> commentaires
                                    </button>
                                    <?php endif; ?>
                                    
                                    <!-- Add comment -->
                                    <div class="flex items-center mt-3 pt-3 border-t border-gray-100">
                                        <input 
                                            type="text" 
                                            placeholder="Ajouter un commentaire..." 
                                            class="flex-1 text-sm placeholder-gray-400 border-none outline-none focus:ring-0"
                                        >
                                        <button class="text-blue-600 font-semibold text-sm hover:text-blue-700">
                                            Publier
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Empty state -->
                        <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Aucune photo pour le moment</h3>
                            <p class="text-gray-500 mb-6">Commencez à partager vos moments !</p>
                            <a href="/camera" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Prendre une photo
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
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