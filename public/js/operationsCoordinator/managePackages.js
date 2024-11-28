document.addEventListener('DOMContentLoaded', function() {
    // Add loading animation to cards
    const cards = document.querySelectorAll('.package-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Handle image loading
    const images = document.querySelectorAll('.package-image img');
    images.forEach(img => {
        img.addEventListener('load', function() {
            this.style.opacity = '1';
        });
        
        img.addEventListener('error', function() {
            this.parentElement.innerHTML = `
                <div class="no-image">
                    <span class="material-icons-sharp">image_not_supported</span>
                </div>
            `;
        });
    });

    // Enhanced delete confirmation
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const packageTitle = this.closest('.package-card').querySelector('h3').textContent;
            
            if (confirm(`Are you sure you want to delete the package "${packageTitle}"? This action cannot be undone.`)) {
                this.submit();
            }
        });
    });

    // Card hover effects
    cards.forEach(card => {
        card.addEventListener('mousemove', handleMouseMove);
        card.addEventListener('mouseleave', handleMouseLeave);
    });
});


// Toggle sidebar
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');
    
    sidebar.classList.toggle('collapsed');
    if (sidebar.classList.contains('collapsed')) {
        mainContent.style.marginLeft = '0';
    } else {
        mainContent.style.marginLeft = '250px';
    }
}

// Flash message auto-hide
const flashMessage = document.querySelector('.flash-message');
if (flashMessage) {
    setTimeout(() => {
        flashMessage.style.opacity = '0';
        setTimeout(() => {
            flashMessage.remove();
        }, 300);
    }, 3000);
}



function showDeleteModal(packageId) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deletePackageForm');
    
    // Set the form action with the correct URL
    form.action = `${URLROOT}/operationsCoordinator/deletePackage/${packageId}`;
    
    // Show modal
    modal.style.display = 'flex';
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target === modal) {
        closeDeleteModal();
    }
}

// Add escape key listener to close modal
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDeleteModal();
    }
});