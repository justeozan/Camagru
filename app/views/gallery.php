<?php require_once "../app/views/partials/header.php"; ?>

<!-- Layout container -->
<div class="flex h-screen">
    <?php require_once "../app/views/partials/sidebar.php"; ?>
    
    <!-- Main content area -->
    <div class="flex-1 lg:ml-64 overflow-auto bg-gray-50">
        <main class="max-w-6xl mx-auto px-4 py-6">
            <h1 class="text-3xl font-bold text-center mb-8 text-gray-900">Galerie publique</h1>

            <?php if (!empty($posts)): ?>
                <!-- Grid des posts -->
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                    <?php foreach ($posts as $post): ?>
                        <div class="bg-white shadow-lg rounded-xl overflow-hidden hover:shadow-xl transition duration-300">
                            <div class="aspect-square relative">
                                <img src="<?= htmlspecialchars($post['image_path']) ?>" 
                                     alt="Post par <?= htmlspecialchars($post['username']) ?>" 
                                     class="w-full h-full object-cover cursor-pointer"
                                     onclick="openModal('<?= htmlspecialchars($post['image_path']) ?>', '<?= htmlspecialchars($post['username']) ?>')">
                            </div>
                            <div class="p-4">
                                <div class="flex items-center space-x-2 mb-2">
                                    <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold text-xs">
                                            <?= strtoupper(substr($post['username'], 0, 1)) ?>
                                        </span>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">@<?= htmlspecialchars($post['username']) ?></p>
                                </div>
                                <p class="text-xs text-gray-500 mb-2"><?= date('d/m/Y à H:i', strtotime($post['created_at'])) ?></p>
                                <?php if (!empty($post['caption'])): ?>
                                    <p class="text-sm text-gray-700 line-clamp-2"><?= htmlspecialchars($post['caption']) ?></p>
                                <?php endif; ?>
                                <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                                    <span class="text-xs text-gray-500">❤️ <?= $post['likes_count'] ?></span>
                                    <span class="text-xs text-gray-500">💬 <?= $post['comments_count'] ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <div class="flex justify-center space-x-2">
                    <?php for ($i = 1; $i <= $pages; $i++): ?>
                                            <a href="/gallery?page=<?= $i ?>" 
                       class="px-4 py-2 rounded-lg font-medium transition duration-200 <?= ($i == $page) ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                        <?= $i ?>
                    </a>
                    <?php endfor; ?>
                </div>
            <?php else: ?>
                <!-- État vide -->
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Aucune photo dans la galerie</h3>
                    <p class="text-gray-500">Les photos postées par les utilisateurs apparaîtront ici.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<!-- Modal pour image en grand -->
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

// Fermer modal avec Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// Fermer modal en cliquant sur l'arrière-plan
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

<?php require_once "../app/views/partials/footer.php"; ?>
