document.addEventListener('DOMContentLoaded', function() {
    // Cache DOM elements
    const projectCards = document.querySelectorAll('.project-card');
    const projectsGrid = document.getElementById('projectsGrid');
    const searchInput = document.querySelector('.search-input');
    const sortDropdown = document.querySelector('.sort-dropdown');

    // Add click handler for project cards
    projectCards.forEach(card => {
        card.addEventListener('click', function() {
            const projectId = this.querySelector('.project-id').textContent.replace('#PP', '');
            window.location.href = `${URLROOT}/operationsCoordinator/managePreProject/${projectId}`;
        });
    });

    // Search functionality
    searchInput.addEventListener('input', function(e) {
        e.stopPropagation(); // Prevent triggering card click
        const searchTerm = this.value.toLowerCase().trim();

        projectCards.forEach(card => {
            const cardContent = card.textContent.toLowerCase();
            card.style.display = cardContent.includes(searchTerm) ? 'block' : 'none';
        });
    });

    // Sort functionality
    sortDropdown.addEventListener('change', function(e) {
        e.stopPropagation(); // Prevent triggering card click
        const cards = Array.from(projectCards);

        cards.sort((a, b) => {
            // Extract dates from the cards
            const dateA = new Date(extractDate(a));
            const dateB = new Date(extractDate(b));

            return this.value === 'newest' ? dateB - dateA : dateA - dateB;
        });

        // Clear and repopulate the grid
        projectsGrid.innerHTML = '';
        cards.forEach(card => projectsGrid.appendChild(card));
    });

    // Helper function to extract date from card
    function extractDate(card) {
        const dateText = card.querySelector('.project-details p:nth-child(3)').textContent;
        return dateText.replace('📅 ', '');
    }

    // Phase filter functionality
    const phaseButtons = document.querySelectorAll('.phase-btn');
    phaseButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const phase = this.dataset.phase;
            
            if (phase === 'all') {
                projectCards.forEach(card => card.style.display = 'block');
            } else {
                projectCards.forEach(card => {
                    card.style.display = card.dataset.phase === phase ? 'block' : 'none';
                });
            }
        });
    });

});
