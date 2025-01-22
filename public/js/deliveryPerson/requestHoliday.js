const URLROOT = 'http://localhost/simpex-solar'

function calculateDays(startDate, endDate) {
    const start = new Date(startDate);
    const end = new Date(endDate);
    return Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1; // Add 1 to include the start day
}

function handleSubmit(event) {
    event.preventDefault();

    
    isSubmitting = true;
    console.log('handle submit');

    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    console.log('3.........Form data:', data);

    // Calculate number of days
    data.employee_id = employeeId;
    console.log('Employee ID:', employeeId); 
    
    const numberOfDays = calculateDays(formData.get('startDate'), formData.get('endDate'));
    formData.append('numberOfDays', numberOfDays);
    console.log('Number of days:', numberOfDays);
    
    console.log('Submitting data:', {
        employee_id: employeeId,
        startDate: formData.get('startDate'),
        endDate: formData.get('endDate'),
        numberOfDays: formData.get('numberOfDays'),
        leaveType: formData.get('leaveType'),
        reason: formData.get('reason')
    });
    
    console.log('starting fetch');
    
    fetch(`${URLROOT}/deliveryPerson/requestHoliday`, {
        method: 'POST',
        body: formData,
        credentials: 'include'
    })
    .then(response => {
        console.log('response:', response);
        if (response.ok) {
            alert('Holiday request submitted successfully!');
            window.location.reload(); // Reload to show the updated table
        } else {
            throw new Error('Request failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again later.');
    });
}

// Add this function
function resetForm() {
    const form = document.getElementById('requestHolidayForm');
    const today = new Date().toISOString().split('T')[0];
    
    // Reset form
    form.reset();
    
    // Reset date inputs to today
    document.getElementById('requestHolidayFormStartDate').value = '';
    document.getElementById('requestHolidayFormEndDate').value = '';
    
    // Reset leave type to default
    document.getElementById('requestHolidayFormLeaveType').selectedIndex = 0;
    
    // Clear reason
    document.getElementById('requestHolidayFormReason').value = '';
}

// Date validation setup
function setMinimumDate() {
    const today = new Date().toISOString().split('T')[0];
    const startDateInput = document.getElementById('requestHolidayFormStartDate');
    const endDateInput = document.getElementById('requestHolidayFormEndDate');

    startDateInput.min = today;
    endDateInput.min = today;
    

    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
    });
}

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('active');
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('requestHolidayForm');
    const cancelButton = document.querySelector('.request-holiday-form-cancel');
    if (form) {
        console.log('Form found and event listener attached');
        form.addEventListener('submit', handleSubmit);
    } 
    if (cancelButton) {
        cancelButton.addEventListener('click', function(e) {
            e.preventDefault();
            resetForm();
        });
    }
});

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
            closePopup('requestFormPopup');
        }
    });
});

// Initialize on page load
window.onload = setMinimumDate;