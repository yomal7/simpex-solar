function addEquipment() {
    const container = document.getElementById('equipment-container');
    const originalSelect = container.querySelector('select');
    const newRow = document.createElement('div');
    newRow.className = 'equipment-row';
    
    newRow.innerHTML = `
        <div class="form-group">
            <label>Select Item</label>
            <select name="item_id[]" class="form-control" required>
                ${originalSelect.innerHTML}
            </select>
        </div>
        <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="quantity[]" class="form-control" min="1" required>
        </div>
        <button type="button" class="btn-remove" onclick="removeEquipment(this)">×</button>
    `;
    
    container.appendChild(newRow);
    updateTotalPrice();
}

function addFeature() {
    const container = document.getElementById('features-container');
    const newRow = document.createElement('div');
    newRow.className = 'feature-row';
    
    newRow.innerHTML = `
        <div class="form-group">
            <label>Feature Name</label>
            <input type="text" name="feature_name[]" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <input type="text" name="feature_description[]" class="form-control" required>
        </div>
        <button type="button" class="btn-remove" onclick="removeFeature(this)">×</button>
    `;
    
    container.appendChild(newRow);
}

function removeEquipment(button) {
    const container = document.getElementById('equipment-container');
    if (container.children.length > 1) {
        button.closest('.equipment-row').remove();
        updateTotalPrice();
    }
}

function updateTotalPrice() {
    let total = 0;
    const serviceCharge = parseFloat(document.getElementById('service_charge').value) || 0;
    
    const equipmentRows = document.querySelectorAll('.equipment-row');
    equipmentRows.forEach(row => {
        const select = row.querySelector('select');
        const quantity = row.querySelector('input[type="number"]');
        
        if (select.value && quantity.value) {
            const price = parseFloat(select.options[select.selectedIndex].dataset.price);
            total += price * parseInt(quantity.value);
        }
    });
    
    total += serviceCharge;
    document.getElementById('total-price').textContent = total.toFixed(2);
}

// Add event listeners
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.package-form');
    
    form.addEventListener('change', function(e) {
        if (e.target.matches('select[name="item_id[]"]') || 
            e.target.matches('input[name="quantity[]"]') ||
            e.target.matches('input[name="service_charge"]')) {
            updateTotalPrice();
        }
    });

    // Image preview
    const imageInput = document.getElementById('package_image');
    const imagePreview = document.getElementById('image-preview');
    
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            }
            reader.readAsDataURL(file);
        } else {
            imagePreview.innerHTML = '';
        }
    });
});

function calculateTotalPrice() {
    let total = 0;
    const serviceCharge = parseFloat(document.getElementById('service_charge').value) || 0;
    
    // Calculate equipment costs
    const equipmentRows = document.querySelectorAll('.equipment-row');
    equipmentRows.forEach(row => {
        const select = row.querySelector('select');
        const quantity = row.querySelector('input[type="number"]');
        
        if (select.value && quantity.value) {
            const price = parseFloat(select.options[select.selectedIndex].dataset.price);
            total += price * parseInt(quantity.value);
        }
    });
    
    // Add service charge
    total += serviceCharge;
    
    // If you want to display the total somewhere
    if (document.getElementById('total-price')) {
        document.getElementById('total-price').textContent = total.toFixed(2);
    }
}



document.getElementById('package_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('image-preview');
    
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        }
        
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});