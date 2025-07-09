<?php require_once "../app/views/partials/header.php"; ?>

<!-- Layout container -->
<div class="flex h-screen">
    <?php require_once "../app/views/partials/sidebar.php"; ?>
    
    <!-- Main content area -->
    <div class="flex-1 lg:ml-64 overflow-auto bg-gray-50">
        <main class="max-w-6xl mx-auto px-4 py-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Mes Photos</h1>
                    <p class="text-gray-600 mt-1">
                        <?= $total ?> photo<?= $total > 1 ? 's' : '' ?> publiée<?= $total > 1 ? 's' : '' ?>
                    </p>
                </div>
                <a href="/create" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nouvelle photo
                </a>
            </div>

            <?php if (!empty($posts)): ?>
                <!-- Grid des photos -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
                    <?php foreach ($posts as $post): ?>
                        <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition duration-200 group">
                            <div class="aspect-square relative">
                                <img 
                                    src="<?= htmlspecialchars($post['image_path']) ?>" 
                                    alt="Ma photo du <?= date('d/m/Y', strtotime($post['created_at'])) ?>" 
                                    class="w-full h-full object-cover cursor-pointer"
                                    onclick="openPhotoModal('<?= htmlspecialchars($post['image_path']) ?>', <?= $post['id'] ?>, '<?= htmlspecialchars($post['caption'] ?? '') ?>', '<?= date('d/m/Y à H:i', strtotime($post['created_at'])) ?>', <?= $post['likes_count'] ?>, <?= $post['comments_count'] ?>)"
                                >
                                
                                <!-- Overlay avec statistiques -->
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition duration-200 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                    <div class="flex space-x-6 text-white">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                            </svg>
                                            <?= $post['likes_count'] ?>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                                            </svg>
                                            <?= $post['comments_count'] ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bouton de suppression -->
                                <button 
                                    onclick="confirmDelete(<?= $post['id'] ?>); event.stopPropagation();"
                                    class="absolute top-2 right-2 bg-red-500 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition duration-200 hover:bg-red-600"
                                    title="Supprimer cette photo"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($pages > 1): ?>
                <div class="flex justify-center space-x-2">
                    <?php for ($i = 1; $i <= $pages; $i++): ?>
                        <a href="/post/myGallery?page=<?= $i ?>" 
                           class="px-4 py-2 rounded-lg font-medium transition duration-200 <?= ($i == $page) ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>

            <?php else: ?>
                <!-- État vide -->
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Aucune photo</h3>
                    <p class="text-gray-500 mb-6">Vous n'avez pas encore publié de photos.</p>
                    <a href="/create" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Prendre ma première photo
                    </a>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<!-- Modal pour voir la photo en grand -->
<div id="photoModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
    <div class="max-w-4xl max-h-full relative">
        <button 
            onclick="closePhotoModal()"
            class="absolute top-4 right-4 text-white hover:text-gray-300 z-10"
        >
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img id="modalPhoto" src="" alt="" class="max-w-full max-h-full object-contain">
        <div class="absolute bottom-4 left-4 text-white">
            <p id="modalCaption" class="font-semibold mb-1"></p>
            <p id="modalDate" class="text-sm opacity-75"></p>
            <div class="flex space-x-4 mt-2 text-sm">
                <span id="modalLikes"></span>
                <span id="modalComments"></span>
            </div>
        </div>
        <button 
            id="modalDeleteBtn"
            onclick="confirmDelete(0)"
            class="absolute bottom-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-200"
        >
            Supprimer
        </button>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-60 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl p-6 max-w-md w-full">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Supprimer cette photo ?</h3>
        <p class="text-gray-600 mb-6">Cette action est irréversible. La photo sera définitivement supprimée.</p>
        
        <div class="flex space-x-4">
            <button 
                onclick="closeDeleteModal()"
                class="flex-1 bg-gray-200 text-gray-800 py-2 rounded-lg hover:bg-gray-300 transition duration-200"
            >
                Annuler
            </button>
            <button 
                id="confirmDeleteBtn"
                onclick="deletePhoto()"
                class="flex-1 bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition duration-200"
            >
                Supprimer
            </button>
        </div>
    </div>
</div>

<script>
let currentPhotoId = null;

function openPhotoModal(imagePath, postId, caption, date, likes, comments) {
    document.getElementById('modalPhoto').src = imagePath;
    document.getElementById('modalCaption').textContent = caption || 'Pas de légende';
    document.getElementById('modalDate').textContent = date;
    document.getElementById('modalLikes').textContent = `❤️ ${likes}`;
    document.getElementById('modalComments').textContent = `💬 ${comments}`;
    document.getElementById('modalDeleteBtn').onclick = () => confirmDelete(postId);
    document.getElementById('photoModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closePhotoModal() {
    document.getElementById('photoModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function confirmDelete(postId) {
    currentPhotoId = postId;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    currentPhotoId = null;
}

function deletePhoto() {
    if (!currentPhotoId) return;
    
    fetch('/post/delete', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            post_id: currentPhotoId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeDeleteModal();
            closePhotoModal();
            window.location.reload();
        } else {
            alert('Erreur lors de la suppression : ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur de connexion');
    });
}

// Fermer les modals avec Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePhotoModal();
        closeDeleteModal();
    }
});

// Fermer les modals en cliquant sur l'arrière-plan
document.getElementById('photoModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePhotoModal();
    }
});

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>

<?php require_once "../app/views/partials/footer.php"; ?>