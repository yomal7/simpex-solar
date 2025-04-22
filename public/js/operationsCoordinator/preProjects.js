// document.addEventListener('DOMContentLoaded', function() {
//     // Cache DOM elements
//     const projectCards = document.querySelectorAll('.project-card');
//     const projectsGrid = document.getElementById('projectsGrid');
//     const searchInput = document.querySelector('.search-input');
//     const sortDropdown = document.querySelector('.sort-dropdown');

//     // Add click handler for project cards
//     projectCards.forEach(card => {
//         card.addEventListener('click', function() {
//             const projectId = this.querySelector('.project-id').textContent.replace('#PP', '');
//             window.location.href = `${URLROOT}/operationsCoordinator/managePreProject/${projectId}`;
//         });
//     });

//     // Search functionality
//     searchInput.addEventListener('input', function(e) {
//         e.stopPropagation(); // Prevent triggering card click
//         const searchTerm = this.value.toLowerCase().trim();

//         projectCards.forEach(card => {
//             const cardContent = card.textContent.toLowerCase();
//             card.style.display = cardContent.includes(searchTerm) ? 'block' : 'none';
//         });
//     });

//     // Sort functionality
//     sortDropdown.addEventListener('change', function(e) {
//         e.stopPropagation(); // Prevent triggering card click
//         const cards = Array.from(projectCards);

//         cards.sort((a, b) => {
//             // Extract dates from the cards
//             const dateA = new Date(extractDate(a));
//             const dateB = new Date(extractDate(b));

//             return this.value === 'newest' ? dateB - dateA : dateA - dateB;
//         });

//         // Clear and repopulate the grid
//         projectsGrid.innerHTML = '';
//         cards.forEach(card => projectsGrid.appendChild(card));
//     });

//     // Helper function to extract date from card
//     function extractDate(card) {
//         const dateText = card.querySelector('.project-details p:nth-child(3)').textContent;
//         return dateText.replace('📅 ', '');
//     }

//     // Phase filter functionality
//     const phaseButtons = document.querySelectorAll('.phase-btn');
//     phaseButtons.forEach(button => {
//         button.addEventListener('click', function(e) {
//             e.stopPropagation();
//             const phase = this.dataset.phase;
            
//             if (phase === 'all') {
//                 projectCards.forEach(card => card.style.display = 'block');
//             } else {
//                 projectCards.forEach(card => {
//                     card.style.display = card.dataset.phase === phase ? 'block' : 'none';
//                 });
//             }
//         });
//     });

// });

// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Get all elements we need
    const cards = document.querySelectorAll('.project-card');
    const grid = document.getElementById('projectsGrid');
    const search = document.querySelector('.search-input');
    const sort = document.querySelector('.sort-dropdown');
    const buttons = document.querySelectorAll('.phase-btn');
    
    // Make cards clickable
    cards.forEach(card => {
        card.addEventListener('click', function() {
            // Get project ID from the card and go to project page
            const id = this.querySelector('.project-id').textContent.replace('#PP', '');
            window.location.href = `${URLROOT}/operationsCoordinator/managePreProject/${id}`;
        });
    });
    
    // Search functionality
    search.addEventListener('input', function(e) {
        e.stopPropagation();
        const term = this.value.toLowerCase();
        
        // Show/hide cards based on search term
        cards.forEach(card => {
            card.style.display = card.textContent.toLowerCase().includes(term) ? 'block' : 'none';
        });
    });
    
    // Sort functionality
    sort.addEventListener('change', function(e) {
        e.stopPropagation();
        
        // Sort cards by date
        const sortedCards = Array.from(cards).sort((a, b) => {
            // Get dates from cards
            const dateA = new Date(a.querySelector('.project-details p:nth-child(3)').textContent.replace('📅 ', ''));
            const dateB = new Date(b.querySelector('.project-details p:nth-child(3)').textContent.replace('📅 ', ''));
            
            // Sort newest or oldest based on dropdown selection
            return this.value === 'newest' ? dateB - dateA : dateA - dateB;
        });
        
        // Clear and rebuild grid with sorted cards
        grid.innerHTML = '';
        sortedCards.forEach(card => grid.appendChild(card));
    });
    
    // Phase filter functionality
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            
            // Get selected phase and highlight button
            const phase = this.dataset.phase;
            buttons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Go to first page of selected phase
            window.location.href = `?phase=${phase}&page=1`;
        });
    });
});

// Sidebar toggle function
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
    document.getElementById('overlay').classList.toggle('show');
}
