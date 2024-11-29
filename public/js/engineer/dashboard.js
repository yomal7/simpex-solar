const projects = [
    { id: 1, name: "John Doe", address: "123 Main St", email: "john@example.com", availability: "in-stock" },
    { id: 2, name: "Jane Smith", address: "456 Elm St", email: "jane@example.com", availability: "low-stock" },
    { id: 3, name: "Bob Johnson", address: "789 Oak St", email: "bob@example.com", availability: "out-of-stock" },
];

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('active');
}

// Add animation to cards
const cards = document.querySelectorAll('.card');
cards.forEach(card => {
    card.addEventListener('mouseover', () => {
        card.style.transform = 'translateY(-10px)';
    });
    card.addEventListener('mouseout', () => {
        card.style.transform = 'translateY(0)';
    });
});

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const menuToggle = document.querySelector('.menu-toggle');
    if (window.innerWidth <= 768 && sidebar.classList.contains('active') && 
        !sidebar.contains(event.target) && event.target !== menuToggle) {
        sidebar.classList.remove('active');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('overlay');
    
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            closePopup('projectFormPopup');
        }
    });
});