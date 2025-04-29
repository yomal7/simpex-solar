//Quotation phase
let equipmentList = [];
// let basePrice = 0;
// let serviceCharge = 0;
let basePrice = parseFloat(document.getElementById('basePrice').textContent.replace('Rs. ', '').replace(/,/g, '')) || 0;
let serviceCharge = parseFloat(document.getElementById('serviceCharge').value.replace(/,/g, '')) || 0;


document.addEventListener('DOMContentLoaded', function() {
    // serviceCharge = parseFloat(document.getElementById('initialServiceCharge').value) || 0;
    // document.getElementById('serviceCharge').value = serviceCharge;



    serviceCharge = parseFloat(document.getElementById('serviceCharge').value.replace(/,/g, '')) || 0;
    document.getElementById('serviceCharge').value = serviceCharge.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    // Get initial equipment from the DOM if available
    const packageEquipment = document.querySelectorAll('.equipment-item');
    if (packageEquipment.length > 0) {
        // Initialize from existing DOM elements
        packageEquipment.forEach(item => {
            const quantity = parseInt(item.querySelector('.quantity-input').value) || 0;
            const unitPrice = parseFloat(item.querySelector('.unit-price').textContent.replace('Rs. ', '')) || 0;
            const name = item.querySelector('.item-name').textContent;
            const id = parseInt(item.dataset.id);

            equipmentList.push({
                id: id,
                item_name: name,
                quantity: quantity,
                unit_price: unitPrice,
                is_original: true
            });
        });
        updateEquipmentDisplay();
        calculateBasePrice();
    } else {
        // Only try API if no DOM elements
        loadInitialEquipment();
    }

    // Modal close handler
    // document.querySelector('.close').onclick = closeModal;
});


function loadInitialEquipment() {
    const packageId = document.getElementById('packageId').value;
    
    if (!packageId) return;

    fetch(`${URLROOT}/operationsCoordinator/getPackageEquipment/${packageId}`)
        .then(async response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            try {
                const data = await response.json();
                return data;
            } catch (e) {
                const text = await response.text();
                console.error('Response was not JSON:', text);
                throw new Error('Invalid JSON response');
            }
        })
        .then(data => {
            if (Array.isArray(data)) {
                // Clear existing list to avoid duplicates
                equipmentList = [];
                // Add new items
                data.forEach(item => {
                    equipmentList.push({
                        id: item.item_id,
                        equipment_id: item.equipment_id,
                        item_name: item.item_name,
                        quantity: parseInt(item.quantity) || 1,
                        unit_price: parseFloat(item.unit_price) || 0,
                        is_original: true
                    });
                });
                updateEquipmentDisplay();
                calculateBasePrice();
                console.log('Equipment list loaded:', equipmentList);
            }
        })
        .catch(error => {
            console.error('Error loading equipment:', error);
            if (equipmentList.length === 0) {
                showFlashMessage('Error loading equipment. Please refresh.', 'error');
            }
        });
}






// function updateTotalPrice() {
//     serviceCharge = parseFloat(document.getElementById('serviceCharge').value) || 0;
//     const total = basePrice + serviceCharge;
//     document.getElementById('totalPrice').textContent = `Rs. ${total.toFixed(2)}`;
// }

// Update the total price calculation
function updateTotalPrice() {
    // Parse service charge as direct amount
    serviceCharge = parseFloat(document.getElementById('serviceCharge').value.replace(/,/g, '')) || 0;
    
    // Calculate total by adding base price and service charge
    const total = basePrice + serviceCharge;
    
    // Format numbers with commas and 2 decimal places
    const formattedTotal = total.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    const formattedServiceCharge = serviceCharge.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    // Update displays
    document.getElementById('serviceCharge').value = formattedServiceCharge;
    document.getElementById('totalPrice').textContent = `Rs. ${formattedTotal}`;
}

function calculateBasePrice() {
    basePrice = equipmentList.reduce((sum, item) => sum + (item.quantity * item.unit_price), 0);
    
    // Format the base price with commas and 2 decimal places
    const formattedBasePrice = basePrice.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    document.getElementById('basePrice').textContent = `Rs. ${formattedBasePrice}`;
    updateTotalPrice(); // Recalculate total price whenever base price changes
}


