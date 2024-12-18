// Form submission handler
function handleSubmit(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());

    // Add employee_id from the PHP session data
    data.employee_id = employeeId;  // Using the employeeId that was made available from PHP

    // Make the POST request to the PHP backend
    fetch('http://localhost/simpex-solar/deliveryPerson/addRequest', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(responseData => {
        if (responseData.success) {
            alert('Holiday request submitted successfully!');
            event.target.reset(); // Reset the form

            // Optionally update the table or UI with the new request
            addRecordToTable({
                leave_type: data.leaveType,
                start_date: data.startDate,
                end_date: data.endDate,
                number_of_days: calculateDays(data.startDate, data.endDate),
                reason: data.reason,
                status: 'pending', // Default status for new submissions
            });
        } else {
            alert(responseData.message || 'Error submitting the holiday request.');
        }
    })
    .catch(error => {
        console.error('Error occurred:', error);
        alert('An error occurred. Please try again later.');
    });
}

// Calculate number of days between start and end date
function calculateDays(startDate, endDate) {
    const start = new Date(startDate);
    const end = new Date(endDate);
    return Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1; // Add 1 to include the start day
}

// Function to add a new record to the table (optional for UI update)
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
