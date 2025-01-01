document.addEventListener('DOMContentLoaded', function() {
    // Phase cards click handlers
    const phaseCards = document.querySelectorAll('.phase-card');
    phaseCards.forEach(card => {
        card.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            if (url) {
                window.location.href = url;
            }
        });

        // Hover effects
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });

    // Flash message auto-hide
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            setTimeout(() => message.remove(), 300);
        }, 5000);
    });

    // Phase update handler
    window.updatePhase = function(preProjectId, phase) {
        fetch(`${URLROOT}/operationsCoordinator/updateProjectPhase`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `pre_project_id=${preProjectId}&phase=${phase}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to update phase. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    };
    // Sidebar toggle
    window.toggleSidebar = function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        
        if (sidebar && overlay) {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }
    };
});

document.querySelectorAll('.phase-card').forEach(card => {
    card.addEventListener('click', function() {
        const url = this.dataset.url;
        if (url) {
            window.location.href = url;
        }
    });
});