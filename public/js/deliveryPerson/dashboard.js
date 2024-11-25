const projects = [
    {
      project_id: 101,
      quotation_id: 567,
      custom_package_id: 3001,
      status: "Pending", // Options: Pending, Approved, Rejected, Completed
      created_at: new Date("2024-11-01T10:00:00"),
      updated_at: new Date("2024-11-02T15:30:00"),
    },
    {
      project_id: 102,
      quotation_id: 568,
      custom_package_id: 3002,
      status: "Approved",
      created_at: new Date("2024-11-02T11:00:00"),
      updated_at: new Date("2024-11-03T16:00:00"),
    },
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