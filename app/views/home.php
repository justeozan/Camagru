<?php require_once "../app/views/partials/header.php"; ?>

<!-- Layout container -->
<div class="flex h-screen">
    <?php require_once "../app/views/partials/sidebar.php"; ?>
    
    <!-- Main content area -->
    <div class="flex-1 lg:ml-64 overflow-auto bg-gray-50">
        <main class="max-w-2xl mx-auto px-4 py-6">
                
                <!-- Feed Posts -->
                <div class="space-y-6">
                    <?php if (isset($posts) && !empty($posts)): ?>
                        <?php foreach ($posts as $post): ?>
                            <!-- Post Card -->
                            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden" data-post-id="<?= $post['id'] ?>">
                                <!-- Post Header -->
                                <div class="flex items-center justify-between p-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                            <span class="text-white font-semibold text-sm">
                                                <?= strtoupper(substr($post['username'], 0, 1)) ?>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-sm">@<?= htmlspecialchars($post['username']) ?></p>
                                            <p class="text-xs text-gray-500"><?= date('d/m/Y', strtotime($post['created_at'])) ?></p>
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
                                        src="<?= htmlspecialchars($post['image_path']) ?>" 
                                        alt="Photo par <?= htmlspecialchars($post['username']) ?>"
                                        class="w-full h-full object-cover cursor-pointer"
                                        onclick="openModal('<?= htmlspecialchars($post['image_path']) ?>', '<?= htmlspecialchars($post['username']) ?>')"
                                    >
                                </div>
                                
                                <!-- Post Actions -->
                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-4">
                                            <!-- Like button -->
                                            <?php if (isset($_SESSION['user_id'])): ?>
                                                <button onclick="toggleLike(<?= $post['id'] ?>)" 
                                                        class="like-btn flex items-center space-x-1 hover:scale-105 transition duration-200"
                                                        data-post-id="<?= $post['id'] ?>"
                                                        data-liked="<?= $post['user_has_liked'] > 0 ? 'true' : 'false' ?>">
                                                    <svg class="w-6 h-6 <?= $post['user_has_liked'] > 0 ? 'text-red-500 fill-current' : 'text-gray-700' ?> hover:text-red-500" 
                                                         fill="<?= $post['user_has_liked'] > 0 ? 'currentColor' : 'none' ?>" 
                                                         stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                    </svg>
                                                </button>
                                            <?php else: ?>
                                                <button onclick="showLoginAlert()" class="flex items-center space-x-1 hover:scale-105 transition duration-200">
                                                    <svg class="w-6 h-6 text-gray-700 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                    </svg>
                                                </button>
                                            <?php endif; ?>
                                            
                                            <!-- Comment button -->
                                            <button onclick="toggleComments(<?= $post['id'] ?>)" class="flex items-center space-x-1 hover:scale-105 transition duration-200">
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
                                    <p class="font-semibold text-sm mb-2 likes-count" data-post-id="<?= $post['id'] ?>">
                                        <?= $post['likes_count'] ?> J'aime
                                    </p>
                                    
                                    <!-- Caption -->
                                    <div class="text-sm">
                                        <span class="font-semibold">@<?= htmlspecialchars($post['username']) ?></span>
                                        <span class="ml-1"><?= htmlspecialchars($post['caption'] ?? '') ?></span>
                                    </div>
                                    
                                    <!-- Comments -->
                                    <button onclick="toggleComments(<?= $post['id'] ?>)" class="text-gray-500 text-sm mt-1 hover:text-gray-700">
                                        <?php if ($post['comments_count'] > 0): ?>
                                            Voir les <?= $post['comments_count'] ?> commentaires
                                        <?php else: ?>
                                            Voir les commentaires
                                        <?php endif; ?>
                                    </button>
                                    
                                    <!-- Comments section (hidden by default) -->
                                    <div id="comments-<?= $post['id'] ?>" class="comments-section hidden mt-3 pt-3 border-t border-gray-100">
                                        <div class="space-y-2 mb-3" id="comments-list-<?= $post['id'] ?>">
                                            <!-- Comments will be loaded here -->
                                        </div>
                                    </div>
                                    
                                    <!-- Add comment -->
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                    <div class="flex items-center mt-3 pt-3 border-t border-gray-100">
                                        <input 
                                            type="text" 
                                            id="comment-input-<?= $post['id'] ?>"
                                            placeholder="Ajouter un commentaire..." 
                                            class="flex-1 text-sm placeholder-gray-400 border-none outline-none focus:ring-0"
                                            onkeypress="handleCommentSubmit(event, <?= $post['id'] ?>)"
                                        >
                                        <button onclick="submitComment(<?= $post['id'] ?>)" class="text-blue-600 font-semibold text-sm hover:text-blue-700">
                                            Publier
                                        </button>
                                    </div>
                                    <?php else: ?>
                                    <div class="flex items-center mt-3 pt-3 border-t border-gray-100">
                                        <input 
                                            type="text" 
                                            placeholder="Connectez-vous pour commenter..." 
                                            class="flex-1 text-sm placeholder-gray-400 border-none outline-none focus:ring-0 cursor-not-allowed"
                                            onclick="showLoginAlert()"
                                            readonly
                                        >
                                        <button onclick="showLoginAlert()" class="text-gray-400 font-semibold text-sm">
                                            Publier
                                        </button>
                                    </div>
                                    <?php endif; ?>
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
                            <a href="/create" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Prendre une photo
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Indicateur de chargement et fin de contenu -->
                <div id="loading-indicator" class="hidden flex justify-center items-center py-8">
                    <div class="flex items-center space-x-3">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                        <span class="text-gray-600">Chargement des posts...</span>
                    </div>
                </div>
                
                <div id="end-of-content" class="hidden text-center py-8">
                    <div class="text-gray-500">
                        <p class="text-lg">✨ Vous avez tout vu !</p>
                        <p class="text-sm mt-1">Il n'y a plus de posts à charger.</p>
                    </div>
                </div>
                
                <!-- Pagination fallback (masquée par défaut avec infinite scroll) -->
                <div id="pagination-fallback" class="hidden">
                    <?php if (isset($pages) && $pages > 1): ?>
                    <div class="flex justify-center space-x-2 mt-8">
                        <?php for ($i = 1; $i <= $pages; $i++): ?>
                            <a href="?page=<?= $i ?>" 
                               class="px-4 py-2 rounded-lg font-medium transition duration-200 <?= ($i == $page) ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                    <?php endif; ?>
                </div>
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
// Gestion de la modal d'image
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

