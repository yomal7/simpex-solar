document.addEventListener("DOMContentLoaded", function() {
    // Time-based greeting
    function getGreeting() {
        const hour = new Date().getHours();
        if (hour >= 5 && hour < 12) return "Good Morning";
        if (hour >= 12 && hour < 17) return "Good Afternoon";
        if (hour >= 17 && hour < 22) return "Good Evening";
        return "Good Night";
    }

    // Update greeting
    document.getElementById('greeting-message').textContent = getGreeting();

    // Simulate progress bar update
    function updateProgress(progress) {
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');
        
        progressBar.style.width = `${progress}%`;
        progressText.textContent = progress;
    }
    
    // Simulate progress update (for demo purposes)
    setTimeout(() => {
        updateProgress(75);
    }, 1000);

    // Update greeting every minute
    setInterval(() => {
        document.getElementById('greeting-message').textContent = getGreeting();
    }, 60000);

    // Example function to handle profile picture edit
    const editIcon = document.querySelector('.edit-icon');
    if (editIcon) {
        editIcon.addEventListener('click', function() {
            // You can implement file upload functionality here
            alert('Edit profile picture functionality will be implemented here');
        });
    }
});

// timeline

document.querySelector('.back-button').addEventListener('click', () => {
    window.history.back();
});

// Add click handler for proceed button
document.querySelector('.proceed-button').addEventListener('click', function() {
    // Add your logic for proceeding to next step
    alert('Proceeding to next step...');
});

// Intersection Observer for animation on scroll
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.timeline-item').forEach(item => {
    observer.observe(item);
});