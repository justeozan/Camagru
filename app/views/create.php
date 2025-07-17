<?php require_once "../app/views/partials/header.php"; ?>

<!-- Layout container -->
<div class="flex h-screen">
  <?php require_once "../app/views/partials/sidebar.php"; ?>

  <!-- Main content area -->
  <div class="flex-1 lg:ml-64 overflow-auto bg-gray-50">
    <main class="max-w-4xl mx-auto px-4 py-6">
      <!-- Page Header -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Créer une nouvelle photo</h1>
        <p class="text-gray-600">Prenez une photo avec votre webcam ou téléchargez une image</p>
      </div>

      <!-- Step 1: Choix de la source -->
      <div id="step1" class="bg-white rounded-xl border border-gray-200 p-8 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6 text-center">Choisissez votre source</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Webcam -->
          <div class="text-center">
            <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Webcam</h3>
            <p class="text-gray-600 mb-4">Prenez une photo en direct</p>
            <button onclick="startCamera()" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
              Activer la caméra
            </button>
          </div>
          <!-- Upload fichier -->
          <div class="text-center">
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Télécharger</h3>
            <p class="text-gray-600 mb-4">Choisir depuis vos fichiers</p>
            <label class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 cursor-pointer">
              Choisir un fichier
              <input type="file" accept="image/*" class="hidden" onchange="loadImageFromFile(this)">
            </label>
          </div>
        </div>
      </div>

      <!-- Step 2: Interface caméra + overlay -->
      <div id="cameraSection" class="bg-white rounded-xl border border-gray-200 p-6 mb-6 hidden">
        <div class="text-center mb-6">
          <h2 class="text-xl font-semibold">Prendre une photo</h2>
          <p class="text-gray-600">Choisissez un overlay puis cliquez sur la vidéo pour le positionner</p>
        </div>

        <!-- Sélection d'overlays -->
        <div class="mb-6">
          <h3 class="mb-4">Choisir un overlay</h3>
          <div class="grid grid-cols-4 md:grid-cols-6 gap-3">
            <button id="overlay-none" class="overlay-btn border-2 p-3 rounded-lg bg-gray-50" onclick="selectOverlay(null)">
              Aucun
            </button>
            <button id="overlay-crown" class="overlay-btn border-2 p-3 rounded-lg" onclick="selectOverlay('crown.png')">
              <img src="/assets/overlays/crown.png" class="w-12 h-12 mx-auto">
              <p class="text-xs text-gray-600 mt-1">Couronne</p>
            </button>
            <button id="overlay-boom" class="overlay-btn border-2 p-3 rounded-lg" onclick="selectOverlay('boom.png')">
              <img src="/assets/overlays/boom.png" class="w-12 h-12 mx-auto">
              <p class="text-xs text-gray-600 mt-1">Boom</p>
            </button>
            <button id="overlay-cadre" class="overlay-btn border-2 p-3 rounded-lg" onclick="selectOverlay('cadre.png')">
              <img src="/assets/overlays/cadre.png" class="w-12 h-12 mx-auto">
              <p class="text-xs text-gray-600 mt-1">Cadre</p>
            </button>
            <button id="overlay-dragon" class="overlay-btn border-2 p-3 rounded-lg" onclick="selectOverlay('dragon.png')">
              <img src="/assets/overlays/dragon.png" class="w-12 h-12 mx-auto">
              <p class="text-xs text-gray-600 mt-1">Dragon</p>
            </button>
            <button id="overlay-frite" class="overlay-btn border-2 p-3 rounded-lg" onclick="selectOverlay('frite.png')">
              <img src="/assets/overlays/frite.png" class="w-12 h-12 mx-auto">
              <p class="text-xs text-gray-600 mt-1">Frite</p>
            </button>
            <button id="overlay-stitch" class="overlay-btn border-2 p-3 rounded-lg" onclick="selectOverlay('stitch.png')">
              <img src="/assets/overlays/stitch.png" class="w-12 h-12 mx-auto">
              <p class="text-xs text-gray-600 mt-1">Stitch</p>
            </button>
          </div>
          <div id="overlayStatus" class="mt-3 text-sm text-gray-500">Sélectionnez un overlay</div>
        </div>

        <!-- Contrôles position/taille -->
        <div id="positionControls" class="mb-6 hidden">
          <button onclick="moveOverlay(0,-10)">⬆️</button>
          <button onclick="moveOverlay(-10,0)">⬅️</button>
          <button onclick="resetOverlayPosition()">Centre</button>
          <button onclick="moveOverlay(10,0)">➡️</button>
          <button onclick="moveOverlay(0,10)">⬇️</button>
          <div class="mt-3">
            <button onclick="resizeOverlay(-10)">➖</button>
            <span id="overlaySize">80px</span>
            <button onclick="resizeOverlay(10)">➕</button>
          </div>
        </div>

        <!-- Vidéo + preview overlay -->
        <div class="flex justify-center mb-6">
          <div class="relative">
            <video id="video" width="640" height="480" autoplay class="rounded-lg border cursor-crosshair"></video>
            <img id="overlayPreview" class="absolute hidden pointer-events-none" style="z-index:10;">
          </div>
        </div>

        <div class="text-center space-x-4">
          <button id="captureBtn" onclick="capturePhoto()" disabled class="px-8 py-3 bg-blue-600 text-white rounded-lg disabled:bg-gray-400">📸 Capturer</button>
          <button onclick="stopCamera()" class="px-6 py-3 bg-gray-500 text-white rounded-lg">Annuler</button>
        </div>
      </div>

      <!-- Step 3: Éditeur (prévisualisation + légende) -->
      <div id="editorSection" class="bg-white rounded-xl border border-gray-200 p-6 hidden">
        <div class="text-center mb-6">
          <h2 class="text-xl font-semibold">Éditeur de photo</h2>
          <p class="text-gray-600">Vérifiez votre image puis ajoutez une légende</p>
        </div>
        <div class="flex gap-6">
          <!-- Canvas d’aperçu -->
          <div class="flex-1 flex justify-center">
            <canvas id="editorCanvas" class="border rounded-lg max-w-full h-auto"></canvas>
          </div>
          <!-- Légende & publication -->
          <div class="w-48">
            <textarea id="caption" placeholder="Légende..." rows="3" class="w-full border rounded mt-4 p-2"></textarea>
            <button onclick="savePhoto()" class="w-full bg-blue-600 text-white py-2 rounded mt-2">Publier la photo</button>
            <button onclick="resetEditor()" class="w-full bg-gray-500 text-white py-2 rounded mt-1">Recommencer</button>
          </div>
        </div>
      </div>

      <!-- Section miniatures des anciennes photos -->
      <?php if (!empty($userPosts)): ?>
      <div class="mt-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-xl font-semibold text-gray-900 mb-4">Mes photos récentes</h2>
          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <?php foreach ($userPosts as $post): ?>
            <div class="relative group">
              <img src="<?= htmlspecialchars($post['image_path']) ?>" 
                   alt="Photo de <?= htmlspecialchars($post['username']) ?>" 
                   class="w-full h-24 object-cover rounded-lg border border-gray-200 group-hover:border-blue-300 transition-colors">
              
              <!-- Overlay avec actions -->
              <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                <div class="flex space-x-2">
                  <!-- Bouton voir -->
                  <a href="/#post-<?= $post['id'] ?>" 
                     class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition-colors"
                     title="Voir la photo">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </a>
                  
                  <!-- Bouton supprimer -->
                  <button onclick="deletePhoto(<?= $post['id'] ?>)" 
                          class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg transition-colors"
                          title="Supprimer la photo">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </div>
              
              <!-- Informations de la photo -->
              <div class="mt-2 text-xs text-gray-500">
                <div class="flex items-center justify-between">
                  <span><?= date('d/m/Y', strtotime($post['created_at'])) ?></span>
                  <div class="flex space-x-2">
                    <span>❤️ <?= $post['likes_count'] ?></span>
                    <span>💬 <?= $post['comments_count'] ?></span>
                  </div>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          
          <!-- Lien vers la galerie complète -->
          <div class="mt-6 text-center">
            <a href="/post/myGallery" 
               class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium">
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              Voir toutes mes photos
            </a>
          </div>
        </div>
      </div>
      <?php endif; ?>

    </main>
  </div>