// Fermer la modal avec Échap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// Fermer la modal en cliquant sur l'arrière-plan
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Gestion des likes
function toggleLike(postId) {
    const likeBtn = document.querySelector(`[data-post-id="${postId}"].like-btn`);
    const isLiked = likeBtn.dataset.liked === 'true';
    
    fetch('/post/like', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `post_id=${postId}&action=${isLiked ? 'unlike' : 'like'}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mettre à jour l'état du bouton
            likeBtn.dataset.liked = data.liked ? 'true' : 'false';
            const svg = likeBtn.querySelector('svg');
            
            if (data.liked) {
                svg.classList.remove('text-gray-700');
                svg.classList.add('text-red-500', 'fill-current');
                svg.setAttribute('fill', 'currentColor');
            } else {
                svg.classList.remove('text-red-500', 'fill-current');
                svg.classList.add('text-gray-700');
                svg.setAttribute('fill', 'none');
            }
            
            // Mettre à jour le compteur
            const likesCount = document.querySelector(`.likes-count[data-post-id="${postId}"]`);
            likesCount.textContent = `${data.likes_count} J'aime`;
        }
    })
    .catch(error => {
        console.error('Erreur lors de la mise à jour du like:', error);
    });
}

// Gestion des commentaires
function toggleComments(postId) {
    const commentsSection = document.getElementById(`comments-${postId}`);
    
    if (commentsSection.classList.contains('hidden')) {
        // Charger et afficher les commentaires
        loadComments(postId);
        commentsSection.classList.remove('hidden');
        
        // Focus automatiquement le champ de commentaire après un court délai
        setTimeout(() => {
            const commentInput = document.getElementById(`comment-input-${postId}`);
            if (commentInput) {
                commentInput.focus();
            }
        }, 100);
    } else {
        // Masquer les commentaires
        commentsSection.classList.add('hidden');
    }
}

