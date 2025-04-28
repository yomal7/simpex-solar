document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const viewModal = document.getElementById('viewModal');
    const editModal = document.getElementById('editModal');
    const deleteModal = document.getElementById('deleteModal');
    let currentSupplierId = null;
    
    // Get the URL root from the data attribute
    const URLROOT = document.body.getAttribute('data-urlroot');
    
    // Close modal when clicking the X or outside the modal
    window.onclick = function(event) {
        if (event.target == editModal || event.target == deleteModal || event.target == viewModal) {
            viewModal.style.display = "none";
            editModal.style.display = "none";
            deleteModal.style.display = "none";
        }
    }
    
    // Close buttons
    document.querySelectorAll('.close').forEach(button => {
        button.addEventListener('click', function() {
            viewModal.style.display = "none";
            editModal.style.display = "none";
            deleteModal.style.display = "none";
        });
    });
    
    // Make these functions globally accessible
    window.deleteSupplier = function(id) {
        currentSupplierId = id;
        // Enhanced visibility for the modal
        if (deleteModal) {
            deleteModal.style.display = "block";
            deleteModal.style.opacity = "1";
            deleteModal.style.zIndex = "2000";
            console.log('Modal should be visible now');
        } else {
            console.error('Delete modal element not found');
        }
    }
    
    window.closeDeleteModal = function() {
        deleteModal.style.display = "none";
        currentSupplierId = null;
    }
    
    window.confirmDelete = async function() {
        if (!currentSupplierId) return;
        
        try {
            const response = await fetch(`${URLROOT}/supplierCoordinator/deleteSupplier`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${currentSupplierId}`
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
    window.viewSupplier = async function(id) {
        currentSupplierId = id;
        
        try {
            // Fetch the supplier details
            const response = await fetch(`${URLROOT}/supplierCoordinator/getSupplierDetails`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${id}`
            });
            
            const supplier = await response.json();
            
            if (supplier.error) {
                showToast('Error loading supplier details', 'error');
                return;
            }
            
            // Populate view modal elements
            document.getElementById('view-name').textContent = supplier.name;
            document.getElementById('view-email').textContent = supplier.email;
            document.getElementById('view-contact').textContent = supplier.contact_number;
            document.getElementById('view-address').textContent = supplier.address;
            document.getElementById('view-other-details').textContent = supplier.other_details || '-';
            
            // Show the modal
            if (viewModal) {
                viewModal.style.display = "block";
                viewModal.style.opacity = "1";
                viewModal.style.zIndex = "2000";
            } else {
                console.error('View modal element not found');
            }
        } catch (error) {
            showToast('Error loading supplier details', 'error');
            console.error('Error:', error);
        }
    }
    window.closeEditModal = function() {
        editModal.style.display = "none";
    }
    
    // Toast notification function
    function showToast(message, type = 'success') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        
        const textSpan = document.createElement('span');
        textSpan.textContent = message;
        
        toast.appendChild(textSpan);
        document.body.appendChild(toast);
        
        // Display toast
        setTimeout(() => toast.classList.add('show'), 100);
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
});