</div>

<script>
let stream = null,
    selectedOverlay = null,
    overlayPosition = { x: 0, y: 0 },
    overlaySize = { width: 80, height: 80 },
    editorCanvas, editorCtx, currentImage,
    rawImageData = null;

// 1) Caméra + overlay
async function startCamera() {
  stream = await navigator.mediaDevices.getUserMedia({ video:true });
  const video = document.getElementById('video');
  video.srcObject = stream;
  video.onloadedmetadata = () => setupOverlayPositioning();
  showSection('cameraSection');
  selectOverlay(null);
}
function stopCamera() {
  if (stream) stream.getTracks().forEach(t=>t.stop());
  showSection('step1');
}
function selectOverlay(file) {
  selectedOverlay = file;
  document.querySelectorAll('.overlay-btn').forEach(b=>b.classList.remove('border-blue-500','bg-blue-50'));
  if (file) {
    document.getElementById(`overlay-${file.replace('.png','')}`)
            .classList.add('border-blue-500','bg-blue-50');
    document.getElementById('positionControls').classList.remove('hidden');
    document.getElementById('overlayStatus').textContent = file;
    showOverlayPreview(file);
  } else {
    document.getElementById('overlay-none').classList.add('border-blue-500','bg-blue-50');
    document.getElementById('positionControls').classList.add('hidden');
    document.getElementById('overlayStatus').textContent = 'Aucun overlay';
    document.getElementById('overlayPreview').classList.add('hidden');
  }
  document.getElementById('captureBtn').disabled = false;
}
function showOverlayPreview(file) {
  const video = document.getElementById('video'),
        prev = document.getElementById('overlayPreview');
  prev.src = `/assets/overlays/${file}`;
  prev.classList.remove('hidden');
  overlayPosition = {
    x: (video.videoWidth - overlaySize.width) / 2,
    y: (video.videoHeight - overlaySize.height) / 2
  };
  updateOverlayPreviewPosition();
}
function setupOverlayPositioning() {
  document.getElementById('video').addEventListener('click', e => {
    if (!selectedOverlay) return;
    const video = e.currentTarget,
          r = video.getBoundingClientRect(),
          sx = video.videoWidth / r.width,
          sy = video.videoHeight / r.height,
          x = (e.clientX - r.left) * sx - overlaySize.width/2,
          y = (e.clientY - r.top ) * sy - overlaySize.height/2;
    overlayPosition.x = clamp(x, 0, video.videoWidth - overlaySize.width);
    overlayPosition.y = clamp(y, 0, video.videoHeight - overlaySize.height);
    updateOverlayPreviewPosition();
  });
}
function updateOverlayPreviewPosition() {
  const video = document.getElementById('video'),
        prev  = document.getElementById('overlayPreview'),
        r     = video.getBoundingClientRect(),
        sx    = r.width / video.videoWidth,
        sy    = r.height / video.videoHeight;
  prev.style.left   = (overlayPosition.x * sx)+'px';
  prev.style.top    = (overlayPosition.y * sy)+'px';
  prev.style.width  = (overlaySize.width * sx)+'px';
  prev.style.height = (overlaySize.height* sy)+'px';
}
function moveOverlay(dx,dy){ overlayPosition.x = clamp(overlayPosition.x+dx,0,640-overlaySize.width); overlayPosition.y = clamp(overlayPosition.y+dy,0,480-overlaySize.height); updateOverlayPreviewPosition(); }
function resetOverlayPosition(){ const v=document.getElementById('video'); overlayPosition={ x:(v.videoWidth-overlaySize.width)/2, y:(v.videoHeight-overlaySize.height)/2 }; updateOverlayPreviewPosition(); }
function resizeOverlay(d){ overlaySize.width=clamp(overlaySize.width+d,20,200); overlaySize.height=overlaySize.width; document.getElementById('overlaySize').textContent=overlaySize.width+'px'; updateOverlayPreviewPosition(); }
function clamp(v,min,max){ return v<min?min:(v>max?max:v); }

