let currentDraftId = null;

function publishDraft(draftId) {
    currentDraftId = draftId;
    document.getElementById('publishModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('publishModal').style.display = 'none';
    currentDraftId = null;
}

function confirmPublish() {
    if (!currentDraftId) return;

    // Create form data
    const formData = new FormData();
    formData.append('publish', true);

    // Show loading state
    const publishBtn = document.querySelector('.modal-actions .publish-btn');
    const originalText = publishBtn.innerHTML;
    publishBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Publishing...';
    publishBtn.disabled = true;

    // Make the request
    fetch(`${URLROOT}/admin/publishDraft/${currentDraftId}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Remove the row from table
            const row = document.querySelector(`tr[data-draft-id="${currentDraftId}"]`);
            if (row) {
                row.remove();
            } else {
                // If row not found, reload the page
                window.location.reload();
            }
            showToast('Draft published successfully!', 'success');
        } else {
            showToast(data.message || 'Failed to publish draft', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred while publishing', 'error');
    })
    .finally(() => {
        // Reset button state
        publishBtn.innerHTML = originalText;
        publishBtn.disabled = false;
        closeModal();
    });
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    
    // Create and add close button
    const closeBtn = document.createElement('button');
    closeBtn.className = 'toast-close';
    closeBtn.innerHTML = '×';
    closeBtn.onclick = () => toast.remove();
    toast.appendChild(closeBtn);
    
    // Add toast to document
    document.body.appendChild(toast);
    
    // Add visible class for animation
    setTimeout(() => toast.classList.add('visible'), 10);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        toast.classList.remove('visible');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('publishModal');
    if (event.target === modal) {
        closeModal();
    }
}