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

			<!-- Step 1: Choose Source -->
			<div id="step1" class="bg-white rounded-xl border border-gray-200 p-8 mb-6">
				<h2 class="text-xl font-semibold text-gray-900 mb-6 text-center">Choisissez votre source</h2>
				
				<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
					<!-- Webcam Option -->
					<div class="text-center">
						<div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
							<svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
							</svg>
						</div>
						<h3 class="text-lg font-semibold text-gray-900 mb-2">Webcam</h3>
						<p class="text-gray-600 mb-4">Prenez une photo en direct</p>
						<button onclick="startCamera()" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
							Activer la caméra
						</button>
					</div>

					<!-- File Upload Option -->
					<div class="text-center">
						<div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
							<svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
							</svg>
						</div>
						<h3 class="text-lg font-semibold text-gray-900 mb-2">Télécharger</h3>
						<p class="text-gray-600 mb-4">Choisir depuis vos fichiers</p>
						<label class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition duration-200 font-medium cursor-pointer inline-block">
							Choisir un fichier
							<input type="file" id="fileInput" accept="image/*" class="hidden" onchange="loadImageFromFile(this)">
						</label>
					</div>
				</div>
			</div>

			<!-- Step 2: Camera Interface -->
			<div id="cameraSection" class="bg-white rounded-xl border border-gray-200 p-6 mb-6 hidden">
				<div class="text-center mb-6">
					<h2 class="text-xl font-semibold text-gray-900 mb-2">Prendre une photo</h2>
					<p class="text-gray-600">Positionnez-vous devant la caméra et cliquez pour capturer</p>
				</div>

				<div class="flex justify-center mb-6">
					<div class="relative">
						<video id="video" width="640" height="480" autoplay class="rounded-lg border border-gray-300"></video>
						<canvas id="canvas" width="640" height="480" class="hidden"></canvas>
					</div>
				</div>

				<div class="text-center space-x-4">
					<button onclick="capturePhoto()" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
						📸 Capturer
					</button>
					<button onclick="stopCamera()" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition duration-200 font-medium">
						Annuler
					</button>
				</div>
			</div>

			<!-- Step 3: Photo Editor -->
			<div id="editorSection" class="bg-white rounded-xl border border-gray-200 p-6 hidden">
				<div class="text-center mb-6">
					<h2 class="text-xl font-semibold text-gray-900 mb-2">Éditeur de photo</h2>
					<p class="text-gray-600">Ajoutez des filtres et des stickers à votre photo</p>
				</div>

				<!-- Editor Canvas -->
				<div class="flex flex-col lg:flex-row gap-6">
					<!-- Canvas Area -->
					<div class="flex-1">
						<div class="flex justify-center mb-4">
							<div class="relative inline-block">
								<canvas id="editorCanvas" class="border border-gray-300 rounded-lg max-w-full h-auto"></canvas>
								<img id="previewImage" class="hidden" />
							</div>
						</div>
					</div>

					<!-- Tools Panel -->
					<div class="lg:w-80">
						<!-- Filters -->
						<div class="mb-6">
							<h3 class="text-lg font-semibold text-gray-900 mb-4">Filtres</h3>
							<div class="grid grid-cols-3 gap-3">
								<button onclick="applyFilter('none')" class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200 text-sm">
									Original
								</button>
								<button onclick="applyFilter('grayscale')" class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200 text-sm">
									N&B
								</button>
								<button onclick="applyFilter('sepia')" class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200 text-sm">
									Sépia
								</button>
								<button onclick="applyFilter('blur')" class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200 text-sm">
									Flou
								</button>
								<button onclick="applyFilter('brightness')" class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200 text-sm">
									Lumineux
								</button>
								<button onclick="applyFilter('contrast')" class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200 text-sm">
									Contraste
								</button>
							</div>
						</div>

						<!-- Stickers -->
						<div class="mb-6">
							<h3 class="text-lg font-semibold text-gray-900 mb-4">Stickers</h3>
							<div class="grid grid-cols-4 gap-3">
								<button onclick="addSticker('❤️')" class="p-3 text-2xl border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">❤️</button>
								<button onclick="addSticker('😀')" class="p-3 text-2xl border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">😀</button>
								<button onclick="addSticker('🌟')" class="p-3 text-2xl border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">🌟</button>
								<button onclick="addSticker('🔥')" class="p-3 text-2xl border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">🔥</button>
								<button onclick="addSticker('👍')" class="p-3 text-2xl border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">👍</button>
								<button onclick="addSticker('🎉')" class="p-3 text-2xl border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">🎉</button>
								<button onclick="addSticker('🌈')" class="p-3 text-2xl border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">🌈</button>
								<button onclick="addSticker('✨')" class="p-3 text-2xl border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">✨</button>
							</div>
						</div>

						<!-- Caption -->
						<div class="mb-6">
							<h3 class="text-lg font-semibold text-gray-900 mb-4">Légende</h3>
							<textarea 
								id="caption" 
								placeholder="Écrivez une légende..." 
								rows="3" 
								class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:border-transparent resize-none"
							></textarea>
						</div>

						<!-- Actions -->
						<div class="space-y-3">
							<button onclick="savePhoto()" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
								Publier la photo
							</button>
							<button onclick="resetEditor()" class="w-full bg-gray-500 text-white py-3 rounded-lg hover:bg-gray-600 transition duration-200 font-medium">
								Recommencer
							</button>
						</div>
					</div>
				</div>
			</div>
		</main>
	</div>
</div>

<script>
let stream = null;
let currentImage = null;
let canvas = null;
let ctx = null;
let stickers = [];

// Initialize editor canvas
function initEditor() {
	canvas = document.getElementById('editorCanvas');
	ctx = canvas.getContext('2d');
}

