// let packages = [
//     {
//         id: 1,
//         title: "Basic Solar Package",
//         type: "on-grid",
//         description: "Perfect for small homes and residential applications. Includes all essential components for a reliable solar power system.",
//         price: 25000,
//         warrantyYears: 5,
//         features: [
//             "5kW System",
//             "24/7 Technical Support",
//             "Free Installation",
//             "Mobile Monitoring App"
//         ],
//         equipment: [
//             {
//                 name: "Solar Panels",
//                 details: "Tier 1 Monocrystalline Panels",
//                 link: "#"
//             },
//             {
//                 name: "Inverter",
//                 details: "High-efficiency Grid-tie Inverter",
//                 link: "#"
//             }
//         ]
//     }
// ];

// let selectedPackageId = null;

// function getPackageTypeBadge(type) {
//     if (!type) return '';
//     return `<span class="package-type-badge ${type}">${type.replace('-', ' ')}</span>`;
// }

// function renderPackages() {
//     const tbody = document.getElementById('packagesTableBody');
//     tbody.innerHTML = '';

//     packages.forEach(pkg => {
//         const tr = document.createElement('tr');
//         tr.className = `package-${pkg.type}`;
//         tr.innerHTML = `
//             <td>
//                 <div class="package-title">${pkg.title}</div>
//                 ${getPackageTypeBadge(pkg.type)}
//             </td>
//             <td>
//                 <div class="package-details">
//                     ${pkg.description}
//                     <div class="package-features">
//                         <strong>Features:</strong> ${pkg.features.join(', ')}
//                     </div>
//                 </div>
//             </td>
//             <td>Rs. ${pkg.price.toLocaleString()}</td>
//             <td>${pkg.warrantyYears} years</td>
//             <td>
//                 <div class="action-buttons">
//                     <button onclick="editPackage(${pkg.id})" class="action-managerBtn edit">
//                         <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
//                             <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/>
//                         </svg>
//                     </button>
//                     <button onclick="deletePackage(${pkg.id})" class="action-managerBtn delete">
//                         <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
//                             <path d="M3 6h18"/>
//                             <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
//                             <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
//                         </svg>
//                     </button>
//                 </div>
//             </td>
//         `;
//         tbody.appendChild(tr);
//     });
// }

// function openPopup(popupId) {
//     document.getElementById(popupId).classList.add('open');
//     document.getElementById('overlay').classList.add('open');
// }

// function closePopup(popupId) {
//     document.getElementById(popupId).classList.remove('open');
//     document.getElementById('overlay').classList.remove('open');
//     if (popupId === 'packageFormPopup') {
//         resetForm();
//     }
// }

// function resetForm() {
//     document.getElementById('packageForm').reset();
//     document.getElementById('packageId').value = '';
//     document.getElementById('featuresContainer').innerHTML = '';
//     document.getElementById('equipmentContainer').innerHTML = '';
//     addFeature();
//     addEquipment();
//     selectedPackageId = null;
// }

// function addFeature() {
//     const container = document.getElementById('featuresContainer');
//     const div = document.createElement('div');
//     div.className = 'feature-input';
//     div.innerHTML = `
//         <input type="text" name="features[]" placeholder="Enter feature" required>
//         <button type="button" class="remove-managerBtn" onclick="this.parentElement.remove()">×</button>
//     `;
//     container.appendChild(div);
// }

// function addEquipment() {
//     const container = document.getElementById('equipmentContainer');
//     const div = document.createElement('div');
//     div.className = 'equipment-input';
//     div.innerHTML = `
//         <input type="text" name="equipment_name[]" placeholder="Equipment Name" required>
//         <input type="text" name="equipment_details[]" placeholder="Details" required>
//         <input type="text" name="equipment_link[]" placeholder="Link" required>
//         <button type="button" class="remove-managerBtn" onclick="this.parentElement.remove()">×</button>
//     `;
//     container.appendChild(div);
// }

// function handleSubmit(event) {
//     event.preventDefault();
//     const formData = new FormData(event.target);
    
