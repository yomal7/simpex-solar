document.querySelectorAll('.phase-header').forEach(header => {
    header.addEventListener('click', () => {
        const content = header.nextElementSibling;
        const dropdownIcon = header.querySelector('.dropdown-icon');
        const isExpanded = content.style.maxHeight;

        // Close all other phase contents
        document.querySelectorAll('.phase-content').forEach(otherContent => {
            if (otherContent !== content) {
                otherContent.style.maxHeight = null;
                const otherIcon = otherContent.previousElementSibling.querySelector('.dropdown-icon');
                otherIcon.style.transform = 'rotate(0deg)';
            }
        });

        // Toggle current phase content
        if (isExpanded) {
            content.style.maxHeight = null;
            dropdownIcon.style.transform = 'rotate(0deg)';
        } else {
            content.style.maxHeight = content.scrollHeight + 'px';
            dropdownIcon.style.transform = 'rotate(180deg)';
        }
    });
});

// Update progress bar for demonstration
let progress = 0;
const progressFill = document.querySelector('.progress-fill');
setInterval(() => {
    if (progress < 100) {
        progress += 1;
        progressFill.style.width = `${progress}%`;
    }
}, 100);