// 2) Capture → preview éditeur
function capturePhoto(){
  const video = document.getElementById('video'),
        canvas = document.createElement('canvas'),
        ctx    = canvas.getContext('2d');
  canvas.width  = video.videoWidth;
  canvas.height = video.videoHeight;
  // capture brute
  ctx.drawImage(video,0,0);
  rawImageData = canvas.toDataURL();
  // ajout overlay pour préview
  if (selectedOverlay) {
    const img = new Image();
    img.onload = () => {
      ctx.drawImage(img, overlayPosition.x, overlayPosition.y, overlaySize.width, overlaySize.height);
      goToEditor(canvas.toDataURL());
    };
    img.src = `/assets/overlays/${selectedOverlay}`;
  } else {
    goToEditor(rawImageData);
  }
  stopCamera();
}
function loadImageFromFile(input){
  if (!input.files[0]) return;
  const reader = new FileReader();
  reader.onload = e => { rawImageData = e.target.result; goToEditor(rawImageData); };
  reader.readAsDataURL(input.files[0]);
}
function goToEditor(dataUrl){
  editorCanvas = document.getElementById('editorCanvas');
  editorCtx    = editorCanvas.getContext('2d');
  currentImage = new Image();
  currentImage.onload = () => {
    let w = currentImage.width, h = currentImage.height, max=600;
    if (w>h && w>max) { h*=max/w; w=max; }
    else if (h>=w && h>max){ w*=max/h; h=max; }
    editorCanvas.width=w; editorCanvas.height=h;
    editorCtx.drawImage(currentImage,0,0,w,h);
    showSection('editorSection');
  };
  currentImage.src = dataUrl;
}

