// 404.js - Add stars to the background
document.addEventListener('DOMContentLoaded', function() {
    // Create stars in the background
    createStars();
});

function createStars() {
    const starsContainer = document.querySelector('.stars');
    const starCount = 150; // Increase star count for denser star field
    
    for (let i = 0; i < starCount; i++) {
        const star = document.createElement('div');
        star.className = 'star';
        
        // Random position
        star.style.left = `${Math.random() * 100}%`;
        star.style.top = `${Math.random() * 100}%`;
        
        // Random size (1-3px)
        const size = (Math.random() * 2) + 1;
        star.style.width = `${size}px`;
        star.style.height = `${size}px`;
        
        // Random opacity
        star.style.opacity = Math.random() * 0.8 + 0.2;
        
        // Animation delay
        star.style.animationDelay = `${Math.random() * 5}s`;
        
        // Apply styles
        star.style.position = 'absolute';
        star.style.backgroundColor = 'white';
        star.style.borderRadius = '50%';
        star.style.animation = 'twinkle 5s infinite ease-in-out';
        
        starsContainer.appendChild(star);
    }
    
    // Add keyframes for twinkling animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes twinkle {
            0%, 100% { opacity: 0.2; }
            50% { opacity: 1; }
        }
    `;
    document.head.appendChild(style);
}