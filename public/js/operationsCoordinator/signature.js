document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('signature');
    const fileNameDisplay = document.getElementById('file-name');
    const uploadForm = document.querySelector('.upload-form');
    const previewContainer = document.querySelector('.signature-preview');
    
    // File input handling
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Display file name
                fileNameDisplay.textContent = `Selected file: ${file.name}`;
                
                // Validate file type and size
                const validTypes = ['image/png', 'image/jpeg', 'image/jpg'];
                const maxSize = 5 * 1024 * 1024; // 5MB
                
                if (!validTypes.includes(file.type)) {
                    showError('Please select a valid image file (PNG, JPEG, or JPG)');
                    fileInput.value = '';
                    fileNameDisplay.textContent = '';
                    return;
                }
                
                if (file.size > maxSize) {
                    showError('File size must be less than 5MB');
                    fileInput.value = '';
                    fileNameDisplay.textContent = '';
                    return;
                }
                
                // Preview image
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (previewContainer) {
                        const previewImage = document.createElement('img');
                        previewImage.src = e.target.result;
                        previewImage.alt = 'Signature Preview';
                        previewImage.className = 'preview-image';
                        
                        // Clear previous preview
                        previewContainer.innerHTML = '';
                        previewContainer.appendChild(previewImage);
                    }
                };
                reader.readAsDataURL(file);
            } else {
                fileNameDisplay.textContent = '';
                if (previewContainer) {
                    previewContainer.innerHTML = '';
                }
            }
        });
    }

    // Form submission handling
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            if (!fileInput.files[0]) {
                e.preventDefault();
                showError('Please select a file to upload');
            }
        });
    }

    // Delete confirmation
    const deleteForm = document.querySelector('.delete-form');
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to delete your signature?')) {
                e.preventDefault();
            }
        });
    }

    // Flash message handling
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(message => {
        // Add close button to flash messages
        const closeBtn = document.createElement('button');
        closeBtn.innerHTML = '&times;';
        closeBtn.className = 'flash-close';
        closeBtn.onclick = function() {
            hideFlashMessage(message);
        };
        message.appendChild(closeBtn);

        // Auto-hide flash messages after 5 seconds
        setTimeout(() => {
            hideFlashMessage(message);
        }, 5000);
    });
    
    // Helper functions
    function showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'flash-message error';
        errorDiv.textContent = message;
        
        const container = document.querySelector('.content-wrapper');
        if (container) {
            container.insertBefore(errorDiv, container.firstChild);
            
            setTimeout(() => {
                hideFlashMessage(errorDiv);
            }, 5000);
        }
    }
    
    function hideFlashMessage(element) {
        element.style.opacity = '0';
        setTimeout(() => {
            element.remove();
        }, 300);
    }

    // Drag and drop handling
    const dropZone = document.querySelector('.file-upload-wrapper');
    if (dropZone) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('drag-hover');
        }

        function unhighlight(e) {
            dropZone.classList.remove('drag-hover');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const file = dt.files[0];
            
            if (file && fileInput) {
                fileInput.files = dt.files;
                const event = new Event('change');
                fileInput.dispatchEvent(event);
            }
        }
    }
});
