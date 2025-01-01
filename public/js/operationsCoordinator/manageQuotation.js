//Quotation phase
let equipmentList = [];
let basePrice = 0;
let serviceCharge = 0;


document.addEventListener('DOMContentLoaded', function() {
    serviceCharge = parseFloat(document.getElementById('initialServiceCharge').value) || 0;
    document.getElementById('serviceCharge').value = serviceCharge;

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
    document.querySelector('.close').onclick = closeModal;
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

function updateEquipmentDisplay() {
    const container = document.getElementById('equipmentList');
    if (!container) return;

    console.log('Updating display with list:', equipmentList);
    container.innerHTML = '';

    equipmentList.forEach((item, index) => {
        const row = document.createElement('div');
        row.className = 'equipment-item';
        row.dataset.id = item.id;
        
        const unitPrice = parseFloat(item.unit_price) || 0;
        const quantity = parseInt(item.quantity) || 0;
        const totalPrice = unitPrice * quantity;

        row.innerHTML = `
            <span class="item-name">${item.item_name}</span>
            <input type="number" value="${quantity}" min="1" class="quantity-input">
            <span class="unit-price">Rs. ${unitPrice.toFixed(2)}</span>
            <span class="total-price">Rs. ${totalPrice.toFixed(2)}</span>
            <button type="button" class="remove-btn">
                <span class="material-icons-sharp">delete</span>
            </button>
        `;

        // Add event listeners
        row.querySelector('.quantity-input').addEventListener('change', (e) => {
            if (e.target.value > 0) {
                updateQuantity(index, parseInt(e.target.value));
            }
        });

        row.querySelector('.remove-btn').addEventListener('click', () => {
            removeEquipment(index);
        });

        container.appendChild(row);
    });
    console.log('Display updated');
}

function calculateBasePrice() {
    basePrice = equipmentList.reduce((total, item) => {
        const price = parseFloat(item.unit_price) || 0;
        const quantity = parseInt(item.quantity) || 0;
        return total + (price * quantity);
    }, 0);
    
    document.getElementById('basePrice').textContent = `Rs. ${basePrice.toFixed(2)}`;
    updateTotalPrice();
}


function updateTotalPrice() {
    serviceCharge = parseFloat(document.getElementById('serviceCharge').value) || 0;
    const total = basePrice + serviceCharge;
    document.getElementById('totalPrice').textContent = `Rs. ${total.toFixed(2)}`;
}

function updateQuantity(index, value) {
    if (index >= 0 && index < equipmentList.length) {
        equipmentList[index].quantity = value;
        updateEquipmentDisplay();
        calculateBasePrice();
    }
}

// function updateQuantity(index, newQuantity) {
//     if (newQuantity > 0) {
//         equipmentList[index].quantity = parseInt(newQuantity);
//         if (equipmentList[index].is_from_package === 1) {
//             equipmentList[index].modification_type = 'modified';
//         }
//         updateEquipmentDisplay();
//         calculateBasePrice();
//     }
// }

function showInventoryModal() {
    const modal = document.getElementById('inventoryModal');
    const overlay = document.getElementById('overlay');
    
    modal.style.display = 'block';
    overlay.style.display = 'block';
    
    // Load inventory items
    fetch(`${URLROOT}/operationsCoordinator/getInventory`)
        .then(response => response.json())
        .then(data => {
            const list = document.querySelector('.inventory-list');
            list.innerHTML = '';

            // Filter out items already in equipmentList
            const availableItems = data.filter(item => 
                !equipmentList.some(equipment => equipment.id === item.id)
            );

            availableItems.forEach(item => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'inventory-item';
                itemDiv.innerHTML = `
                    <div class="item-details">
                        <div class="item-info">
                            <span class="item-name">${item.name}</span>
                            <span class="item-price">Rs. ${parseFloat(item.price).toFixed(2)}</span>
                        </div>
                        <div class="item-stock">
                            <span>In Stock: ${item.quantity}</span>
                        </div>
                    </div>
                    <button type="button" class="add-btn">
                        <span class="material-icons-sharp">add</span>
                    </button>
                `;

                itemDiv.querySelector('.add-btn').addEventListener('click', () => {
                    addEquipment(item.id, item.name, item.price);
                });

                if (item.description) {
                    const description = document.createElement('div');
                    description.className = 'item-description';
                    description.textContent = item.description;
                    itemDiv.querySelector('.item-details').appendChild(description);
                }

                list.appendChild(itemDiv);
            });

            if (availableItems.length === 0) {
                list.innerHTML = '<div class="no-items">No available equipment items</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Failed to load inventory items');
        });
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
            updateEquipmentDisplay();
            calculateBasePrice();
        }
    }
}

function addEquipment(id, name, price) {
    // Check if already in list
    if (equipmentList.some(item => item.id === id)) {
        showError('Item already added to equipment list');
        return;
    }

    equipmentList.push({
        id: id,
        name: name,
        quantity: 1,
        unitPrice: parseFloat(price)
    });
    
    updateEquipmentDisplay();
    calculatePrices();
    closeModal();
    showSuccess('Equipment added successfully');
}


function removeEquipment(index) {
    if (index >= 0 && index < equipmentList.length) {
        equipmentList = equipmentList.filter((_, i) => i !== index);
        console.log('Removed item, current list:', equipmentList);
        updateEquipmentDisplay();
        calculateBasePrice();
    }
}

function updateEquipmentDisplay() {
    const container = document.getElementById('equipmentList');
    if (!container) return;

    container.innerHTML = equipmentList.map((item, index) => `
        <div class="equipment-item" data-id="${item.id}">
            <span class="item-name">${item.name}</span>
            <input type="number" value="${item.quantity}" min="1" 
                   class="quantity-input" onchange="updateQuantity(${index}, this.value)">
            <span class="unit-price">Rs. ${item.unitPrice.toFixed(2)}</span>
            <span class="total-price">Rs. ${(item.quantity * item.unitPrice).toFixed(2)}</span>
            <button type="button" class="remove-btn" onclick="removeEquipment(${index})">
                <span class="material-icons-sharp">delete</span>
            </button>
        </div>
    `).join('');

    // Add animation class
    const items = container.querySelectorAll('.equipment-item');
    items.forEach(item => {
        item.classList.add('fade-in');
        setTimeout(() => item.classList.remove('fade-in'), 500);
    });
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