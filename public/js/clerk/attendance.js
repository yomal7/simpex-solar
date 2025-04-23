// More modern approach with cleaner syntax using async/await
async function clockIn(buttonElement) {
    const employeeId = buttonElement.getAttribute('data-employee-id');
    const date = buttonElement.getAttribute('data-date');
    
    try {
        // Create form data
        const formData = new FormData();
        formData.append('employee_id', employeeId);
        formData.append('date', date);
        
        // Make the request
        const response = await fetch(URLROOT + '/clerk/markClockIn', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.status === 'success') {
            // Update the UI
            const row = buttonElement.closest('tr');
            const statusCell = row.querySelector('.status-cell span');
            const timeInCell = row.querySelector('.time-in-cell');
            
            statusCell.textContent = 'Present';
            statusCell.className = 'attendance-status present';
            timeInCell.textContent = data.time;
            
            // Change button to Clock Out
            buttonElement.textContent = 'Clock Out';
            buttonElement.className = 'clock-out-btn';
            buttonElement.setAttribute('onclick', 'clockOut(this)');
            
            // Show success message
            alert('Employee clocked in successfully at ' + data.time);
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while processing your request.');
    }
}

// Function to handle Clock Out button click using async/await
async function clockOut(buttonElement) {
    const employeeId = buttonElement.getAttribute('data-employee-id');
    const date = buttonElement.getAttribute('data-date');
    
    try {
        // Create form data
        const formData = new FormData();
        formData.append('employee_id', employeeId);
        formData.append('date', date);
        
        // Make the request
        const response = await fetch(URLROOT + '/clerk/markClockOut', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.status === 'success') {
            // Update the UI
            const row = buttonElement.closest('tr');
            const timeOutCell = row.querySelector('.time-out-cell');
            
            timeOutCell.textContent = data.time;
            
            // Disable the button
            buttonElement.disabled = true;
            buttonElement.textContent = 'Completed';
            
            // Show success message
            alert('Employee clocked out successfully at ' + data.time);
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while processing your request.');
    }
}