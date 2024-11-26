document.addEventListener('DOMContentLoaded', function() {
    // Initialize smooth scroll for headings
    initializeSmoothScroll();
    
    // Add scroll animation for elements
    initializeScrollAnimations();
    
    // Initialize image zoom
    initializeImageZoom();
});

function initializeSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

function initializeScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.post-body > *').forEach(element => {
        element.classList.add('scroll-animate');
        observer.observe(element);
    });
}

function initializeImageZoom() {
    document.querySelectorAll('.post-body img').forEach(img => {
        img.addEventListener('click', function() {
            const overlay = document.createElement('div');
            overlay.className = 'image-overlay';
            
            const image = document.createElement('img');
            image.src = this.src;
            image.alt = this.alt;
            
            overlay.appendChild(image);
            document.body.appendChild(overlay);
            
            setTimeout(() => overlay.classList.add('show'), 10);
            
            overlay.addEventListener('click', function() {
                this.classList.remove('show');
                setTimeout(() => this.remove(), 300);
            });
        });
    });
}

function sharePost(platform) {
    const url = window.location.href;
    const title = document.querySelector('.post-header h1').textContent;
    
    let shareUrl;
    switch(platform) {
        case 'facebook':
            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
            break;
        case 'twitter':
            shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`;
            break;
        case 'linkedin':
            shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`;
            break;
    }
    
    if (shareUrl) {
        window.open(shareUrl, '_blank', 'width=600,height=400');
    }
}

function copyLink() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(() => {
        showToast('Link copied to clipboard!');
    }).catch(() => {
        showToast('Failed to copy link', 'error');
    });
}

function showToast(message, type = 'success') {
    const existingToast = document.querySelector('.toast');
    if (existingToast) {
        existingToast.remove();
    }

    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <span class="material-icons-sharp">${type === 'success' ? 'check_circle' : 'error'}</span>
        <span>${message}</span>
    `;

    document.body.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 10);
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}