// Start camera
async function startCamera() {
	try {
		stream = await navigator.mediaDevices.getUserMedia({
			video: { width: 3840, height: 2160 } 
		});
		const video = document.getElementById('video');
		video.srcObject = stream;
		
		document.getElementById('step1').classList.add('hidden');
		document.getElementById('cameraSection').classList.remove('hidden');
	} catch (err) {
		alert('Erreur d\'accès à la caméra: ' + err.message);
	}
}

// Stop camera
function stopCamera() {
	if (stream) {
		stream.getTracks().forEach(track => track.stop());
		stream = null;
	}
	document.getElementById('cameraSection').classList.add('hidden');
	document.getElementById('step1').classList.remove('hidden');
}

// Capture photo from webcam
function capturePhoto() {
	const video = document.getElementById('video');
	const canvas = document.getElementById('canvas');
	const ctx = canvas.getContext('2d');
	
	ctx.drawImage(video, 0, 0, 640, 480);
	
	// Convert to image
	canvas.toBlob(blob => {
		const url = URL.createObjectURL(blob);
		loadImageToEditor(url);
	});
	
	stopCamera();
}

// Load image from file
function loadImageFromFile(input) {
	if (input.files && input.files[0]) {
		const reader = new FileReader();
		reader.onload = function(e) {
			loadImageToEditor(e.target.result);
		};
		reader.readAsDataURL(input.files[0]);
	}
}

// Load image to editor
function loadImageToEditor(imageSrc) {
	currentImage = new Image();
	currentImage.onload = function() {
		initEditor();
		
		// Set canvas size
		const maxWidth = 600;
		const maxHeight = 600;
		let { width, height } = this;
		
		if (width > height) {
			if (width > maxWidth) {
				height = height * (maxWidth / width);
				width = maxWidth;
			}
		} else {
			if (height > maxHeight) {
				width = width * (maxHeight / height);
				height = maxHeight;
			}
		}
		
		canvas.width = width;
		canvas.height = height;
		
		// Draw image
		ctx.drawImage(this, 0, 0, width, height);
		
		// Show editor
		document.getElementById('step1').classList.add('hidden');
		document.getElementById('cameraSection').classList.add('hidden');
		document.getElementById('editorSection').classList.remove('hidden');
	};
	currentImage.src = imageSrc;
}

// Apply filter
function applyFilter(filterType) {
	if (!currentImage || !canvas) return;
	
	// Clear canvas
	ctx.clearRect(0, 0, canvas.width, canvas.height);
	
	// Apply filter
	switch (filterType) {
		case 'none':
			ctx.filter = 'none';
			break;
		case 'grayscale':
			ctx.filter = 'grayscale(100%)';
			break;
		case 'sepia':
			ctx.filter = 'sepia(100%)';
			break;
		case 'blur':
			ctx.filter = 'blur(2px)';
			break;
		case 'brightness':
			ctx.filter = 'brightness(150%)';
			break;
		case 'contrast':
			ctx.filter = 'contrast(150%)';
			break;
	}
	
	// Redraw image
	ctx.drawImage(currentImage, 0, 0, canvas.width, canvas.height);
	
	// Redraw stickers
	stickers.forEach(sticker => {
		ctx.filter = 'none';
		ctx.font = '30px Arial';
		ctx.fillText(sticker.emoji, sticker.x, sticker.y);
	});
}

// Add sticker
function addSticker(emoji) {
	if (!canvas) return;
	
	const x = Math.random() * (canvas.width - 50);
	const y = 30 + Math.random() * (canvas.height - 60);
	
	stickers.push({ emoji, x, y });
	
	ctx.filter = 'none';
	ctx.font = '30px Arial';
	ctx.fillText(emoji, x, y);
}

// Save photo
async function savePhoto() {
	if (!canvas) {
		alert('Aucune image à sauvegarder');
		return;
	}
	
	const caption = document.getElementById('caption').value;
	
	// Convert canvas to blob
	canvas.toBlob(async (blob) => {
		const formData = new FormData();
		formData.append('photo', blob, 'photo.png');
		formData.append('caption', caption);
		
		try {
			const response = await fetch('/photo/upload', {
				method: 'POST',
				body: formData
			});
			
			if (response.ok) {
				alert('Photo publiée avec succès !');
				window.location.href = '/';
			} else {
				alert('Erreur lors de la publication');
			}
		} catch (error) {
			alert('Erreur: ' + error.message);
		}
	}, 'image/png');
}

// Reset editor
function resetEditor() {
	stickers = [];
	currentImage = null;
	document.getElementById('caption').value = '';
	document.getElementById('editorSection').classList.add('hidden');
	document.getElementById('step1').classList.remove('hidden');
}

// Add drag and drop functionality
document.addEventListener('DOMContentLoaded', function() {
	const step1 = document.getElementById('step1');
	
	// Drag and drop
	step1.addEventListener('dragover', function(e) {
		e.preventDefault();
		e.stopPropagation();
		step1.classList.add('border-blue-400', 'bg-blue-50');
	});
	
	step1.addEventListener('dragleave', function(e) {
		e.preventDefault();
		e.stopPropagation();
		step1.classList.remove('border-blue-400', 'bg-blue-50');
	});
	
	step1.addEventListener('drop', function(e) {
		e.preventDefault();
		e.stopPropagation();
		step1.classList.remove('border-blue-400', 'bg-blue-50');
		
		const files = e.dataTransfer.files;
		if (files.length > 0 && files[0].type.startsWith('image/')) {
			const reader = new FileReader();
			reader.onload = function(e) {
				loadImageToEditor(e.target.result);
			};
			reader.readAsDataURL(files[0]);
		}
	});
});
</script>

<?php require_once "../app/views/partials/footer.php"; ?>
