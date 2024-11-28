let packages = [
    {
        id: 1,
        title: "Basic Solar Package",
        type: "on-grid",
        description: "Perfect for small homes and residential applications. Includes all essential components for a reliable solar power system.",
        price: 25000,
        warrantyYears: 5,
        features: [
            "5kW System",
            "24/7 Technical Support",
            "Free Installation",
            "Mobile Monitoring App"
        ],
        equipment: [
            {
                name: "Solar Panels",
                details: "Tier 1 Monocrystalline Panels",
                link: "#"
            },
            {
                name: "Inverter",
                details: "High-efficiency Grid-tie Inverter",
                link: "#"
            }
        ]
    }
];

let selectedPackageId = null;

function getPackageTypeBadge(type) {
    if (!type) return '';
    return `<span class="package-type-badge ${type}">${type.replace('-', ' ')}</span>`;
}

function renderPackages() {
    const tbody = document.getElementById('packagesTableBody');
    tbody.innerHTML = '';

    packages.forEach(pkg => {
        const tr = document.createElement('tr');
        tr.className = `package-${pkg.type}`;
        tr.innerHTML = `
            <td>
                <div class="package-title">${pkg.title}</div>
                ${getPackageTypeBadge(pkg.type)}
            </td>
            <td>
                <div class="package-details">
                    ${pkg.description}
                    <div class="package-features">
                        <strong>Features:</strong> ${pkg.features.join(', ')}
                    </div>
                </div>
            </td>
            <td>Rs. ${pkg.price.toLocaleString()}</td>
            <td>${pkg.warrantyYears} years</td>
            <td>
                <div class="action-buttons">
                    <button onclick="editPackage(${pkg.id})" class="action-managerBtn edit">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/>
                        </svg>
                    </button>
                    <button onclick="deletePackage(${pkg.id})" class="action-managerBtn delete">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 6h18"/>
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                        </svg>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function openPopup(popupId) {
    document.getElementById(popupId).classList.add('open');
    document.getElementById('overlay').classList.add('open');
}

function closePopup(popupId) {
    document.getElementById(popupId).classList.remove('open');
    document.getElementById('overlay').classList.remove('open');
    if (popupId === 'packageFormPopup') {
        resetForm();
    }
}

function resetForm() {
    document.getElementById('packageForm').reset();
    document.getElementById('packageId').value = '';
    document.getElementById('featuresContainer').innerHTML = '';
    document.getElementById('equipmentContainer').innerHTML = '';
    addFeature();
    addEquipment();
    selectedPackageId = null;
}

function addFeature() {
    const container = document.getElementById('featuresContainer');
    const div = document.createElement('div');
    div.className = 'feature-input';
    div.innerHTML = `
        <input type="text" name="features[]" placeholder="Enter feature" required>
        <button type="button" class="remove-managerBtn" onclick="this.parentElement.remove()">×</button>
    `;
    container.appendChild(div);
}

function addEquipment() {
    const container = document.getElementById('equipmentContainer');
    const div = document.createElement('div');
    div.className = 'equipment-input';
    div.innerHTML = `
        <input type="text" name="equipment_name[]" placeholder="Equipment Name" required>
        <input type="text" name="equipment_details[]" placeholder="Details" required>
        <input type="text" name="equipment_link[]" placeholder="Link" required>
        <button type="button" class="remove-managerBtn" onclick="this.parentElement.remove()">×</button>
    `;
    container.appendChild(div);
}

function handleSubmit(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    
    const packageData = {
        title: formData.get('title'),
        type: formData.get('type'),
        description: formData.get('description'),
        price: Number(formData.get('price')),
        warrantyYears: Number(formData.get('warranty_years')),
        features: Array.from(formData.getAll('features[]')),
        equipment: Array.from(formData.getAll('equipment_name[]')).map((name, index) => ({
            name,
            details: formData.getAll('equipment_details[]')[index],
            link: formData.getAll('equipment_link[]')[index]
        }))
    };

    if (selectedPackageId) {
        const index = packages.findIndex(p => p.id === selectedPackageId);
        packages[index] = { ...packageData, id: selectedPackageId };
    } else {
        packageData.id = Math.max(0, ...packages.map(p => p.id)) + 1;
        packages.push(packageData);
    }

    renderPackages();
    closePopup('packageFormPopup');
}

function editPackage(id) {
    const pkg = packages.find(p => p.id === id);
    if (!pkg) return;

    selectedPackageId = id;
    document.getElementById('packageId').value = id;
    document.getElementById('title').value = pkg.title;
    document.getElementById('packageType').value = pkg.type;
    document.getElementById('description').value = pkg.description;
    document.getElementById('price').value = pkg.price;
    document.getElementById('warranty').value = pkg.warrantyYears;

    const featuresContainer = document.getElementById('featuresContainer');
    featuresContainer.innerHTML = '';
    pkg.features.forEach(feature => {
        const div = document.createElement('div');
        div.className = 'feature-input';
        div.innerHTML = `
            <input type="text" name="features[]" value="${feature}" required>
            <button type="button" class="remove-managerBtn" onclick="this.parentElement.remove()">×</button>
        `;
        featuresContainer.appendChild(div);
    });

    const equipmentContainer = document.getElementById('equipmentContainer');
    equipmentContainer.innerHTML = '';
    pkg.equipment.forEach(item => {
        const div = document.createElement('div');
        div.className = 'equipment-input';
        div.innerHTML = `
            <input type="text" name="equipment_name[]" value="${item.name}" required>
            <input type="text" name="equipment_details[]" value="${item.details}" required>
            <input type="text" name="equipment_link[]" value="${item.link}" required>
            <button type="button" class="remove-managerBtn" onclick="this.parentElement.remove()">×</button>
        `;
        equipmentContainer.appendChild(div);
    });

    document.getElementById('formTitle').textContent = 'Edit Package';
    openPopup('packageFormPopup');
}

function deletePackage(id) {
    selectedPackageId = id;
    openPopup('deleteConfirmPopup');
}

function confirmDelete() {
    packages = packages.filter(p => p.id !== selectedPackageId);
    renderPackages();
    closePopup('deleteConfirmPopup');
    selectedPackageId = null;
}

document.getElementById('addPackagemanagerBtn').addEventListener('click', () => {
    document.getElementById('formTitle').textContent = 'Add New Package';
    resetForm();
    
    openPopup('packageFormPopup');

});

// Initialize the table

renderPackages();