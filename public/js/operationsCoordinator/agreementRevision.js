let equipmentList = [];
let basePrice = 0;
let serviceCharge = 0;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize service charge
    serviceCharge = parseFloat(document.getElementById('service_charge').value) || 0;
    
    // Initialize equipment list from existing items if any
    initializeEquipmentList();
    
    // Add event listener for modal close button
    document.querySelector('.close').onclick = closeModal;
    
    // Add event listener for overlay click
    document.getElementById('overlay').onclick = closeModal;
});

function initializeEquipmentList() {
    const items = document.querySelectorAll('.equipment-item');
    equipmentList = Array.from(items).map(item => ({
        id: parseInt(item.dataset.id),
        name: item.querySelector('.item-name').textContent,
        quantity: parseInt(item.querySelector('.quantity-input').value),
        unitPrice: parseFloat(item.querySelector('.unit-price').textContent.replace('Rs. ', ''))
    }));
    
    updateEquipmentDisplay();
    calculateBasePrice();
}

function showInventoryModal() {
    const modal = document.getElementById('inventoryModal');
    const overlay = document.getElementById('overlay');
    modal.style.display = 'block';
    overlay.style.display = 'block';
    
    // Fetch and display inventory items
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

                list.appendChild(itemDiv);
            });

            if (availableItems.length === 0) {
                list.innerHTML = '<div class="no-items">No available equipment items</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to load inventory items', 'error');
        });
}

function closeModal() {
    document.getElementById('inventoryModal').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
}

function addEquipment(id, name, price) {
    // Check if already in list
    if (equipmentList.some(item => item.id === id)) {
        showToast('Item already added to equipment list', 'error');
        return;
    }

    equipmentList.push({
        id: id,
        name: name,
        quantity: 1,
        unitPrice: parseFloat(price)
    });
    
    updateEquipmentDisplay();
    calculateBasePrice();
    closeModal();
    showToast('Equipment added successfully', 'success');
}

function updateQuantity(index, newValue) {
    if (index >= 0 && index < equipmentList.length) {
        const quantity = parseInt(newValue);
        if (quantity > 0) {
            equipmentList[index].quantity = quantity;
            updateEquipmentDisplay();
            calculateBasePrice();
        }
    }
}

function removeEquipment(index) {
    if (index >= 0 && index < equipmentList.length) {
        equipmentList.splice(index, 1);
        updateEquipmentDisplay();
        calculateBasePrice();
        showToast('Equipment removed', 'success');
    }
}

function updateEquipmentDisplay() {
    const container = document.getElementById('equipmentList');
    if (!container) return;

    container.innerHTML = equipmentList.map((item, index) => `
        <div class="equipment-item" data-id="${item.id}">
            <div class="item-details">
                <span class="item-name">${item.name}</span>
                <div class="item-info">
                    <input type="number" value="${item.quantity}" min="1" 
                           class="quantity-input" onchange="updateQuantity(${index}, this.value)">
                    <span class="unit-price">Rs. ${item.unitPrice.toFixed(2)}</span>
                    <span class="total-price">Rs. ${(item.quantity * item.unitPrice).toFixed(2)}</span>
                </div>
            </div>
            <button type="button" class="remove-btn" onclick="removeEquipment(${index})">
                <span class="material-icons-sharp">delete</span>
            </button>
        </div>
    `).join('');
}

function calculateBasePrice() {
    basePrice = equipmentList.reduce((total, item) => {
        return total + (item.quantity * item.unitPrice);
    }, 0);
    
    document.getElementById('basePrice').textContent = `Rs. ${basePrice.toFixed(2)}`;
    document.querySelector('input[name="base_price"]').value = basePrice;
    updateTotalPrice();
}

function updateTotalPrice() {
    serviceCharge = parseFloat(document.getElementById('service_charge').value) || 0;
    const total = basePrice + serviceCharge;
    
    document.getElementById('totalPrice').textContent = `Rs. ${total.toFixed(2)}`;
    document.querySelector('input[name="total_price"]').value = total;
}

// Form submission handling
document.getElementById('agreementForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Validate required fields
    if (!validateForm()) {
        showToast('Please fill in all required fields', 'error');
        return;
    }

    // Create FormData object
    const formData = new FormData(this);

    // Add equipment list
    formData.append('equipment', JSON.stringify(equipmentList.map(item => ({
        id: item.id,
        quantity: item.quantity,
        unitPrice: item.unitPrice
    }))));

    // Add hidden fields for prices if not already in form
    if (!formData.get('base_price')) {
        formData.append('base_price', basePrice);
    }
    if (!formData.get('total_price')) {
        formData.append('total_price', basePrice + serviceCharge);
    }

    // Show loading state
    const submitBtn = document.querySelector('.submit-btn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="material-icons-sharp">hourglass_empty</span> Revising...';

    // Get agreement ID from the form's action URL
    const agreementId = AGREEMENT_ID;

    fetch(`${URLROOT}/operationsCoordinator/updateAgreement/${agreementId}`, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast('Agreement revised successfully', 'success');
            setTimeout(() => {
                window.location.href = `${URLROOT}/operationsCoordinator/preProjects`;
            }, 1500);
        } else {
            throw new Error(data.message || 'Error revising agreement');
        }
    })
    .catch(error => {
        console.error('Revision error:', error);
        showToast(error.message || 'Error revising agreement', 'error');
        // Reset button state
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<span class="material-icons-sharp">done</span> Revise Agreement';
    });
});

function validateForm() {
    // Validate system capacity
    const systemCapacity = document.getElementById('system_capacity').value;
    if (!systemCapacity || systemCapacity <= 0) {
        document.getElementById('system_capacity').focus();
        return false;
    }

    // Validate estimated generation
    const estimatedGeneration = document.getElementById('estimated_generation').value;
    if (!estimatedGeneration || estimatedGeneration <= 0) {
        document.getElementById('estimated_generation').focus();
        return false;
    }

    // Validate equipment list
    if (equipmentList.length === 0) {
        showToast('Please add at least one equipment item', 'error');
        return false;
    }

    // Validate signature
    const useExistingSignature = document.querySelector('input[name="use_existing_signature"]');
    const signatureFile = document.getElementById('signature_file');
    
    if (!useExistingSignature?.checked && (!signatureFile.files || signatureFile.files.length === 0)) {
        showToast('Please provide a signature', 'error');
        return false;
    }

    return true;
}

function showToast(message, type = 'info') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast ${type}`;
    toast.style.display = 'block';
    
    setTimeout(() => {
        toast.style.display = 'none';
    }, 3000);
}