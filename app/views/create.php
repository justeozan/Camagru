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
              <!-- icône caméra -->
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
              <!-- icône upload -->
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
  if (!editorCanvas||!rawImageData){ alert('Aucune image'); return; }
  const caption=document.getElementById('caption').value,
        blob = await (await fetch(rawImageData)).blob(),
        form = new FormData();
  form.append('photo', blob, 'photo.png');
  form.append('caption', caption);
  if (selectedOverlay){
    form.append('overlay_file', selectedOverlay);
    form.append('overlay_x', overlayPosition.x);
    form.append('overlay_y', overlayPosition.y);
    form.append('overlay_width', overlaySize.width);
    form.append('overlay_height', overlaySize.height);
  }
  const res=await fetch('/post/uploadWithOverlay',{method:'POST',body:form}),
        json=await res.json();
  if (json.success){ alert('Photo publiée !'); location.href='/'; }
  else alert('Erreur : '+(json.error||json.message));
}

function resetEditor(){ showSection('step1'); }

function showSection(id){
  ['step1','cameraSection','editorSection'].forEach(s=>document.getElementById(s).classList.toggle('hidden',s!==id));
}
</script>

<?php require_once "../app/views/partials/footer.php"; ?>
