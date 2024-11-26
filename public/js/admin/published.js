// public/js/editBlog.js

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editBlogForm');
    
    // Handle form submission
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!validateForm()) {
                return;
            }

            // Get the clicked button's status value
            const clickedButton = document.activeElement;
            const status = clickedButton.value;
            
            // Show loading state on clicked button
            const originalText = clickedButton.innerHTML;
            clickedButton.innerHTML = `<span class="material-icons-sharp spinner">sync</span> ${status === 'draft' ? 'Saving...' : 'Publishing...'}`;
            clickedButton.disabled = true;

            // Create FormData
            const formData = new FormData(form);
            formData.append('status', status);

            // Send AJAX request
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message || 'Post saved successfully', 'success');
                    setTimeout(() => {
                        window.location.href = status === 'draft' 
                            ? `${URLROOT}/admin/drafts` 
                            : `${URLROOT}/admin/published`;
                    }, 1000);
                } else {
                    showToast(data.message || 'Failed to save post', 'error');
                    clickedButton.innerHTML = originalText;
                    clickedButton.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while saving', 'error');
                clickedButton.innerHTML = originalText;
                clickedButton.disabled = false;
            });
        });
    }
});

function validateForm() {
    let isValid = true;
    const title = document.getElementById('title').value;
    const category = document.getElementById('category').value;
    const summary = document.getElementById('summary').value;
    const body = document.getElementById('body').value;

    // Reset previous errors
    document.querySelectorAll('.error').forEach(error => error.textContent = '');
    document.querySelectorAll('.form-control').forEach(control => control.classList.remove('is-invalid'));

    if (!title.trim()) {
        showError('title', 'Title is required');
        isValid = false;
    }

    if (!category) {
        showError('category', 'Please select a category');
        isValid = false;
    }

    if (!summary.trim()) {
        showError('summary', 'Summary is required');
        isValid = false;
    }

    if (!body.trim()) {
        showError('body', 'Content is required');
        isValid = false;
    }

    return isValid;
}

function showError(fieldId, message) {
    const field = document.getElementById(fieldId);
    const errorSpan = field.nextElementSibling;
    field.classList.add('is-invalid');
    if (errorSpan && errorSpan.classList.contains('error')) {
        errorSpan.textContent = message;
    }
}

function showToast(message, type = 'info') {
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.toast');
    existingToasts.forEach(toast => toast.remove());

    // Create new toast
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    
    // Add icon based on type
    const icon = document.createElement('span');
    icon.className = 'material-icons-sharp';
    icon.textContent = type === 'success' ? 'check_circle' : 'error';
    toast.appendChild(icon);
    
    // Add message
    const messageSpan = document.createElement('span');
    messageSpan.textContent = message;
    toast.appendChild(messageSpan);
    
    // Add close button
    const closeBtn = document.createElement('button');
    closeBtn.className = 'toast-close';
    closeBtn.innerHTML = '×';
    closeBtn.onclick = () => {
        toast.classList.remove('visible');
        setTimeout(() => toast.remove(), 300);
    };
    toast.appendChild(closeBtn);
    
    document.body.appendChild(toast);
    
    // Trigger animation
    requestAnimationFrame(() => {
        toast.classList.add('visible');
    });
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (toast) {
            toast.classList.remove('visible');
            setTimeout(() => toast.remove(), 300);
        }
    }, 3000);
}

// Add fadeOut animation
const style = document.createElement('style');
style.textContent = `
@keyframes fadeOut {
    from { opacity: 1; transform: translateX(0); }
    to { opacity: 0; transform: translateX(-10px); }
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.spinner {
    animation: spin 1s linear infinite;
    display: inline-block;
}`;
document.head.appendChild(style);