document.getElementById('purchaseRequestForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitButton = document.querySelector('.submit-button');
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

    // Simulate form submission
    setTimeout(() => {
        // Here you would normally send the data to your server
        window.location.href = '/request-success';
    }, 2000);
});

document.getElementById('purchaseRequestForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Basic form validation
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('invalid');
        } else {
            field.classList.remove('invalid');
        }
    });

    if (!isValid) {
        alert('Please fill in all required fields');
        return;
    }

    const submitButton = document.querySelector('.submit-button');
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

    // Collect form data
    const formData = new FormData(this);
    
    // Simulate form submission
    setTimeout(() => {
        // Here you would normally send the data to your server
        // Example AJAX request:
        /*
        fetch('/api/purchase-request', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            window.location.href = '/request-success';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
        */

        window.location.href = '/request-success';
    }, 2000);
});

// Phone number validation
const phoneInputs = document.querySelectorAll('input[type="tel"]');
phoneInputs.forEach(input => {
    input.addEventListener('input', function(e) {
        // Remove any non-numeric characters
        this.value = this.value.replace(/[^\d+\-\s()]/g, '');
    });
});

// Date validation for installation date
const installationDateInput = document.getElementById('preferredInstallation');
const today = new Date().toISOString().split('T')[0];
installationDateInput.setAttribute('min', today);