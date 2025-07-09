<!-- Toast Notification -->
<?php if (isset($_SESSION['toast'])): ?>
<div id="toast" class="fixed top-4 right-4 z-50 max-w-sm w-full bg-white border border-gray-200 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out">
	<div class="flex items-center p-4">
		<div class="flex-shrink-0">
			<?php if ($_SESSION['toast']['type'] === 'success'): ?>
				<svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
					<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
				</svg>
			<?php else: ?>
				<svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
					<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
				</svg>
			<?php endif; ?>
		</div>
		<div class="ml-3 w-0 flex-1">
			<p class="text-sm font-medium text-gray-900">
				<?= htmlspecialchars($_SESSION['toast']['message']) ?>
			</p>
		</div>
		<div class="ml-4 flex-shrink-0 flex">
			<button onclick="closeToast()" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
				<span class="sr-only">Fermer</span>
				<svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
					<path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
				</svg>
			</button>
		</div>
	</div>
</div>
<?php unset($_SESSION['toast']); ?>
<?php endif; ?>

<script>
function showToast() {
	const toast = document.getElementById('toast');
	if (toast) {
		toast.classList.remove('translate-x-full');
		toast.classList.add('translate-x-0');
		setTimeout(() => { closeToast(); }, 5000);
	}
}

function closeToast() {
	const toast = document.getElementById('toast');
	if (toast) {
		toast.classList.remove('translate-x-0');
		toast.classList.add('translate-x-full');
		setTimeout(() => { toast.remove(); }, 300);
	}
}

document.addEventListener('DOMContentLoaded', function() {
	const toast = document.getElementById('toast');
	if (toast) {
		showToast();
	}
});
</script>