//     const packageData = {
//         title: formData.get('title'),
//         type: formData.get('type'),
//         description: formData.get('description'),
//         price: Number(formData.get('price')),
//         warrantyYears: Number(formData.get('warranty_years')),
//         features: Array.from(formData.getAll('features[]')),
//         equipment: Array.from(formData.getAll('equipment_name[]')).map((name, index) => ({
//             name,
//             details: formData.getAll('equipment_details[]')[index],
//             link: formData.getAll('equipment_link[]')[index]
//         }))
//     };

//     if (selectedPackageId) {
//         const index = packages.findIndex(p => p.id === selectedPackageId);
//         packages[index] = { ...packageData, id: selectedPackageId };
//     } else {
//         packageData.id = Math.max(0, ...packages.map(p => p.id)) + 1;
//         packages.push(packageData);
//     }

//     renderPackages();
//     closePopup('packageFormPopup');
// }

// function editPackage(id) {
//     const pkg = packages.find(p => p.id === id);
//     if (!pkg) return;

//     selectedPackageId = id;
//     document.getElementById('packageId').value = id;
//     document.getElementById('title').value = pkg.title;
//     document.getElementById('packageType').value = pkg.type;
//     document.getElementById('description').value = pkg.description;
//     document.getElementById('price').value = pkg.price;
//     document.getElementById('warranty').value = pkg.warrantyYears;

//     const featuresContainer = document.getElementById('featuresContainer');
//     featuresContainer.innerHTML = '';
//     pkg.features.forEach(feature => {
//         const div = document.createElement('div');
//         div.className = 'feature-input';
//         div.innerHTML = `
//             <input type="text" name="features[]" value="${feature}" required>
//             <button type="button" class="remove-managerBtn" onclick="this.parentElement.remove()">×</button>
//         `;
//         featuresContainer.appendChild(div);
//     });

//     const equipmentContainer = document.getElementById('equipmentContainer');
//     equipmentContainer.innerHTML = '';
//     pkg.equipment.forEach(item => {
//         const div = document.createElement('div');
//         div.className = 'equipment-input';
//         div.innerHTML = `
//             <input type="text" name="equipment_name[]" value="${item.name}" required>
//             <input type="text" name="equipment_details[]" value="${item.details}" required>
//             <input type="text" name="equipment_link[]" value="${item.link}" required>
//             <button type="button" class="remove-managerBtn" onclick="this.parentElement.remove()">×</button>
//         `;
//         equipmentContainer.appendChild(div);
//     });

//     document.getElementById('formTitle').textContent = 'Edit Package';
//     openPopup('packageFormPopup');
// }

// function deletePackage(id) {
//     selectedPackageId = id;
//     openPopup('deleteConfirmPopup');
// }

// function confirmDelete() {
//     packages = packages.filter(p => p.id !== selectedPackageId);
//     renderPackages();
//     closePopup('deleteConfirmPopup');
//     selectedPackageId = null;
// }

// document.getElementById('addPackagemanagerBtn').addEventListener('click', () => {
//     document.getElementById('formTitle').textContent = 'Add New Package';
//     resetForm();
    
//     openPopup('packageFormPopup');

// });

// // Initialize the table

// renderPackages();

// Initialize state management
let packages = [];
let inventoryItems = [];
let selectedPackageId = null;

// Fetch inventory items from database
async function fetchInventoryItems() {
    try {
        const response = await fetch('/api/inventory');
        inventoryItems = await response.json();
        console.log('Loaded inventory:', inventoryItems);
    } catch (error) {
        console.error('Error loading inventory:', error);
        alert('Failed to load inventory items');
    }
}