// 3) Envoi
async function savePhoto(){
  if (!editorCanvas||!rawImageData){ 
    showClientToast('Aucune image sélectionnée', 'error');
    return; 
  }
  
  // Afficher un indicateur de chargement
  const submitBtn = document.querySelector('button[onclick="savePhoto()"]');
  const originalText = submitBtn.textContent;
  submitBtn.textContent = 'Publication en cours...';
  submitBtn.disabled = true;
  
  try {
    const caption = document.getElementById('caption').value;
    const blob = await (await fetch(rawImageData)).blob();
    const form = new FormData();
    
    form.append('photo', blob, 'photo.png');
    form.append('caption', caption);
    
    if (selectedOverlay){
      form.append('overlay_file', selectedOverlay);
      form.append('overlay_x', overlayPosition.x);
      form.append('overlay_y', overlayPosition.y);
      form.append('overlay_width', overlaySize.width);
      form.append('overlay_height', overlaySize.height);
    }
    
    // Envoi vers le serveur
    const res = await fetch('/post/uploadWithOverlay', {
      method: 'POST',
      body: form
    });
    
    // Vérifier si la réponse est OK
    if (!res.ok) {
      if (res.status === 413)
        throw new Error('La photo est trop volumineuse. Veuillez en choisir une plus petite.');
      else
        throw new Error(`Erreur serveur: ${res.status} ${res.statusText}`);
    }
    
    // Vérifier si la réponse est du JSON
    const contentType = res.headers.get('content-type');
    if (!contentType || !contentType.includes('application/json')) {
      const text = await res.text();
      console.error('Réponse non-JSON du serveur:', text);
      throw new Error('Le serveur a renvoyé une réponse invalide (pas du JSON)');
    }
    
    const json = await res.json();
    
    // Rediriger vers la page spécifiée (avec le toast qui sera affiché)
    if (json.redirect) {
      window.location.href = json.redirect;
    } else {
      // Fallback si pas de redirection spécifiée
      window.location.reload();
    }
    
  } catch (error) {
    showClientToast(`Erreur : ${error.message}`, 'error');
    submitBtn.textContent = originalText;
    submitBtn.disabled = false;
  }
}

function resetEditor(){ showSection('step1'); }

function showSection(id){
  ['step1','cameraSection','editorSection'].forEach(s=>document.getElementById(s).classList.toggle('hidden',s!==id));
}

// Fonction pour afficher un toast temporaire côté client
function showClientToast(message, type = 'error') {
  // Supprimer tout toast existant
  const existingToast = document.getElementById('clientToast');
  if (existingToast) {
    existingToast.remove();
  }
  
  // Créer le nouveau toast
  const toastHtml = `
    <div id="clientToast" class="fixed top-4 right-4 z-50 max-w-sm w-full bg-white border border-gray-200 rounded-lg shadow-lg transform translate-x-0 transition-transform duration-300 ease-in-out">
      <div class="flex items-center p-4">
        <div class="flex-shrink-0">
          ${type === 'success' ? `
            <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
          ` : `
            <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
          `}
        </div>
        <div class="ml-3 w-0 flex-1">
          <p class="text-sm font-medium text-gray-900">${message}</p>
        </div>
        <div class="ml-4 flex-shrink-0 flex">
          <button onclick="closeClientToast()" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <span class="sr-only">Fermer</span>
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
          </button>
        </div>
      </div>
    </div>
  `;
  
  // Ajouter le toast au DOM
  document.body.insertAdjacentHTML('beforeend', toastHtml);
  
  // Auto-fermeture après 5 secondes
  setTimeout(() => {
    closeClientToast();
  }, 5000);
}

function closeClientToast() {
  const toast = document.getElementById('clientToast');
  if (toast) {
    toast.classList.add('translate-x-full');
    setTimeout(() => {
      toast.remove();
    }, 300);
  }
}

// Fonction pour supprimer une photo avec confirmation
async function deletePhoto(postId) {
  if (!confirm('Êtes-vous sûr de vouloir supprimer cette photo ? Cette action est irréversible.')) {
    return;
  }
  
  try {
    const response = await fetch('/post/delete', {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        post_id: postId
      })
    });
    
    const result = await response.json();
    
    if (result.success) {
      // Recharger la page pour mettre à jour les miniatures
      location.reload();
    } else {
      showClientToast(result.message || 'Erreur lors de la suppression', 'error');
    }
  } catch (error) {
    showClientToast('Erreur réseau lors de la suppression', 'error');
  }
}
</script>

<?php require_once "../app/views/partials/footer.php"; ?>
