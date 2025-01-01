document.addEventListener('DOMContentLoaded', function() {
    const scheduleForm = document.getElementById('scheduleForm');
    if (scheduleForm) {
        scheduleForm.addEventListener('submit', handleScheduleSubmit);
    }
});

async function handleScheduleSubmit(e) {
    e.preventDefault();
    
    const date = document.getElementById('visitDate').value;
    const time = document.getElementById('visitTime').value;

    // Validate time (8 AM to 4 PM)
    const selectedTime = new Date(`2000-01-01T${time}`);
    const minTime = new Date(`2000-01-01T08:00`);
    const maxTime = new Date(`2000-01-01T16:00`);

    if (selectedTime < minTime || selectedTime > maxTime) {
        showToast('Please select a time between 8 AM and 4 PM', 'error');
        return;
    }

    try {
        const response = await fetch(`${URLROOT}/operationsCoordinator/scheduleSiteVisit`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                pre_project_id: preProjectId,
                date: date,
                time: time
            })
        });

        const data = await response.json();

        if (response.ok) {
            showToast('Site visit scheduled successfully', 'success');
            setTimeout(() => location.reload(), 2000);
        } else {
            throw new Error(data.message || 'Failed to schedule site visit');
        }
    } catch (error) {
        showToast(error.message, 'error');
    }
}

async function completeSiteVisit() {
    const notes = document.getElementById('siteNotes').value.trim();
    
    if (!notes) {
        showToast('Please enter site visit notes', 'error');
        return;
    }

    try {
        const response = await fetch(`${URLROOT}/operationsCoordinator/completeSiteVisit`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                pre_project_id: preProjectId,
                notes: notes
            })
        });

        const data = await response.json();

        if (response.ok) {
            showToast('Site visit marked as completed', 'success');
            setTimeout(() => location.reload(), 2000);
        } else {
            throw new Error(data.message || 'Failed to complete site visit');
        }
    } catch (error) {
        showToast(error.message, 'error');
    }
}

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast ${type} show`;

    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

// let currentDate = new Date();
// let events = [];

// async function loadCalendarEvents() {
//     try {
//         const response = await fetch(`${URLROOT}/operationsCoordinator/getSiteVisitCalendar`);
//         events = await response.json();
//         console.log('Loaded events:', events); // Debug line
//         renderCalendar();
//     } catch (error) {
//         console.error('Error loading events:', error);
//     }
// }

// function renderCalendar() {
//     const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
//     const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
//     const startDayOfWeek = firstDay.getDay();
//     const daysInMonth = lastDay.getDate();

//     document.getElementById('currentMonth').textContent = 
//         currentDate.toLocaleString('default', { month: 'long', year: 'numeric' });

//     const calendarDates = document.getElementById('calendarDates');
//     calendarDates.innerHTML = '';

//     // Add empty cells for days before the first day of the month
//     for (let i = 0; i < startDayOfWeek; i++) {
//         const emptyCell = document.createElement('div');
//         emptyCell.className = 'calendar-date empty';
//         calendarDates.appendChild(emptyCell);
//     }

//     // Add cells for each day of the month
//     for (let day = 1; day <= daysInMonth; day++) {
//         const dateCell = document.createElement('div');
//         dateCell.className = 'calendar-date';
        
//         const date = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
//         const formattedDate = date.toISOString().split('T')[0]; // Format: YYYY-MM-DD
        
//         // Check if this date has any visits
//         const dayEvents = events.filter(event => {
//             return event.visit_date === formattedDate;
//         });

//         if (dayEvents.length > 0) {
//             dateCell.classList.add('has-visits');
//             // Add different indicators based on status
//             const statusTypes = dayEvents.map(event => event.status);
//             if (statusTypes.includes('scheduled')) dateCell.classList.add('scheduled-visit');
//             if (statusTypes.includes('confirmed')) dateCell.classList.add('confirmed-visit');
//             if (statusTypes.includes('completed')) dateCell.classList.add('completed-visit');

//             const indicator = document.createElement('div');
//             indicator.className = 'visit-indicator';
//             dateCell.appendChild(indicator);
//         }

//         if (date.toDateString() === new Date().toDateString()) {
//             dateCell.classList.add('today');
//         }

//         dateCell.innerHTML = `<span>${day}</span>`;

//         dateCell.addEventListener('click', () => showDaySchedule(formattedDate));
//         calendarDates.appendChild(dateCell);
//     }
// }

// function showDaySchedule(dateStr) {
//     document.querySelectorAll('.calendar-date').forEach(cell => {
//         cell.classList.remove('selected');
//     });

//     const dayEvents = events.filter(event => event.visit_date === dateStr);
//     const scheduleList = document.getElementById('scheduleList');
//     scheduleList.innerHTML = '';

//     if (dayEvents.length === 0) {
//         scheduleList.innerHTML = '<p>No site visits scheduled for this day</p>';
//         return;
//     }

//     dayEvents.forEach(event => {
//         const scheduleItem = document.createElement('div');
//         scheduleItem.className = `schedule-item ${event.status}`;
//         const time = new Date('1970-01-01T' + event.visit_time).toLocaleTimeString([], { 
//             hour: '2-digit', 
//             minute: '2-digit' 
//         });

//         scheduleItem.innerHTML = `
//             <div class="time">${time}</div>
//             <div class="details">
//                 <div class="customer">Customer: ${event.customer_name}</div>
//                 <div class="location">Location: ${event.nearest_city}</div>
//                 <div class="status">Status: ${event.status.replace('_', ' ')}</div>
//                 ${event.reschedule_request ? 
//                     `<div class="reschedule-note">Note: ${event.reschedule_request}</div>` : 
//                     ''}
//             </div>
//         `;
//         scheduleList.appendChild(scheduleItem);
//     });
// }

// function previousMonth() {
//     currentDate.setMonth(currentDate.getMonth() - 1);
//     renderCalendar();
// }

// function nextMonth() {
//     currentDate.setMonth(currentDate.getMonth() + 1);
//     renderCalendar();
// }

// Initial load
document.addEventListener('DOMContentLoaded', () => {
    loadCalendarEvents();
});