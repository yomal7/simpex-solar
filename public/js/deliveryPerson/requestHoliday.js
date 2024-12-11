// const requests = [
//     { 
//         id: 1, 
//         leaveType: "Sick Leave", 
//         startDate: "12-08-2024", 
//         endDate: "13-08-2024", 
//         numberOfDays: "2", 
//         reason: "Fever and rest recommended by the doctor.", 
//         status: "approved" 
//     },
//     { 
//         id: 2, 
//         leaveType: "Casual Leave", 
//         startDate: "15-08-2024", 
//         endDate: "16-08-2024", 
//         numberOfDays: "2", 
//         reason: "Personal work and family commitment.", 
//         status: "not-approved" 
//     },
//     { 
//         id: 3, 
//         leaveType: "Maternity Leave", 
//         startDate: "01-09-2024", 
//         endDate: "30-09-2024", 
//         numberOfDays: "30", 
//         reason: "To take care of newborn and postnatal recovery.", 
//         status: "pending" 
//     }
// ];

// const holidayRecordsTableBody = document.getElementById("holidayRecordsTableBody");
// let currentrequestId;

// function renderTable(requestsToRender = requests) {
//     holidayRecordsTableBody.innerHTML = "";
//     requestsToRender.forEach(request => {
//         const row = document.createElement("tr");
//         row.innerHTML = `
//             <td>${request.leaveType}
//             <td>${request.startDate}</td>
//             <td>${request.endDate}</td>
//             <td>${request.numberOfDays}</td>
//             <td>${request.reason}</td>            
//             <td><span class="status ${request.status}">${getStatusLabel(request.status)}</span></td>            
//         `;
//         row.addEventListener("click", (e) => {
//                 viewDetails(request.id);
//         });
//         holidayRecordsTableBody.appendChild(row);
//     });
// }


function handleSubmit(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    
    // Use AJAX to send the data to the server
    fetch('<?php echo URLROOT; ?>/holidayRequest/requestHoliday', {
        method: 'POST',
        body: new URLSearchParams(data),
    })
    .then(response => response.json())
    .then(responseData => {
        if (responseData.success) {
            alert('Holiday request submitted successfully!');
            event.target.reset(); // Reset the form

            addRecordToTable({
                leave_type: data.leaveType,
                start_date: data.startDate,
                end_date: data.endDate,
                number_of_days: calculateDays(data.startDate, data.endDate),
                reason: data.reason,
                status: 'pending' // Default status for new submissions
            });
        } else {
            alert('Error submitting the holiday request.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again later.');
    });
}

// // Function to render holiday records (data from the backend)
// function renderTable(holidayRecords) {
//     const holidayRecordsTableBody = document.getElementById("holidayRecordsTableBody");
//     holidayRecordsTableBody.innerHTML = "";
    
//     holidayRecords.forEach(record => {
//         const row = document.createElement("tr");
//         row.innerHTML = `
//             <td>${record.leave_type}</td>
//             <td>${record.start_date}</td>
//             <td>${record.end_date}</td>
//             <td>${record.number_of_days}</td>
//             <td>${record.reason}</td>
//             <td class="${record.status}">${getStatusLabel(record.status)}</td>
//         `;
//         holidayRecordsTableBody.appendChild(row);
//     });
// }

function calculateDays(startDate, endDate) {
    const start = new Date(startDate);
    const end = new Date(endDate);
    return Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1; // Add 1 to include the start day
}


// Function to add a new record to the table
function addRecordToTable(record) {
    const holidayRecordsTableBody = document.getElementById("holidayRecordsTableBody");

    const row = document.createElement("tr");
    row.innerHTML = `
        <td>${record.leave_type}</td>
        <td>${record.start_date}</td>
        <td>${record.end_date}</td>
        <td>${record.number_of_days}</td>
        <td>${record.reason}</td>
        <td class="${record.status}">${getStatusLabel(record.status)}</td>
    `;

    holidayRecordsTableBody.appendChild(row);
}

function getStatusLabel(status) {
    switch (status) {
        case "approved": return "Approved";
        case "pending": return "Pending";
        case "not-approved": return "Not Approved";
        default: return "";
    }
}

// function setMinimumDate() {
//     const today = new Date().toISOString().split('T')[0];

//     const startDateInput = document.getElementById('requestHolidayFormStartDate');
//     const endDateInput = document.getElementById('requestHolidayFormEndDate');

//     startDateInput.min = today;
//     endDateInput.min = today;
    
// }

// document.getElementById('requestHolidayFormStartDate').addEventListener('change', function() {

//     const startDate = this.value;
//     const endDateInput = document.getElementById('requestHolidayFormEndDate');
    
//     endDateInput.min= startDate;
    
// });



function setMinimumDate() {
    const today = new Date().toISOString().split('T')[0];
    const startDateInput = document.getElementById('requestHolidayFormStartDate');
    const endDateInput = document.getElementById('requestHolidayFormEndDate');

    // Set the minimum date for Start Date and End Date
    startDateInput.min = today;
    endDateInput.min = today;

    // Update the End Date minimum dynamically when Start Date changes
    startDateInput.addEventListener('change', function () {
        const selectedStartDate = this.value;
        endDateInput.min = selectedStartDate; // Set End Date's min to the selected Start Date
    });
}

// Call the function on page load
//window.onload = setMinimumDate;



//document.addEventListener('DOMContentLoaded', setMinimumDate); 

function viewDetails(requestId) {
    //alert('Viewing details for request with ID: ${requestId}');
}

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('active');
}

// function handleSubmit(event) {
//     event.preventDefault();
//     const formData = new FormData(event.target);
//     const data = Object.fromEntries(formData.entries());
//     console.log('Form submitted with data:', data);
//     alert('Form submitted successfully!');
//     event.target.reset();
// }

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

//renderTable();

window.onload = setMinimumDate;