function loadComments(postId) {
    fetch(`/post/comments?post_id=${postId}`)
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const commentsList = document.getElementById(`comments-list-${postId}`);
            commentsList.innerHTML = '';
            
            if (data.comments && data.comments.length > 0) {
                data.comments.forEach(comment => {
                    const commentElement = document.createElement('div');
                    commentElement.className = 'flex items-start space-x-2 text-sm';
                    commentElement.innerHTML = `
                        <span class="font-semibold">@${comment.username}</span>
                        <span class="flex-1">${comment.content}</span>
                        <span class="text-xs text-gray-500">${formatDate(comment.created_at)}</span>
                    `;
                    commentsList.appendChild(commentElement);
                });
            } else {
                commentsList.innerHTML = '<p class="text-gray-500 text-sm">Aucun commentaire pour le moment.</p>';
            }
        } else {
            const commentsList = document.getElementById(`comments-list-${postId}`);
            commentsList.innerHTML = '<p class="text-red-500 text-sm">Erreur lors du chargement des commentaires.</p>';
        }
    })
    .catch(error => {
        const commentsList = document.getElementById(`comments-list-${postId}`);
        commentsList.innerHTML = '<p class="text-red-500 text-sm">Erreur de connexion.</p>';
    });
}

function submitComment(postId) {
    const input = document.getElementById(`comment-input-${postId}`);
    const content = input.value.trim();
    
    if (!content) return;
    
    fetch('/post/comment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `post_id=${postId}&content=${encodeURIComponent(content)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            input.value = '';
            
            // Recharger les commentaires si la section est visible
            const commentsSection = document.getElementById(`comments-${postId}`);
            if (!commentsSection.classList.contains('hidden')) {
                loadComments(postId);
            }
            
            // Mettre à jour le compteur de commentaires dans le bouton
            updateCommentsButton(postId);
        }
    })
    .catch(error => {
        console.error('Erreur lors de l\'ajout du commentaire:', error);
    });
}

