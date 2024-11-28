document.addEventListener('DOMContentLoaded', function() {
    // Image Upload Preview
    const imageInput = document.getElementById('featured_image');
    const previewContainer = document.getElementById('preview');

    if (imageInput) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContainer.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" class="current-image">
                        <span class="remove-image" onclick="removeImage(event)">×</span>
                    `;
                    document.getElementById('remove_image').value = '0';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Form Validation
    const form = document.getElementById('editBlogForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
            }
        });
    }
});

function removeImage(e) {
    e.stopPropagation();
    const previewContainer = document.getElementById('preview');
    previewContainer.innerHTML = `
        <span class="material-icons-sharp upload-icon">cloud_upload</span>
        <span>Click to upload image</span>
    `;
    document.getElementById('featured_image').value = '';
    document.getElementById('remove_image').value = '1';
}



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

function saveDraft() {
    if (validateForm()) {
        const form = document.getElementById('editBlogForm');
        
        // Create form data to send
        const formData = new FormData(form);
        formData.append('status', 'draft');

        // Show loading state
        const draftBtn = document.querySelector('.draft-btn');
        const originalBtnText = draftBtn.innerHTML;
        draftBtn.innerHTML = '<span class="material-icons-sharp spinner">sync</span> Saving...';
        draftBtn.disabled = true;

        // Send AJAX request
        fetch(`${URLROOT}/admin/editBlog/${formData.get('post_id')}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Post saved as draft successfully', 'success');
                // Redirect to drafts page after short delay
                setTimeout(() => {
                    window.location.href = `${URLROOT}/admin/drafts`;
                }, 1000);
            } else {
                showToast(data.message || 'Failed to save draft', 'error');
                draftBtn.innerHTML = originalBtnText;
                draftBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while saving', 'error');
            draftBtn.innerHTML = originalBtnText;
            draftBtn.disabled = false;
        });
    }
}

function previewPost() {
    if (!validateForm()) {
        showToast('Please fill all required fields before preview', 'error');
        return;
    }

    const title = document.getElementById('title').value;
    const body = document.getElementById('body').value;
    const summary = document.getElementById('summary').value;
    const category = document.getElementById('category');
    const categoryName = category.options[category.selectedIndex].text;
    const featuredImage = document.querySelector('.current-image')?.src || '';

    const previewWindow = window.open('', 'Preview', 'width=800,height=600,scrollbars=yes');
    previewWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Preview: ${title}</title>
            <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 20px;
                    color: #2c3e50;
                }
                .preview-header {
                    border-bottom: 2px solid #eee;
                    margin-bottom: 2rem;
                    padding-bottom: 1rem;
                }
                .preview-title {
                    font-size: 2.5rem;
                    margin: 0 0 1rem 0;
                    color: #2c3e50;
                }
                .preview-meta {
                    color: #7f8c8d;
                    font-size: 0.9rem;
                    margin-bottom: 1rem;
                }
                .preview-summary {
                    font-size: 1.1rem;
                    font-style: italic;
                    color: #666;
                    margin: 1rem 0;
                    padding: 1rem;
                    background: #f8f9fa;
                    border-left: 4px solid #3498db;
                }
                .preview-image {
                    width: 100%;
                    max-height: 400px;
                    object-fit: cover;
                    border-radius: 8px;
                    margin: 1rem 0;
                }
                .preview-content {
                    font-size: 1.1rem;
                    line-height: 1.8;
                }
                .preview-badge {
                    display: inline-block;
                    padding: 0.25rem 0.5rem;
                    background: #e74c3c;
                    color: white;
                    border-radius: 4px;
                    font-size: 0.8rem;
                    margin-left: 1rem;
                }
            </style>
        </head>
        <body>
            <div class="preview-header">
                <h1 class="preview-title">
                    ${title}
                    <span class="preview-badge">Preview Mode</span>
                </h1>
                <div class="preview-meta">
                    Category: ${categoryName} | Last Updated: ${new Date().toLocaleDateString()}
                </div>
                ${
                    featuredImage ? 
                    `<img src="${featuredImage}" alt="Featured image" class="preview-image">` : 
                    ''
                }
                <div class="preview-summary">${summary}</div>
            </div>
            <div class="preview-content">
                ${body}
            </div>
        </body>
        </html>
    `);
    previewWindow.document.close();
}

function showToast(message, type = 'info') {
    // Remove existing toasts
    document.querySelectorAll('.toast').forEach(toast => toast.remove());

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
    
    // Show toast with animation
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

// Auto-save draft every 2 minutes
let autoSaveTimer;
const AUTO_SAVE_INTERVAL = 2 * 60 * 1000; // 2 minutes

function startAutoSave() {
    autoSaveTimer = setInterval(() => {
        if (document.querySelector('.form-control.is-invalid')) {
            return; // Don't auto-save if there are validation errors
        }
        
        const formData = new FormData(document.getElementById('editBlogForm'));
        formData.append('status', 'draft');
        formData.append('auto_save', 'true');

        fetch(document.getElementById('editBlogForm').action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Draft auto-saved', 'success');
            }
        })
        .catch(error => {
            console.error('Auto-save error:', error);
        });
    }, AUTO_SAVE_INTERVAL);
}

// Start auto-save when page loads
document.addEventListener('DOMContentLoaded', startAutoSave);

// Clear auto-save when leaving page
window.addEventListener('beforeunload', () => {
    clearInterval(autoSaveTimer);
});

// Prevent accidental navigation
window.onbeforeunload = function() {
    if (document.getElementById('editBlogForm').dataset.changed === 'true') {
        return 'You have unsaved changes. Are you sure you want to leave?';
    }
};

// Track form changes
document.getElementById('editBlogForm').addEventListener('input', function() {
    this.dataset.changed = 'true';
});