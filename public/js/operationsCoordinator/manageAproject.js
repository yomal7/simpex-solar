// Agreement phase 

document.addEventListener('DOMContentLoaded', () => {
    const header = document.getElementById('agreementHeader');
    const content = document.getElementById('agreementContent');
    let isExpanded = false;

    // Toggle phase content
    header.addEventListener('click', () => {
        isExpanded = !isExpanded;
        if (isExpanded) {
            content.classList.add('active');
        } else {
            content.classList.remove('active');
        }
    });

    // Sample items data
    let items = [];
    const itemsTableBody = document.getElementById('itemsTableBody');

    function calculateItemTotal(item) {
        return (item.quantity * item.price).toFixed(2);
    }

    function updateItemsTable() {
        itemsTableBody.innerHTML = items.map((item, index) => `
            <tr>
                <td>
                    <input type="text" value="${item.name}" 
                        onchange="updateItem(${index}, 'name', this.value)">
                </td>
                <td>
                    <input type="number" min="1" value="${item.quantity}" 
                        onchange="updateItem(${index}, 'quantity', this.value)">
                </td>
                <td>
                    <input type="number" min="0" step="0.01" value="${item.price}" 
                        onchange="updateItem(${index}, 'price', this.value)">
                </td>
                <td>$${calculateItemTotal(item)}</td>
                <td>
                    <button type="button" onclick="removeItem(${index})" class="btn btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 6L6 18M6 6l12 12"></path>
                        </svg>
                    </button>
                </td>
            </tr>
        `).join('');
        updateTotal();
    }

    // Add to global scope for inline event handlers
    window.updateItem = (index, field, value) => {
        items[index][field] = field === 'name' ? value : Number(value);
        updateTotal();
    };

    window.removeItem = (index) => {
        items.splice(index, 1);
        updateItemsTable();
    };

    window.updateTotal = () => {
        const subtotal = items.reduce((sum, item) => sum + (item.quantity * item.price), 0);
        const serviceCharge = Number(document.getElementById('serviceCharge').value) || 0;
        const total = subtotal + serviceCharge;

        document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('serviceChargeAmount').textContent = `$${serviceCharge.toFixed(2)}`;
        document.getElementById('totalAmount').textContent = `$${total.toFixed(2)}`;
    };

    // Add item button handler
    document.getElementById('addItemBtn').addEventListener('click', () => {
        items.push({
            name: 'New Item ' + (items.length + 1),
            quantity: 1,
            price: 0
        });
        updateItemsTable();
    });

    // Form submission handler
    document.getElementById('agreementForm').addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = {
            title: document.getElementById('title').value,
            description: document.getElementById('description').value,
            items: items,
            serviceCharge: Number(document.getElementById('serviceCharge').value),
            total: Number(document.getElementById('totalAmount').textContent.replace('$', ''))
        };
        console.log('Agreement submitted:', formData);
    });

    // Initialize table
    updateItemsTable();
});




// site visi and other dates conformation

document.addEventListener('DOMContentLoaded', () => {
    const header = document.getElementById('siteVisitHeader');
    const content = document.getElementById('siteVisitContent');
    const visitDateInput = document.getElementById('visitDate');
    const visitTimeInput = document.getElementById('visitTime');
    const visitForm = document.getElementById('siteVisitForm');
    const scheduleStatus = document.getElementById('scheduleStatus');
    const customerResponse = document.getElementById('customerResponse');
    const completeBtn = document.getElementById('completeBtn');
    const phaseStatus = document.getElementById('visitPhaseStatus');

    // Set minimum date to today
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    visitDateInput.min = tomorrow.toISOString().split('T')[0];

    // Toggle phase content
    header.addEventListener('click', () => {
        content.classList.toggle('active');
    });

    // Form submission handler
    visitForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const date = visitDateInput.value;
        const time = visitTimeInput.value;
        const notes = document.getElementById('visitNotes').value;

        // Validate date is not in the past
        const selectedDateTime = new Date(`${date}T${time}`);
        if (selectedDateTime <= new Date()) {
            alert('Please select a future date and time');
            return;
        }

        // Update status display
        scheduleStatus.style.display = 'block';
        scheduleStatus.className = 'visit-status scheduled';
        document.getElementById('statusMessage').textContent = 'Visit Scheduled';
        document.getElementById('scheduledDateTime').textContent = 
            `Date: ${formatDate(date)} | Time: ${formatTime(time)}`;
        
        phaseStatus.textContent = 'Scheduled';
        phaseStatus.className = 'phase-status status-scheduled';

        // Enable complete button (in real app, this would be enabled after customer confirms)
        completeBtn.disabled = false;

        // Simulate customer response (for demo purposes)
        setTimeout(simulateCustomerResponse, 2000);
    });

    // Complete button handler
    completeBtn.addEventListener('click', () => {
        phaseStatus.textContent = 'Completed';
        phaseStatus.className = 'phase-status status-completed';
        completeBtn.disabled = true;
    });

    function simulateCustomerResponse() {
        customerResponse.style.display = 'block';
        document.getElementById('customerMessage').textContent = 
            'Customer has confirmed the schedule.';
    }

    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    function formatTime(timeString) {
        return new Date(`2000/01/01 ${timeString}`).toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }
});

