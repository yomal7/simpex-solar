let equipmentList = [];
let basePrice = 0;
let serviceCharge = 0;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize service charge
    serviceCharge = parseFloat(document.getElementById('service_charge')?.value || 0);
    
    // Initialize equipment list from existing items if any
    initializeEquipmentList();
    
    // Add event listeners
    const closeButton = document.querySelector('.close');
    if (closeButton) {
        closeButton.onclick = closeModal;
    }
    
    const overlay = document.getElementById('overlay');
    if (overlay) {
        overlay.onclick = closeModal;
    }

    // Add event listeners to remove buttons
    document.querySelectorAll('.remove-btn').forEach((btn, index) => {
        btn.addEventListener('click', function() {
            removeEquipment(index);
        });
    });
});

function initializeEquipmentList() {
    const items = document.querySelectorAll('.equipment-item');
    
    console.log("Initializing equipment list with", items.length, "items");
    
    equipmentList = Array.from(items).map(item => {
        // Get the unit price text
        const unitPriceText = item.querySelector('.unit-price').textContent;
        console.log("Original unit price text:", unitPriceText);
        
        // Remove "Rs. " prefix and any commas, then parse as float
        let unitPrice = parseFloat(unitPriceText.replace('Rs. ', '').replace(/,/g, ''));
        
        // Important fix: Multiply by 1000 if price is suspiciously low
        if (unitPrice < 1000) {
            console.log("Price appears too low, multiplying by 1000:", unitPrice, "->", unitPrice * 1000);
            unitPrice = unitPrice * 1000;
        }
        
        console.log("Final unit price:", unitPrice);
        
        const quantity = parseInt(item.querySelector('.quantity-input').value);
        console.log("Quantity:", quantity);
        
        // Log the total calculation
        console.log("Total calculated:", quantity * unitPrice);
        
        return {
            id: parseInt(item.dataset.id),
            name: item.querySelector('.item-name').textContent,
            quantity: quantity,
            unitPrice: unitPrice
        };
    });
    
    console.log("Final equipment list:", equipmentList);
    
    updateEquipmentDisplay();
    calculateBasePrice();
}

function showInventoryModal() {
    const modal = document.getElementById('inventoryModal');
    const overlay = document.getElementById('overlay');
    if (modal) modal.style.display = 'block';
    if (overlay) overlay.style.display = 'block';
    
    // Fetch and display inventory items
    fetch(`${URLROOT}/operationsCoordinator/getInventory`)
        .then(response => response.json())
        .then(data => {
            const list = document.querySelector('.inventory-list');
            if (!list) return;
            
            list.innerHTML = '';

            // Filter out items already in equipmentList
            const availableItems = data.filter(item => 
                !equipmentList.some(equipment => equipment.id === item.id)
            );

            availableItems.forEach(item => {
                // Apply the same price correction for display
                let displayPrice = parseFloat(item.price);
                if (displayPrice < 1000) {
                    displayPrice = displayPrice * 1000;
                }
                
                const itemDiv = document.createElement('div');
                itemDiv.className = 'inventory-item';
                itemDiv.innerHTML = `
                    <div class="item-details">
                        <div class="item-info">
                            <span class="item-name">${item.name}</span>
                            <span class="item-price">Rs. ${displayPrice.toFixed(2)}</span>
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
    const modal = document.getElementById('inventoryModal');
    const overlay = document.getElementById('overlay');
    if (modal) modal.style.display = 'none';
    if (overlay) overlay.style.display = 'none';
}

function addEquipment(id, name, price) {
    // Check if already in list
    if (equipmentList.some(item => item.id === id)) {
        showToast('Item already added to equipment list', 'error');
        return;
    }
    
    // Parse price and apply same correction
    let unitPrice = parseFloat(price);
    console.log("Original new item price:", unitPrice);
    
    if (unitPrice < 1000) {
        console.log("New item price appears too low, multiplying by 1000");
        unitPrice = unitPrice * 1000;
    }
    
    console.log("Final new item price:", unitPrice);

    equipmentList.push({
        id: id,
        name: name,
        quantity: 1,
        unitPrice: unitPrice
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

    // Re-add event listeners to remove buttons
    document.querySelectorAll('.remove-btn').forEach((btn, index) => {
        btn.addEventListener('click', function() {
            removeEquipment(index);
        });
    });
}

function calculateBasePrice() {
    basePrice = equipmentList.reduce((total, item) => {
        return total + (item.quantity * item.unitPrice);
    }, 0);
    
    const basePriceElement = document.getElementById('basePrice');
    if (basePriceElement) {
        basePriceElement.textContent = `Rs. ${basePrice.toFixed(2)}`;
    }
    
    const basePriceInput = document.querySelector('input[name="base_price"]');
    if (basePriceInput) {
        basePriceInput.value = basePrice;
    }
    
    updateTotalPrice();
}

function updateTotalPrice() {
    const serviceChargeInput = document.getElementById('service_charge');
    serviceCharge = serviceChargeInput ? parseFloat(serviceChargeInput.value) || 0 : 0;
    const total = basePrice + serviceCharge;
    
    const totalPriceElement = document.getElementById('totalPrice');
    if (totalPriceElement) {
        totalPriceElement.textContent = `Rs. ${total.toFixed(2)}`;
    }
    
    const totalPriceInput = document.querySelector('input[name="total_price"]');
    if (totalPriceInput) {
        totalPriceInput.value = total;
    }
}

// Form submission handling
const agreementForm = document.getElementById('agreementForm');
if (agreementForm) {
    agreementForm.addEventListener('submit', function(e) {
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
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="material-icons-sharp">hourglass_empty</span> Generating...';
        }

        fetch(`${URLROOT}/operationsCoordinator/saveAgreement`, {
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
                showToast('Agreement generated successfully', 'success');
                setTimeout(() => {
                    window.location.href = `${URLROOT}/operationsCoordinator/preProjects`;
                }, 1500);
            } else {
                throw new Error(data.message || 'Error saving agreement');
            }
        })
        .catch(error => {
            console.error('Save error:', error);
            showToast(error.message || 'Error generating agreement', 'error');
            // Reset button state
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span class="material-icons-sharp">done</span> Generate Agreement';
            }
        });
    });
}

function validateForm() {
    // Validate system capacity
    const systemCapacity = document.getElementById('system_capacity');
    if (systemCapacity && (!systemCapacity.value || parseFloat(systemCapacity.value) <= 0)) {
        systemCapacity.focus();
        return false;
    }

    // Validate estimated generation
    const estimatedGeneration = document.getElementById('estimated_generation');
    if (estimatedGeneration && (!estimatedGeneration.value || parseFloat(estimatedGeneration.value) <= 0)) {
        estimatedGeneration.focus();
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
    
    if (useExistingSignature && signatureFile) {
        if (!useExistingSignature.checked && (!signatureFile.files || signatureFile.files.length === 0)) {
            showToast('Please provide a signature', 'error');
            return false;
        }
    }

    return true;
}

function showToast(message, type = 'info') {
    const toast = document.getElementById('toast');
    if (!toast) return;
    
    toast.textContent = message;
    toast.className = `toast ${type}`;
    toast.style.display = 'block';
    
    setTimeout(() => {
        toast.style.display = 'none';
    }, 3000);
}