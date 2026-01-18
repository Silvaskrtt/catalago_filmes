// Global functions for the app

// Toast Notification
function showToast(message, type = 'info') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toast-message');

    if (!toast || !toastMessage) return;

    // Set message
    toastMessage.textContent = message;

    // Set color based on type
    if (type === 'error') {
        toast.style.backgroundColor = '#991b1b';
    } else if (type === 'success') {
        toast.style.backgroundColor = '#065f46';
    } else {
        toast.style.backgroundColor = '#1f2937';
    }

    // Show toast
    toast.classList.remove('hidden');

    // Hide after 3 seconds
    setTimeout(() => {
        toast.classList.add('hidden');
    }, 3000);
}

// Close delete modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('delete-modal');
        if (modal && !modal.classList.contains('hidden')) {
            closeDeleteModal();
        }
    }
});
