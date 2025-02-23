// public/js/flash.js
function showFlash(message, type = 'success', duration = 3000) {
    const flashContainer = document.querySelector('.flash-container');
    const template = document.querySelector('.flash-message');
    
    // Clone the template
    const flash = template.cloneNode(true);
    
    // Update content
    flash.querySelector('.flash-title').textContent = type.charAt(0).toUpperCase() + type.slice(1);
    flash.querySelector('.flash-description').textContent = message;
    
    // Update icon based on type
    const icon = flash.querySelector('.flash-icon i');
    switch(type) {
        case 'success':
            icon.className = 'fas fa-check';
            break;
        case 'error':
            icon.className = 'fas fa-times';
            break;
        case 'warning':
            icon.className = 'fas fa-exclamation';
            break;
        case 'info':
            icon.className = 'fas fa-info';
            break;
    }
    
    // Add type class
    flash.classList.add(type);
    
    // Add to container
    flashContainer.appendChild(flash);
    
    // Trigger animation
    setTimeout(() => flash.classList.add('show'), 10);
    
    // Remove after duration
    setTimeout(() => {
        flash.classList.add('hide');
        setTimeout(() => flash.remove(), 500);
    }, duration);
}

function closeFlash(element) {
    const flash = element.closest('.flash-message');
    flash.classList.add('hide');
    setTimeout(() => flash.remove(), 500);
}