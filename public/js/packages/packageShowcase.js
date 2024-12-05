document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.nav-btn');
    const packages = document.querySelectorAll('.package-card');

    buttons.forEach(button => {
        button.addEventListener('click', function() {
            // Update active button
            buttons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const selectedType = this.getAttribute('data-type');

            // Show/hide packages based on type
            packages.forEach(package => {
                if (selectedType === 'all' || package.getAttribute('data-type') === selectedType) {
                    package.style.display = 'block';
                } else {
                    package.style.display = 'none';
                }
            });
        });
    });
});

