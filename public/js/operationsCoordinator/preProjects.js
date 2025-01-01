document.addEventListener('DOMContentLoaded', function() {
    // Add click handler for project cards
    const projectCards = document.querySelectorAll('.project-card');
    projectCards.forEach(card => {
        card.addEventListener('click', function() {
            const projectId = this.querySelector('.project-id').textContent.replace('#PP', '');
            window.location.href = `${URLROOT}/operationsCoordinator/managePreProject/${projectId}`;
        });
    });

    // Phase filter functionality
    const phaseButtons = document.querySelectorAll('.phase-btn');
    phaseButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent triggering card click
            const phase = this.dataset.phase;
            window.location.href = `${URLROOT}/operationsCoordinator/preProjects?phase=${phase}`;
        });
    });

    // Search functionality
    const searchInput = document.querySelector('.search-input');
    searchInput.addEventListener('input', function(e) {
        e.stopPropagation(); // Prevent triggering card click
        const searchTerm = this.value.toLowerCase();
        
        projectCards.forEach(card => {
            const title = card.querySelector('.project-title').textContent.toLowerCase();
            const details = card.querySelector('.project-details').textContent.toLowerCase();
            
            if (title.includes(searchTerm) || details.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Sort functionality
    const sortDropdown = document.querySelector('.sort-dropdown');
    const projectsGrid = document.getElementById('projectsGrid');
    
    sortDropdown.addEventListener('change', function(e) {
        e.stopPropagation(); // Prevent triggering card click
        const cards = Array.from(projectCards);
        
        cards.sort((a, b) => {
            const dateA = new Date(a.querySelector('.project-details p:last-child').textContent);
            const dateB = new Date(b.querySelector('.project-details p:last-child').textContent);
            
            return this.value === 'newest' ? dateB - dateA : dateA - dateB;
        });
        
        cards.forEach(card => projectsGrid.appendChild(card));
    });
});