// first payment phase
document.addEventListener('DOMContentLoaded', () => {
    const header = document.getElementById('paymentHeader');
    const content = document.getElementById('paymentContent');
    const modal = document.getElementById('slipModal');

    // Toggle phase content
    header.addEventListener('click', () => {
        content.classList.toggle('active');
    });

    // Close modal when clicking outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('active');
        }
    });
});

function openSlipModal() {
    document.getElementById('slipModal').classList.add('active');
}

function showRejectionForm() {
    document.getElementById('rejectionForm').style.display = 'block';
}

function hideRejectionForm() {
    document.getElementById('rejectionForm').style.display = 'none';
}

function approvePayment() {
    const statusBadge = document.getElementById('paymentStatus');
    const statusMessage = document.getElementById('statusMessage');
    
    statusBadge.textContent = 'Payment Verified';
    statusBadge.className = 'phase-status status-completed';
    
    statusMessage.className = 'payment-status status-verified';
    statusMessage.innerHTML = `
        <h4>Payment Verified</h4>
        <p>Payment has been successfully verified at ${new Date().toLocaleString()}</p>
    `;

    // Disable action buttons
    document.querySelector('.payment-actions').style.display = 'none';
}

function rejectPayment() {
    const reason = document.getElementById('rejectionReason').value;
    if (!reason.trim()) {
        alert('Please provide a reason for rejection');
        return;
    }

    const statusBadge = document.getElementById('paymentStatus');
    const statusMessage = document.getElementById('statusMessage');
    
    statusBadge.textContent = 'Payment Rejected';
    statusBadge.className = 'phase-status status-rejected';
    
    statusMessage.className = 'payment-status status-rejected';
    statusMessage.innerHTML = `
        <h4>Payment Rejected</h4>
        <p>Reason: ${reason}</p>
        <p>Rejected at: ${new Date().toLocaleString()}</p>
    `;

    // Hide rejection form and action buttons
    hideRejectionForm();
    document.querySelector('.payment-actions').style.display = 'none';
}

// installion phase

document.addEventListener('DOMContentLoaded', () => {
    const header = document.getElementById('installationHeader');
    const content = document.getElementById('installationContent');
    const installDateInput = document.getElementById('installDate');
    const installTimeInput = document.getElementById('installTime');
    const installationForm = document.getElementById('installationForm');
    const scheduleStatus = document.getElementById('scheduleStatus');
    const customerResponse = document.getElementById('customerResponse');
    const completeBtn = document.getElementById('completeBtn');
    const phaseStatus = document.getElementById('installationPhaseStatus');

    // Set minimum date to today
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    installDateInput.min = tomorrow.toISOString().split('T')[0];

    // Toggle phase content
    header.addEventListener('click', () => {
        content.classList.toggle('active');
    });

    // Form submission handler
    installationForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const date = installDateInput.value;
        const time = installTimeInput.value;
        const notes = document.getElementById('installNotes').value;

        // Validate date is not in the past
        const selectedDateTime = new Date(`${date}T${time}`);
        if (selectedDateTime <= new Date()) {
            alert('Please select a future date and time for installation');
            return;
        }

        // Update status display
        scheduleStatus.style.display = 'block';
        scheduleStatus.className = 'visit-status scheduled';
        document.getElementById('statusMessage').textContent = 'Installation Date Proposed';
        document.getElementById('scheduledDateTime').textContent = 
            `Date: ${formatDate(date)} | Time: ${formatTime(time)}`;
        
        phaseStatus.textContent = 'Scheduled';
        phaseStatus.className = 'phase-status status-scheduled';

        // Simulate customer response (for demo purposes)
        setTimeout(simulateCustomerResponse, 2000);
    });

    // Customer response simulation
    function simulateCustomerResponse() {
        customerResponse.style.display = 'block';
        document.getElementById('customerMessage').textContent = 
            'Customer has confirmed the installation schedule.';
        
        // Enable complete button after customer confirms
        completeBtn.disabled = false;
    }

    // Installation completion verification
    completeBtn.addEventListener('click', () => {
        if (confirm('Are you sure the installation has been completed successfully?')) {
            phaseStatus.textContent = 'Installation Complete';
            phaseStatus.className = 'phase-status status-completed';
            completeBtn.disabled = true;
            
            // Update customer response to show completion
            customerResponse.style.display = 'block';
            document.getElementById('customerMessage').textContent = 
                'Installation has been completed and verified by the manager.';
            document.getElementById('customerMessage').style.fontWeight = 'bold';
        }
    });

    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    function formatTime(timeString) {
        return new Date(`2000/01/01 ${timeString}`).toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }
});

// Final payment phase