function updateQuantity(index, value) {
    if (index >= 0 && index < equipmentList.length) {
        equipmentList[index].quantity = value;
        // updateEquipmentDisplay();
        calculateBasePrice();
    }
}




function closeModal() {
    document.getElementById('inventoryModal').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
}


function initializeEquipmentList() {
    equipmentList = [];
    const items = document.querySelectorAll('.equipment-item');
    
    items.forEach(item => {
        const id = item.dataset.id;
        const name = item.querySelector('.item-name').textContent;
        const quantity = parseInt(item.querySelector('.quantity-input').value);
        const unitPrice = parseFloat(item.querySelector('.unit-price').textContent.replace('Rs. ', ''));
        
        equipmentList.push({
            id: parseInt(id),
            item_name: name,
            quantity: quantity,
            unit_price: unitPrice,
            is_from_package: 1,
            modification_type: 'original'
        });
    });
}

function updateQuantity(index, newValue) {
    if (index >= 0 && index < equipmentList.length) {
        const quantity = parseInt(newValue);
        if (quantity > 0) {
            equipmentList[index].quantity = quantity;
            if (equipmentList[index].is_from_package === 1) {
                equipmentList[index].modification_type = 'modified';
            }
            // updateEquipmentDisplay();
            calculateBasePrice();
        }
    }
}



function removeEquipment(index) {
    if (index >= 0 && index < equipmentList.length) {
        equipmentList = equipmentList.filter((_, i) => i !== index);
        console.log('Removed item, current list:', equipmentList);
        updateEquipmentDisplay();
        calculateBasePrice();
    }
}


function saveQuotation(type) {
    // Format equipment data to match backend expectations
    const formattedEquipment = equipmentList.map(item => ({
        inventory_id: item.id, // Map id to inventory_id
        quantity: item.quantity,
        unit_price: item.unit_price,
        total_price: item.quantity * item.unit_price,
        is_from_package: item.is_original ? 1 : 0, // Convert boolean to tinyint
        modification_type: item.is_original ? 'modified' : 'added'
    }));

    const data = {
        quotation_id: QUOTATION_ID,
        equipment: formattedEquipment, // Send formatted equipment data
        system_capacity: document.getElementById('systemCapacity').value,
        estimated_generation: document.getElementById('estimatedGeneration').value,
        base_price: basePrice,
        service_charge: serviceCharge,
        total_price: basePrice + serviceCharge,
        notes: document.getElementById('reviewNotes').value,
        status: type === 'draft' ? 'under_review' : 'reviewed'
    };

    console.log('Saving data:', data); // Debug

    fetch(`${URLROOT}/operationsCoordinator/saveReviewedQuotation`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showFlashMessage('Quotation saved successfully', 'success');
            if (type !== 'draft') {
                window.location.href = `${URLROOT}/operationsCoordinator/preProjects`;
            }
        } else {
            showFlashMessage(result.message || 'Error saving quotation', 'error');
        }
    })
    .catch(error => {
        console.error('Save error:', error);
        showFlashMessage('Error saving quotation', 'error');
    });
}

function showFlashMessage(message, type) {
    const flash = document.createElement('div');
    flash.className = `flash-message ${type}`;
    flash.textContent = message;
    document.querySelector('.quotation-review').prepend(flash);
    setTimeout(() => flash.remove(), 3000);
}


document.addEventListener('DOMContentLoaded', function() {
    // Get the quotation status
    const quotationStatus = document.querySelector('.status-badge').textContent.toLowerCase().trim();
    
    // Check if the quotation is in a non-editable state
    const isReadOnly = ['accepted_by_customer', 'reviewed'].includes(quotationStatus);
    
    if (isReadOnly) {
        // Disable all input fields
        const inputs = document.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.readOnly = true;
            input.classList.add('readonly');
        });
        
        // Disable quantity changes in equipment list
        const quantityInputs = document.querySelectorAll('.quantity-input');
        quantityInputs.forEach(input => {
            input.readOnly = true;
            input.classList.add('readonly');
        });
        
        // Remove event listeners from elements that should not be interactive
        document.getElementById('serviceCharge').removeEventListener('change', updateTotalPrice);
        
        // Hide the action buttons if they exist
        const actionButtons = document.querySelector('.action-buttons');
        if (actionButtons) {
            actionButtons.style.display = 'none';
        }
    }
    
});