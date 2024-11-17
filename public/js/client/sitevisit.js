const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
let selectedDate = new Date();
let selectedTime = '14:30';

function generateCalendar() {
    const firstDay = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1);
    const lastDay = new Date(selectedDate.getFullYear(), selectedDate.getMonth() + 1, 0);
    const grid = document.getElementById('calendarGrid');
    grid.innerHTML = '';

    // Add day headers
    days.forEach(day => {
        const dayHeader = document.createElement('div');
        dayHeader.textContent = day;
        dayHeader.className = 'calendar-day header';
        grid.appendChild(dayHeader);
    });

    // Add empty cells for days before first day of month
    for (let i = 0; i < firstDay.getDay(); i++) {
        const emptyDay = document.createElement('div');
        emptyDay.className = 'calendar-day';
        grid.appendChild(emptyDay);
    }

    // Add days of month
    for (let i = 1; i <= lastDay.getDate(); i++) {
        const dayElement = document.createElement('div');
        dayElement.textContent = i;
        dayElement.className = 'calendar-day';
        
        if (i === selectedDate.getDate()) {
            dayElement.classList.add('active');
        }

        grid.appendChild(dayElement);
    }

    document.getElementById('currentMonth').textContent = 
        `${months[selectedDate.getMonth()]} ${selectedDate.getFullYear()}`;
}

function updateDisplays() {
    document.getElementById('visitDate').textContent = 
        `${months[selectedDate.getMonth()]} ${selectedDate.getDate()}`;
    
    const timeDisplay = new Date(`2024-01-01T${selectedTime}`);
    document.getElementById('visitTime').textContent = 
        timeDisplay.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
}

function showDatePicker() {
    const dateInput = document.getElementById('dateInput');
    const timeInput = document.getElementById('timeInput');
    
    dateInput.value = selectedDate.toISOString().split('T')[0];
    timeInput.value = selectedTime;

    document.getElementById('overlay').classList.add('active');
    document.getElementById('datePickerPopup').classList.add('active');
}

function closeDatePicker() {
    document.getElementById('overlay').classList.remove('active');
    document.getElementById('datePickerPopup').classList.remove('active');
}

function confirmDate() {
    showToast('success', 'Visit schedule confirmed successfully!');
}

function confirmNewDate() {
    const dateInput = document.getElementById('dateInput');
    const timeInput = document.getElementById('timeInput');

    if (dateInput.value && timeInput.value) {
        selectedDate = new Date(dateInput.value);
        selectedTime = timeInput.value;
        
        generateCalendar();
        updateDisplays();
        closeDatePicker();
        showToast('success', 'Visit schedule updated successfully!');
    }
}
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    const icon = type === 'success' ? '✓' :
                type === 'error' ? '✕' :
                type === 'warning' ? '⚠' : 'ℹ';
    
    toast.innerHTML = `
        <span class="toast-icon">${icon}</span>
        <span class="toast-message">${message}</span>
        <div class="toast-progress">
            <div class="toast-progress-bar"></div>
        </div>
    `;
    
    document.getElementById('toastContainer').appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Initialize calendar and displays
generateCalendar();
updateDisplays();