const requests = [
    { 
        id: 1, 
        leaveType: "Sick Leave", 
        startDate: "12-08-2024", 
        endDate: "13-08-2024", 
        numberOfDays: "2", 
        reason: "Fever and rest recommended by the doctor.", 
        status: "approved" 
    },
    { 
        id: 2, 
        leaveType: "Casual Leave", 
        startDate: "15-08-2024", 
        endDate: "16-08-2024", 
        numberOfDays: "2", 
        reason: "Personal work and family commitment.", 
        status: "pending" 
    },
    { 
        id: 3, 
        leaveType: "Maternity Leave", 
        startDate: "01-09-2024", 
        endDate: "30-09-2024", 
        numberOfDays: "30", 
        reason: "To take care of newborn and postnatal recovery.", 
        status: "not-approved" 
    }
];

const holidayRecordsTableBody = document.getElementById("holidayRecordsTableBody");
let currentrequestId;

function renderTable(requestsToRender = requests) {
    holidayRecordsTableBody.innerHTML = "";
    requestsToRender.forEach(request => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${request.leaveType}
            <td>${request.startDate}</td>
            <td>${request.endDate}</td>
            <td>${request.numberOfDays}</td>
            <td>${request.reason}</td>            
            <td><span class="status ${request.status}">${getStatusLabel(request.status)}</span></td>            
        `;
        row.addEventListener("click", (e) => {
                viewDetails(request.id);
        });
        holidayRecordsTableBody.appendChild(row);
    });
}

function getStatusLabel(status) {
    switch (status) {
        case "approved": return "Approved";
        case "pending": return "Pending";
        case "not-approved": return "Not Approved";
        default: return "";
    }
}

function setMinimumDate() {
    const today = new Date().toISOString().split('T')[0];

    const startDateInput = document.getElementById('requestHolidayFormStartDate');
    const endDateInput = document.getElementById('requestHolidayFormEndDate');

    startDateInput.min = today;
    endDateInput.min = today;
}

document.getElementById('requestHolidayFormStartDate').addEventListener('change', function() {
    const startDate = this.value;
    const endDateInput = document.getElementById('requestHolidayFormEndDate');
    endDateInput.min= startDate;
});

function viewDetails(requestId) {
    alert(`Viewing details for request with ID: ${requestId}`);
}

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('active');
}

function handleSubmit(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    console.log('Form submitted with data:', data);
    alert('Form submitted successfully!');
    event.target.reset();
}

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

renderTable();

window.onload = setMinimumDate;