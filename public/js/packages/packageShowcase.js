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

function toggleInfo() {
    const content = document.getElementById('infoContent');
    const icon = document.querySelector('.toggle-icon');
    content.classList.toggle('active');
    icon.classList.toggle('active');
    
    // Load video when section is opened
    if(content.classList.contains('active')) {
        const iframe = document.querySelector('.video-wrapper iframe');
        if(iframe.getAttribute('src') === 'about:blank') {
            iframe.setAttribute('src', iframe.getAttribute('data-src'));
        }
    }
}

function calculateEnergy() {
    const billAmount = parseFloat(document.getElementById('monthlyBill').value);
    if(!billAmount || billAmount < 0) {
        alert('Please enter a valid bill amount');
        return;
    }
    
    // Calculate kWh (1kWh = 100Rs)
    const energyKwh = (billAmount / 100).toFixed(2);
    
    // Update result with animation
    const resultElement = document.getElementById('energyValue');
    let currentValue = parseFloat(resultElement.textContent);
    const targetValue = parseFloat(energyKwh);
    
    // Animate the number counting up
    const animationDuration = 1000; // 1 second
    const frames = 60;
    const increment = (targetValue - currentValue) / frames;
    
    let frame = 0;
    const animate = setInterval(() => {
        currentValue += increment;
        resultElement.textContent = currentValue.toFixed(2);
        frame++;
        
        if(frame >= frames) {
            clearInterval(animate);
            resultElement.textContent = targetValue;
        }
    }, animationDuration / frames);
}

