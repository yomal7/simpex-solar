// let currentSupplierId = null;
// const viewModal = document.getElementById('viewModal');
// const editModal = document.getElementById('editModal');
// const deleteModal = document.getElementById('deleteModal');

// // Close modal when clicking the X or outside the modal
// document.querySelectorAll('.close, .modal').forEach(element => {
//     element.addEventListener('click', (e) => {
//         if (e.target === element) {
//             viewModal.style.display = 'none';
//             editModal.style.display = 'none';
//             deleteModal.style.display = 'none';
//         }
//     });
// });

// // View Supplier Details
// async function viewSupplier(supplierId) {
//     try {
//         const response = await fetch(`${URLROOT}/supplierCoordinator/getSupplierDetails`, {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/x-www-form-urlencoded',
//             },
//             body: `supplier_id=${supplierId}`
//         });
        
//         const supplier = await response.json();
        
//         if (supplier.error) {
//             showToast('Error loading supplier details', 'error');
//             return;
//         }

//         // Populate modal with supplier details
//         document.getElementById('view-name').textContent = supplier.name;
//         document.getElementById('view-email').textContent = supplier.email;
//         document.getElementById('view-contact').textContent = supplier.contact_number;
//         document.getElementById('view-address').textContent = supplier.address;
//         document.getElementById('view-other-details').textContent = supplier.other_details;

//         viewModal.style.display = 'block';
//     } catch (error) {
//         showToast('Error loading supplier details', 'error');
//     }
// }

// // Edit Supplier
// async function editSupplier(supplierId) {
//     try {
//         const response = await fetch(`${URLROOT}/supplierCoordinator/getSupplierDetails`, {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/x-www-form-urlencoded',
//             },
//             body: `supplier_id=${supplierId}`
//         });
        
//         const supplier = await response.json();
        
//         if (supplier.error) {
//             showToast('Error loading supplier details', 'error');
//             return;
//         }

//         // Populate edit form
//         document.getElementById('edit-supplier-id').value = supplier.supplier_id;
//         document.getElementById('edit-name').value = supplier.name;
//         document.getElementById('edit-email').value = supplier.email;
//         document.getElementById('edit-contact').value = supplier.contact_number;
//         document.getElementById('edit-address').value = supplier.address;
//         document.getElementById('edit-other-details').value = supplier.other_details;

//         editModal.style.display = 'block';
//     } catch (error) {
//         showToast('Error loading supplier details', 'error');
//     }
// }

// // Handle edit form submission
// document.getElementById('editSupplierForm').addEventListener('submit', async (e) => {
//     e.preventDefault();
    
//     const formData = new FormData(e.target);
    
//     try {
//         const response = await fetch(`${URLROOT}/supplierCoordinator/editSupplier`, {
//             method: 'POST',
//             body: formData
//         });
        
//         const result = await response.json();
        
//         if (result.success) {
//             showToast('Supplier updated successfully', 'success');
//             editModal.style.display = 'none';
//             window.location.reload();
//         } else {
//             showToast('Error updating supplier', 'error');
//         }
//     } catch (error) {
//         showToast('Error updating supplier', 'error');
//     }
// });

// // Delete Supplier
// function deleteSupplier(supplierId) {
//     currentSupplierId = supplierId;
//     deleteModal.style.display = 'block';
// }

// function closeDeleteModal() {
//     deleteModal.style.display = 'none';
//     currentSupplierId = null;
// }

// async function confirmDelete() {
//     if (!currentSupplierId) return;
    
//     try {
//         const response = await fetch(`${URLROOT}/supplierCoordinator/deleteSupplier`, {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/x-www-form-urlencoded',
//             },
//             body: `supplier_id=${currentSupplierId}`
//         });
        
//         const result = await response.json();
        
//         if (result.success) {
//             showToast('Supplier deleted successfully', 'success');
//             deleteModal.style.display = 'none';
//             window.location.reload();
//         } else {
//             showToast('Error deleting supplier', 'error');
//         }
//     } catch (error) {
//         showToast('Error deleting supplier', 'error');
//     }
// }

// // Toast notification function
// function showToast(message, type = 'success') {
//     const toast = document.createElement('div');
//     toast.className = `toast toast-${type}`;
    
//     const icon = document.createElement('i');
//     icon.className = type === 'success' 
//         ? 'fas fa-check-circle'
//         : 'fas fa-exclamation-circle';
    
//     const textSpan = document.createElement('span');
//     textSpan.textContent = message;
    