// Nouvelle fonction pour mettre à jour le bouton commentaires
function updateCommentsButton(postId) {
    fetch(`/post/comments?post_id=${postId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const commentsButton = document.querySelector(`button[onclick="toggleComments(${postId})"]`);
            if (commentsButton) {
                const count = data.comments.length;
                if (count > 0) {
                    commentsButton.textContent = `Voir les ${count} commentaires`;
                } else {
                    commentsButton.textContent = 'Voir les commentaires';
                }
            }
        }
    })
    .catch(error => {
        // Erreur silencieuse pour la mise à jour du bouton
    });
}

function handleCommentSubmit(event, postId) {
    if (event.key === 'Enter') {
        submitComment(postId);
    }
}

function focusComment(postId) {
    const input = document.getElementById(`comment-input-${postId}`);
    if (input) {
        input.focus();
    }
}

function showLoginAlert() {
    alert('Veuillez vous connecter pour interagir avec les publications.');
}

// Utilitaire pour formater les dates
function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);
    
    if (diffInSeconds < 60) return 'maintenant';
    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m`;
    if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h`;
    return date.toLocaleDateString('fr-FR');
}

// ==================== PAGINATION INFINIE ====================
// Variables globales pour la pagination infinie
let currentPage = <?= $page ?>;
let isLoading = false;
let hasMorePosts = <?= ($total > $perPage * $page) ? 'true' : 'false' ?>;
let totalPosts = <?= $total ?>;

// Initialisation de la pagination infinie
document.addEventListener('DOMContentLoaded', function() {
    initInfiniteScroll();
});

function initInfiniteScroll() {
    // Vérifier si l'Intersection Observer est supporté (compatible Firefox 55+, Chrome 51+)
    if (!window.IntersectionObserver) {
        // Fallback vers pagination classique si pas de support
        document.getElementById('pagination-fallback').classList.remove('hidden');
        return;
    }

    const postsContainer = document.querySelector('.space-y-6');
    const loadingIndicator = document.getElementById('loading-indicator');
    const endOfContent = document.getElementById('end-of-content');
    
    // Créer un élément sentinelle pour détecter le scroll
    const sentinel = document.createElement('div');
    sentinel.id = 'scroll-sentinel';
    sentinel.style.height = '1px';
    postsContainer.parentNode.insertBefore(sentinel, loadingIndicator);
    
    // Configuration de l'Intersection Observer
    const observerOptions = {
        root: null, // Viewport
        rootMargin: '100px', // Charger 100px avant d'atteindre le bas
        threshold: 0.1
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting && hasMorePosts && !isLoading) {
                loadMorePosts();
            }
        });
    }, observerOptions);
    
    // Commencer à observer la sentinelle
    observer.observe(sentinel);
    
    // Afficher l'indicateur de fin si pas de posts supplémentaires
    if (!hasMorePosts && totalPosts > 0) {
        endOfContent.classList.remove('hidden');
    }
}

async function loadMorePosts() {
    if (isLoading || !hasMorePosts) return;
    
    isLoading = true;
    const loadingIndicator = document.getElementById('loading-indicator');
    const endOfContent = document.getElementById('end-of-content');
    const postsContainer = document.querySelector('.space-y-6');
    
    // Afficher l'indicateur de chargement
    loadingIndicator.classList.remove('hidden');
    endOfContent.classList.add('hidden');
    
    try {
        const nextPage = currentPage + 1;
        const response = await fetch(`/home/loadMorePosts?page=${nextPage}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Cache-Control': 'no-cache'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success && data.posts.length > 0) {
            // Ajouter les nouveaux posts au DOM
            data.posts.forEach(postHtml => {
                postsContainer.insertAdjacentHTML('beforeend', postHtml);
            });
            
            // Mettre à jour les variables de pagination
            currentPage = data.currentPage;
            hasMorePosts = data.hasMore;
            totalPosts = data.totalPosts;
            
            // Masquer l'indicateur de chargement
            loadingIndicator.classList.add('hidden');
            
            // Afficher la fin du contenu si plus de posts
            if (!hasMorePosts) {
                endOfContent.classList.remove('hidden');
            }
            
        } else {
            // Aucun post supplémentaire trouvé
            hasMorePosts = false;
            loadingIndicator.classList.add('hidden');
            endOfContent.classList.remove('hidden');
        }
        
    } catch (error) {
        // Masquer l'indicateur de chargement
        loadingIndicator.classList.add('hidden');
        
        // Afficher un message d'erreur temporaire
        const errorMessage = document.createElement('div');
        errorMessage.className = 'text-center py-4 text-red-600';
        errorMessage.innerHTML = `
            <p>Erreur lors du chargement des posts.</p>
            <button onclick="retryLoadMore()" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Réessayer
            </button>
        `;
        postsContainer.parentNode.insertBefore(errorMessage, loadingIndicator);
        
        // Supprimer le message d'erreur après 5 secondes
        setTimeout(() => {
            if (errorMessage.parentNode) {
                errorMessage.parentNode.removeChild(errorMessage);
            }
        }, 5000);
    }
    
    isLoading = false;
}

function retryLoadMore() {
    // Supprimer les messages d'erreur existants
    const errorMessages = document.querySelectorAll('.text-red-600');
    errorMessages.forEach(msg => {
        if (msg.parentNode) {
            msg.parentNode.removeChild(msg);
        }
    });
    
    // Relancer le chargement
    loadMorePosts();
}
</script>

<?php require_once "../app/views/partials/footer.php"; ?>