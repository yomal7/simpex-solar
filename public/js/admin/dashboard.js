// ADMIN_ROOT/public/js/admin.js
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
                        <img src="${e.target.result}" alt="Preview" style="max-width: 100%; max-height: 200px; border-radius: 8px;">
                        <span class="remove-image">&times;</span>
                    `;
                    
                    // Add remove image functionality
                    const removeBtn = previewContainer.querySelector('.remove-image');
                    removeBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        imageInput.value = '';
                        previewContainer.innerHTML = `
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Click to upload image</span>
                        `;
                    });
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Form Validation
    const blogForm = document.getElementById('blogForm');
    if (blogForm) {
        blogForm.addEventListener('submit', function(e) {
            let isValid = true;
            const title = document.getElementById('title').value;
            const category = document.getElementById('category').value;
            const body = document.getElementById('body').value;

            if (!title.trim()) {
                showError('title', 'Title is required');
                isValid = false;
            }

            if (!category) {
                showError('category', 'Please select a category');
                isValid = false;
            }

            if (!body.trim()) {
                showError('body', 'Content is required');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    // Show error message
    function showError(fieldId, message) {
        const field = document.getElementById(fieldId);
        field.classList.add('is-invalid');
        
        // Create error message element if it doesn't exist
        let errorDiv = field.nextElementSibling;
        if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
            errorDiv = document.createElement('div');
            errorDiv.classList.add('invalid-feedback');
            field.parentNode.insertBefore(errorDiv, field.nextSibling);
        }
        errorDiv.textContent = message;
    }

    // Initialize CKEditor
    if (typeof ClassicEditor !== 'undefined') {
        ClassicEditor
            .create(document.querySelector('#body'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo'],
                placeholder: 'Start writing your blog post...'
            })
            .catch(error => {
                console.error(error);
            });
    }

    // Preview Post
    window.previewPost = function() {
        const title = document.getElementById('title').value;
        const content = document.querySelector('.ck-content').innerHTML;
        
        // Create preview window
        const previewWindow = window.open('', 'Preview', 'width=800,height=600,scrollbars=yes');
        previewWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Preview: ${title}</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; max-width: 800px; margin: 0 auto; padding: 20px; }
                    h1 { color: #2c3e50; }
                    img { max-width: 100%; height: auto; }
                </style>
            </head>
            <body>
                <h1>${title}</h1>
                <div class="content">${content}</div>
            </body>
            </html>
        `);
    };
});