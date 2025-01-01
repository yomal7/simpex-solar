document.addEventListener('DOMContentLoaded', () => {
    // Tab switching
    const tabs = document.querySelectorAll('.tab');
    const projectCards = document.querySelectorAll('.project-card');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
        });
    });

    // Search functionality
    const search = document.querySelector('.search');
    search.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        projectCards.forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(searchTerm) ? 'block' : 'none';
        });
    });

    // Project card click
    projectCards.forEach(card => {
        card.addEventListener('click', () => {
            // Redirect to project details or show modal
            console.log('Project clicked:', card.querySelector('.project-id').textContent);
        });
    });
});