//     toast.appendChild(icon);
//     toast.appendChild(textSpan);
//     document.body.appendChild(toast);
    
//     // Trigger animation
//     setTimeout(() => toast.classList.add('show'), 100);
    
//     // Remove toast after 3 seconds
//     setTimeout(() => {
//         toast.classList.remove('show');
//         setTimeout(() => toast.remove(), 300);
//     }, 3000);
// }

let currentSupplierId = null;
const viewModal = document.getElementById('viewModal');
const editModal = document.getElementById('editModal');
const deleteModal = document.getElementById('deleteModal');

// Close modal when clicking the X or outside the modal
document.querySelectorAll('.close, .modal').forEach(element => {
    element.addEventListener('click', (e) => {
        if (e.target === element) {
            viewModal.style.display = 'none';
            editModal.style.display = 'none';
            deleteModal.style.display = 'none';
        }
    });
});

// View Supplier Details
async function viewSupplier(id) {
    try {
        const response = await fetch(`${URLROOT}/supplierCoordinator/getSupplierDetails`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${id}`  // Changed from supplier_id to id
        });
        
        const supplier = await response.json();
        
        if (supplier.error) {
            showToast('Error loading supplier details', 'error');
            return;
        }

        // Populate modal with supplier details
        document.getElementById('view-name').textContent = supplier.name;
        document.getElementById('view-email').textContent = supplier.email;
        document.getElementById('view-contact').textContent = supplier.contact_number;
        document.getElementById('view-address').textContent = supplier.address;
        document.getElementById('view-other-details').textContent = supplier.other_details || 'N/A';

        viewModal.style.display = 'block';
    } catch (error) {
        showToast('Error loading supplier details', 'error');
        console.error('Error:', error);
    }
}

// Edit Supplier
async function editSupplier(id) {
    try {
        const response = await fetch(`${URLROOT}/supplierCoordinator/getSupplierDetails`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${id}`  // Changed from supplier_id to id
        });
        
        const supplier = await response.json();
        
        if (supplier.error) {
            showToast('Error loading supplier details', 'error');
            return;
        }

        // Populate edit form
        document.getElementById('edit-id').value = supplier.id;  // Changed to match the form field ID
        document.getElementById('edit-name').value = supplier.name;
        document.getElementById('edit-email').value = supplier.email;
        document.getElementById('edit-contact').value = supplier.contact_number;
        document.getElementById('edit-address').value = supplier.address;
        document.getElementById('edit-other-details').value = supplier.other_details || '';

        editModal.style.display = 'block';
    } catch (error) {
        showToast('Error loading supplier details', 'error');
        console.error('Error:', error);
    }
}

// Handle edit form submission
document.getElementById('editSupplierForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    
    try {
        const response = await fetch(`${URLROOT}/supplierCoordinator/updateSupplier`, {  // Changed endpoint
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('Supplier updated successfully', 'success');
            editModal.style.display = 'none';
            window.location.reload();
        } else {
            showToast('Error updating supplier', 'error');
        }
    } catch (error) {
        showToast('Error updating supplier', 'error');
        console.error('Error:', error);
    }
});

// Delete Supplier
function deleteSupplier(id) {
    currentSupplierId = id;
    deleteModal.style.display = 'block';
}

function closeDeleteModal() {
    deleteModal.style.display = 'none';
    currentSupplierId = null;
}

async function confirmDelete() {
    if (!currentSupplierId) return;
    
    try {
        const response = await fetch(`${URLROOT}/supplierCoordinator/deleteSupplier`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${currentSupplierId}`  // Changed from supplier_id to id
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('Supplier deleted successfully', 'success');
            deleteModal.style.display = 'none';
            window.location.reload();
        } else {
            showToast('Error deleting supplier', 'error');
        }
    } catch (error) {
        showToast('Error deleting supplier', 'error');
        console.error('Error:', error);
    }
}

// Toast notification function
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    
    const icon = document.createElement('i');
    icon.className = type === 'success' 
        ? 'fas fa-check-circle'
        : 'fas fa-exclamation-circle';
    
    const textSpan = document.createElement('span');
    textSpan.textContent = message;
    
    toast.appendChild(icon);
    toast.appendChild(textSpan);
    document.body.appendChild(toast);
    
    // Trigger animation
    setTimeout(() => toast.classList.add('show'), 100);
    
    // Remove toast after 3 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Close modals
function closeEditModal() {
    editModal.style.display = 'none';
}