document.addEventListener('DOMContentLoaded', () => {
    const finalHeader = document.getElementById('finalPaymentHeader');
    const finalContent = document.getElementById('finalPaymentContent');
    const finalModal = document.getElementById('finalSlipModal');

    // Toggle phase content
    finalHeader.addEventListener('click', () => {
        finalContent.classList.toggle('active');
    });

    // Close modal when clicking outside
    finalModal.addEventListener('click', (e) => {
        if (e.target === finalModal) {
            finalModal.classList.remove('active');
        }
    });
});

function openFinalSlipModal() {
    document.getElementById('finalSlipModal').classList.add('active');
}

function showFinalRejectionForm() {
    document.getElementById('finalRejectionForm').style.display = 'block';
}

function hideFinalRejectionForm() {
    document.getElementById('finalRejectionForm').style.display = 'none';
}

function approveFinalPayment() {
    const statusBadge = document.getElementById('finalPaymentStatus');
    const statusMessage = document.getElementById('finalStatusMessage');
    
    statusBadge.textContent = 'Final Payment Verified';
    statusBadge.className = 'phase-status status-completed';
    
    statusMessage.className = 'payment-status status-verified';
    statusMessage.innerHTML = `
        <h4>Final Payment Verified</h4>
        <p>Final payment has been successfully verified at ${new Date().toLocaleString()}</p>
        <p>Project is now ready for engineer approval.</p>
    `;

    // Disable action buttons
    document.querySelector('.payment-actions').style.display = 'none';
}

function rejectFinalPayment() {
    const reason = document.getElementById('finalRejectionReason').value;
    if (!reason.trim()) {
        alert('Please provide a reason for rejection');
        return;
    }

    const statusBadge = document.getElementById('finalPaymentStatus');
    const statusMessage = document.getElementById('finalStatusMessage');
    
    statusBadge.textContent = 'Final Payment Rejected';
    statusBadge.className = 'phase-status status-rejected';
    
    statusMessage.className = 'payment-status status-rejected';
    statusMessage.innerHTML = `
        <h4>Final Payment Rejected</h4>
        <p>Reason: ${reason}</p>
        <p>Rejected at: ${new Date().toLocaleString()}</p>
    `;

    // Hide rejection form and action buttons
    hideFinalRejectionForm();
    document.querySelector('.payment-actions').style.display = 'none';
}

// Engineers approval phase


document.addEventListener('DOMContentLoaded', () => {
    const header = document.getElementById('engineerHeader');
    const content = document.getElementById('engineerContent');
    const inspectionDateInput = document.getElementById('inspectionDate');
    const inspectionTimeInput = document.getElementById('inspectionTime');
    const engineerForm = document.getElementById('engineerForm');
    const scheduleStatus = document.getElementById('scheduleStatus');
    const engineerResponse = document.getElementById('engineerResponse');
    const approvalBtn = document.getElementById('approvalBtn');
    const phaseStatus = document.getElementById('engineerPhaseStatus');

    // Set minimum date to tomorrow
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    inspectionDateInput.min = tomorrow.toISOString().split('T')[0];

    // Toggle phase content
    header.addEventListener('click', () => {
        content.classList.toggle('active');
    });

    // Form submission handler
    engineerForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const date = inspectionDateInput.value;
        const time = inspectionTimeInput.value;
        const notes = document.getElementById('engineerNotes').value;

        // Validate date is not in the past
        const selectedDateTime = new Date(`${date}T${time}`);
        if (selectedDateTime <= new Date()) {
            alert('Please select a future date and time for inspection');
            return;
        }

        // Update status display
        scheduleStatus.style.display = 'block';
        scheduleStatus.className = 'visit-status scheduled';
        document.getElementById('statusMessage').textContent = 'Engineer Inspection Scheduled';
        document.getElementById('scheduledDateTime').textContent = 
            `Inspection Date: ${formatDate(date)} | Time: ${formatTime(time)}`;
        
        phaseStatus.textContent = 'Inspection Scheduled';
        phaseStatus.className = 'phase-status status-scheduled';

        // Enable approval button (in real app, this would be enabled after inspection)
        setTimeout(() => {
            approvalBtn.disabled = false;
            simulateEngineerResponse();
        }, 2000);
    });

    // Approval button handler
    approvalBtn.addEventListener('click', () => {
        if(confirm('Confirm that both Engineer Approval and Grid Connection are completed?')) {
            phaseStatus.textContent = 'Approved & Connected';
            phaseStatus.className = 'phase-status status-completed';
            approvalBtn.disabled = true;

            engineerResponse.style.display = 'block';
            document.getElementById('engineerMessage').textContent = 
                'Engineer approval obtained and grid connection completed.';
            document.getElementById('engineerConfirmation').textContent = 
                `Verified on: ${new Date().toLocaleDateString()}`;
        }
    });

    function simulateEngineerResponse() {
        engineerResponse.style.display = 'block';
        document.getElementById('engineerMessage').textContent = 
            'Engineer has confirmed the inspection schedule.';
    }

    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    function formatTime(timeString) {
        return new Date(`2000/01/01 ${timeString}`).toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }
});