// Calculate final price
function calculateFinalPrice() {
    const basePrice = parseFloat(document.getElementById('price').value) || 0;
    const serviceCharge = parseFloat(document.getElementById('serviceCharge').value) || 0;
    let equipmentTotal = 0;

    // Calculate equipment total
    const equipmentInputs = document.querySelectorAll('.equipment-input');
    equipmentInputs.forEach(container => {
        const itemId = container.querySelector('select[name="equipment_id[]"]').value;
        const quantity = parseInt(container.querySelector('input[name="equipment_quantity[]"]').value) || 0;
        const item = inventoryItems.find(i => i.item_id === parseInt(itemId));
        
        if (item && quantity) {
            equipmentTotal += item.price * quantity;
        }
    });

    const finalPrice = basePrice + serviceCharge + equipmentTotal;
    document.getElementById('finalPrice').value = finalPrice.toFixed(2);
    return finalPrice;
}

// Render the packages table
function renderPackages() {
    const tbody = document.getElementById('packagesTableBody');
    tbody.innerHTML = '';

    packages.forEach(pkg => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <div class="package-image-container">
                    ${pkg.image_url ? 
                        `<img src="${pkg.image_url}" alt="${pkg.title}" class="package-image">` : 
                        '<div class="no-image">No Image</div>'}
                </div>
                <div class="package-title">${pkg.title}</div>
                <span class="package-type-badge ${pkg.type}">${pkg.type}</span>
            </td>
            <td>
                <div class="package-description">${pkg.description}</div>
                <div class="package-features">
                    <strong>Features:</strong>
                    <ul>${pkg.features.map(f => `<li>${f.feature_name}</li>`).join('')}</ul>
                </div>
                <div class="package-equipment">
                    <strong>Equipment:</strong>
                    <ul>${pkg.equipment.map(e => {
                        const item = inventoryItems.find(i => i.item_id === e.item_id);
                        return `<li>${item ? item.name : 'Unknown'} (Qty: ${e.quantity})</li>`;
                    }).join('')}</ul>
                </div>
            </td>
            <td>
                <div class="price-breakdown">
                    <div>Base Price: Rs. ${pkg.price.toLocaleString()}</div>
                    <div>Service: Rs. ${pkg.service_charge.toLocaleString()}</div>
                    <div class="final-price">Total: Rs. ${pkg.final_price.toLocaleString()}</div>
                </div>
            </td>
            <td>${pkg.warranty_years} years</td>
            <td>
                <div class="action-buttons">
                    <button onclick="editPackage(${pkg.package_id})" class="btn-edit">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button onclick="deletePackage(${pkg.package_id})" class="btn-delete">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// Handle image upload
function handleImageUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('imagePreview').src = e.target.result;
        document.getElementById('imagePreview').style.display = 'block';
    };
    reader.readAsDataURL(file);
}

// Add equipment row
function addEquipmentRow() {
    const container = document.getElementById('equipmentContainer');
    const row = document.createElement('div');
    row.className = 'equipment-input';
    
    row.innerHTML = `
        <select name="equipment_id[]" required onchange="updateEquipmentDetails(this)">
            <option value="">Select Equipment</option>
            ${inventoryItems.map(item => `
                <option value="${item.item_id}">
                    ${item.name} - Rs.${item.price} (Stock: ${item.quantity})
                </option>
            `).join('')}
        </select>
        <input type="number" name="equipment_quantity[]" min="1" 
               placeholder="Quantity" required onchange="calculateFinalPrice()">
        <button type="button" onclick="removeEquipmentRow(this)" class="btn-remove">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    container.appendChild(row);
}

// Remove equipment row
function removeEquipmentRow(button) {
    button.closest('.equipment-input').remove();
    calculateFinalPrice();
}

// Add feature row
function addFeatureRow() {
    const container = document.getElementById('featuresContainer');
    const row = document.createElement('div');
    row.className = 'feature-input';
    
    row.innerHTML = `
        <input type="text" name="features[]" placeholder="Enter feature" required>
        <button type="button" onclick="removeFeatureRow(this)" class="btn-remove">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    container.appendChild(row);
}

// Remove feature row
function removeFeatureRow(button) {
    button.closest('.feature-input').remove();
}

