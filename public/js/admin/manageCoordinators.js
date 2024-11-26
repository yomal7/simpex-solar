document.addEventListener('DOMContentLoaded', function() {
    // Modal Elements
    const addModal = document.getElementById('addModal');
    const editModal = document.getElementById('editModal');
    const deleteModal = document.getElementById('deleteModal');
    const addBtn = document.getElementById('addCoordinatorBtn');
    const closeBtns = document.querySelectorAll('.close');

    // Form Elements
    const addForm = document.getElementById('addCoordinatorForm');
    const editForm = document.getElementById('editCoordinatorForm');
    const deleteForm = document.getElementById('deleteCoordinatorForm');

    // Open Add Modal
    addBtn.onclick = function() {
        addModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    // Close Modals
    closeBtns.forEach(btn => {
        btn.onclick = function() {
            addModal.style.display = 'none';
            editModal.style.display = 'none';
            deleteModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });

    // Close on outside click
    window.onclick = function(event) {
        if (event.target == addModal || event.target == editModal || event.target == deleteModal) {
            addModal.style.display = 'none';
            editModal.style.display = 'none';
            deleteModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    // Form Validation
    addForm.onsubmit = function(e) {
        return validateForm(this, e);
    }

    editForm.onsubmit = function(e) {
        return validateForm(this, e);
    }

    function validateForm(form, e) {
        const email = form.querySelector('input[type="email"]');
        const password = form.querySelector('input[type="password"]');
        const name = form.querySelector('input[name="name"]');
        const role = form.querySelector('select[name="role"]');
        let isValid = true;

        // Clear previous error messages
        clearErrors(form);

        // Validate Name
        if (name.value.trim().length < 2) {
            showError(name, 'Name must be at least 2 characters long');
            isValid = false;
        }

        // Validate Email
        if (!isValidEmail(email.value)) {
            showError(email, 'Please enter a valid email address');
            isValid = false;
        }

        // Validate Password (only for add form)
        if (password && password.value.length < 6) {
            showError(password, 'Password must be at least 6 characters long');
            isValid = false;
        }

        // Validate Role
        if (!role.value) {
            showError(role, 'Please select a role');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
        return isValid;
    }

    // Helper Functions
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function showError(input, message) {
        const formGroup = input.closest('.form-group');
        const error = document.createElement('div');
        error.className = 'error-message';
        error.textContent = message;
        error.style.color = 'red';
        error.style.fontSize = '0.8rem';
        error.style.marginTop = '0.25rem';
        formGroup.appendChild(error);
        input.style.borderColor = 'red';
    }

    function clearErrors(form) {
        form.querySelectorAll('.error-message').forEach(error => error.remove());
        form.querySelectorAll('input, select').forEach(input => input.style.borderColor = '');
    }

    // Edit Modal Functions
    window.openEditModal = function(userId, name, email, role) {
        document.getElementById('edit_user_id').value = userId;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_role').value = role;
        editModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    // Delete Modal Functions
    window.openDeleteModal = function(userId, name) {
        document.getElementById('delete_user_id').value = userId;
        document.getElementById('deleteCoordinatorName').textContent = name;
        deleteModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    window.closeDeleteModal = function() {
        deleteModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Add animation classes for modals
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('animationend', function(e) {
            if (e.animationName === 'fadeOut') {
                modal.style.display = 'none';
            }
        });
    });

    // Success message animation
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(message => {
        message.style.animation = 'slideIn 0.5s ease-out';
        setTimeout(() => {
            message.style.animation = 'slideOut 0.5s ease-in forwards';
        }, 3000);
    });
});