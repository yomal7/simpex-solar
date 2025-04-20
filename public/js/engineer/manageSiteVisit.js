// Toggle sidebar on mobile
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const menuToggle = document.querySelector('.menu-toggle');
    
    if (window.innerWidth <= 768 && 
        !sidebar.contains(event.target) && 
        !menuToggle.contains(event.target) &&
        sidebar.classList.contains('show')) {
        sidebar.classList.remove('show');
    }
});

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(event) {
            const notes = document.getElementById('site_notes');
            const checkboxes = document.querySelectorAll('.checklist-item input[type="checkbox"]');
            
            // Check if notes are filled
            if (!notes.value.trim()) {
                event.preventDefault();
                alert('Please enter site visit notes');
                notes.focus();
                return;
            }
            
            // Check if all checkboxes are checked
            let allChecked = true;
            checkboxes.forEach(checkbox => {
                if (!checkbox.checked) {
                    allChecked = false;
                }
            });
            
            if (!allChecked) {
                event.preventDefault();
                alert('Please complete all items in the checklist');
                return;
            }
        });
    }
});

// Close flash messages after a delay
document.addEventListener('DOMContentLoaded', function() {
    const flashMessage = document.querySelector('.alert');
    if (flashMessage) {
        setTimeout(() => {
            flashMessage.style.opacity = '0';
            setTimeout(() => {
                flashMessage.style.display = 'none';
            }, 500);
        }, 5000);
    }
});