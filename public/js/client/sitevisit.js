function showRescheduleForm() {
    document.getElementById('reschedulePopup').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeRescheduleForm() {
    document.getElementById('reschedulePopup').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Date Range Validation
function validateDateRange() {
    const dateInput = document.querySelector('input[name="preferred_date"]');
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    const selectedDate = new Date(dateInput.value);
    selectedDate.setHours(0, 0, 0, 0);

    if (selectedDate <= today) {
        alert('Please select a future date');
        return false;
    }

    return true;
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Setup form validation
    const rescheduleForm = document.querySelector('#reschedulePopup form');
    if (rescheduleForm) {
        rescheduleForm.addEventListener('submit', function(e) {
            if (!validateDateRange()) {
                e.preventDefault();
            }
        });
    }

    // Close popup when clicking overlay
    document.getElementById('overlay').addEventListener('click', function() {
        closeRescheduleForm();
    });

    // Set minimum date for date input
    const dateInput = document.querySelector('input[name="preferred_date"]');
    if (dateInput) {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        dateInput.min = tomorrow.toISOString().split('T')[0];
    }
});
updateDisplays();