// Handle form submission
async function handleSubmit(event) {
    event.preventDefault();
    
    try {
        const formData = new FormData(event.target);
        const imageFile = document.getElementById('packageImage').files[0];
        
        if (imageFile) {
            formData.append('image', imageFile);
        }

        const packageData = {
            title: formData.get('title'),
            type: formData.get('type'),
            description: formData.get('description'),
            price: parseFloat(formData.get('price')),
            service_charge: parseFloat(formData.get('serviceCharge')),
            warranty_years: parseInt(formData.get('warranty_years')),
            features: Array.from(formData.getAll('features[]')).map(feature => ({
                feature_name: feature
            })),
            equipment: Array.from(formData.getAll('equipment_id[]')).map((id, index) => ({
                item_id: parseInt(id),
                quantity: parseInt(formData.getAll('equipment_quantity[]')[index])
            })).filter(e => e.item_id && e.quantity)
        };

        packageData.final_price = calculateFinalPrice();

        const url = selectedPackageId ? 
            `/api/packages/${selectedPackageId}` : 
            '/api/packages';
        
        const method = selectedPackageId ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(packageData)
        });

        if (!response.ok) throw new Error('Failed to save package');

        await fetchPackages();
        closePopup('packageFormPopup');
        resetForm();
        
    } catch (error) {
        console.error('Error saving package:', error);
        alert('Failed to save package');
    }
}

// Edit package
async function editPackage(packageId) {
    try {
        const response = await fetch(`/api/packages/${packageId}`);
        const package = await response.json();
        
        selectedPackageId = packageId;
        
        // Fill form fields
        document.getElementById('title').value = package.title;
        document.getElementById('packageType').value = package.type;
        document.getElementById('description').value = package.description;
        document.getElementById('price').value = package.price;
        document.getElementById('serviceCharge').value = package.service_charge;
        document.getElementById('warranty').value = package.warranty_years;
        
        // Clear and fill features
        const featuresContainer = document.getElementById('featuresContainer');
        featuresContainer.innerHTML = '';
        package.features.forEach(feature => {
            addFeatureRow();
            const lastInput = featuresContainer.lastChild.querySelector('input');
            lastInput.value = feature.feature_name;
        });
        
        // Clear and fill equipment
        const equipmentContainer = document.getElementById('equipmentContainer');
        equipmentContainer.innerHTML = '';
        package.equipment.forEach(equip => {
            addEquipmentRow();
            const lastRow = equipmentContainer.lastChild;
            lastRow.querySelector('select').value = equip.item_id;
            lastRow.querySelector('input[type="number"]').value = equip.quantity;
        });
        
        // Show image preview if exists
        if (package.image_url) {
            document.getElementById('imagePreview').src = package.image_url;
            document.getElementById('imagePreview').style.display = 'block';
        }
        
        calculateFinalPrice();
        openPopup('packageFormPopup');
        
    } catch (error) {
        console.error('Error loading package:', error);
        alert('Failed to load package details');
    }
}

// Delete package
async function deletePackage(packageId) {
    if (!confirm('Are you sure you want to delete this package?')) return;
    
    try {
        const response = await fetch(`/api/packages/${packageId}`, {
            method: 'DELETE'
        });
        
        if (!response.ok) throw new Error('Failed to delete package');
        
        await fetchPackages();
        
    } catch (error) {
        console.error('Error deleting package:', error);
        alert('Failed to delete package');
    }
}

// Reset form
function resetForm() {
    document.getElementById('packageForm').reset();
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('featuresContainer').innerHTML = '';
    document.getElementById('equipmentContainer').innerHTML = '';
    selectedPackageId = null;
    addFeatureRow();
    addEquipmentRow();
}

// Initialize
document.addEventListener('DOMContentLoaded', async function() {
    await fetchInventoryItems();
    await fetchPackages();
    
    // Add event listeners
    document.getElementById('price').addEventListener('input', calculateFinalPrice);
    document.getElementById('serviceCharge').addEventListener('input', calculateFinalPrice);
    document.getElementById('packageImage').addEventListener('change', handleImageUpload);
    
    document.getElementById('addPackageBtn').addEventListener('click', function() {
        resetForm();
        openPopup('packageFormPopup');
    });
});