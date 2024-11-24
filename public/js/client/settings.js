document.addEventListener('DOMContentLoaded', () => {
    const imgUpload = document.getElementById('img-upload');
    const profileImg = document.getElementById('profile-img');
    const saveBtn = document.querySelector('.save-btn');

    imgUpload.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                profileImg.src = e.target.result;
                showSuccessAnimation();
            };
            reader.readAsDataURL(file);
        }
    });

    function showSuccessAnimation() {
        saveBtn.classList.add('success');
        setTimeout(() => {
            saveBtn.classList.remove('success');
        }, 2000);
    }
});

function saveChanges() {
    const phone = document.getElementById('phone').value;
    const currentPassword = document.getElementById('current-password').value;
    const newPassword = document.getElementById('new-password').value;

    // Here you would typically make an API call to save the changes
    // For demo purposes, we'll just show the success animation
    const saveBtn = document.querySelector('.save-btn');
    saveBtn.classList.add('success');
    
    // Reset password fields
    document.getElementById('current-password').value = '';
    document.getElementById('new-password').value = '';

    setTimeout(() => {
        saveBtn.classList.remove('success');
    }, 2000);
}

class ToastNotification {
    constructor() {
        this.container = document.getElementById('toast-container');
    }

    show(message, type = 'success', duration = 5000) {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;

        // Choose icon based on type
        let icon = '';
        switch(type) {
            case 'success':
                icon = `<svg class="toast-icon success" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 6L9 17l-5-5"></path></svg>`;
                break;
            case 'error':
                icon = `<svg class="toast-icon error" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6L6 18M6 6l12 12"></path></svg>`;
                break;
            case 'warning':
                icon = `<svg class="toast-icon warning" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`;
                break;
        }

        // Create progress bar
        const progressBar = document.createElement('div');
        progressBar.className = 'toast-progress';
        progressBar.innerHTML = '<div class="toast-progress-bar"></div>';

        toast.innerHTML = `
            <div class="toast-content">
                ${icon}
                <span>${message}</span>
            </div>
            <button class="toast-close" aria-label="Close">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6L6 18M6 6l12 12"></path>
                </svg>
            </button>
        `;
        toast.appendChild(progressBar);

        // Add to container
        this.container.appendChild(toast);

        // Start progress bar animation
        const progress = toast.querySelector('.toast-progress-bar');
        progress.style.width = '100%';
        progress.style.transition = `width ${duration}ms linear`;
        
        // Force reflow to ensure transition starts
        progress.getBoundingClientRect();
        progress.style.width = '0%';

        // Add close button listener
        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', () => this.closeToast(toast));

        // Auto remove after duration
        setTimeout(() => this.closeToast(toast), duration);

        // Return the toast element
        return toast;
    }

    closeToast(toast) {
        if (!toast.parentElement) return;
        
        toast.style.animation = 'slideOut 0.3s ease-in-out forwards';
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 300);
    }
}

// Initialize toast notification
const toast = new ToastNotification();

// Convert PHP flash messages to toasts on page load
document.addEventListener('DOMContentLoaded', function() {
    const flashMessages = document.querySelectorAll('[id^="msg-flash"]');
    
    flashMessages.forEach(flash => {
        // Determine message type
        let type = 'success';
        if (flash.classList.contains('alert-danger')) {
            type = 'error';
        } else if (flash.classList.contains('alert-warning')) {
            type = 'warning';
        }

        // Show toast and remove original flash message
        if (flash.textContent.trim()) {
            toast.show(flash.textContent.trim(), type);
        }
        flash.remove();
    });
});

// Function to show toast (can be called from anywhere)
function showToast(message, type = 'success', duration = 5000) {
    toast.show(message, type, duration);
}

// Add form submission handlers
document.getElementById('profile-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Submit the form
    fetch(this.action, {
        method: 'POST',
        body: new FormData(this)
    })
    .then(response => response.text())
    .then(data => {
        toast.show('Profile updated successfully', 'success');
    })
    .catch(error => {
        toast.show('Failed to update profile', 'error');
    });
});

// Add password form submission handler
document.querySelector('.password-section')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const currentPassword = document.getElementById('current-password').value;
    const newPassword = document.getElementById('new-password').value;
    
    if (!currentPassword || !newPassword) {
        toast.show('Please fill in all password fields', 'warning');
        return;
    }
    
    // Submit the form
    fetch(this.action, {
        method: 'POST',
        body: new FormData(this)
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes('success')) {
            toast.show('Password updated successfully', 'success');
            this.reset();
        } else {
            toast.show('Failed to update password. Please check your current password.', 'error');
        }
    })
    .catch(error => {
        toast.show('Failed to update password', 'error');
    });
});

// Add profile picture form submission handler
document.getElementById('img-upload')?.addEventListener('change', function() {
    const form = document.getElementById('profile-pic-form');
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes('success')) {
            toast.show('Profile picture updated successfully', 'success');
            // Reload the page to show the new image
            setTimeout(() => location.reload(), 1000);
        } else {
            toast.show('Failed to update profile picture', 'error');
        }
    })
    .catch(error => {
        toast.show('Failed to upload profile picture', 'error');
    });
});