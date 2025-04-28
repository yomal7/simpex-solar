// agreement.js

// DOM Elements
const overlay = document.getElementById('overlay');
const approvePopup = document.getElementById('approvePopup');
const submitAgainPopup = document.getElementById('submitAgainPopup');
const cancelPopup = document.getElementById('cancelPopup');
const signatureBox = document.getElementById('signatureBox');
const signatureInput = document.getElementById('signatureInput');
const agreementId = document.getElementById('agreement_id').value;
const preProjectId = document.getElementById('pre_project_id').value;
const toastContainer = document.getElementById('toastContainer');

// Toggle sidebar
document.querySelector('nav i').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.toggle('open');
    document.querySelector('.content').classList.toggle('sidebar-open');
});

// Popup functions
function openPopup(popupId) {
    document.getElementById(popupId).style.display = 'block';
    overlay.style.display = 'block';
}

function closePopup(popupId) {
    document.getElementById(popupId).style.display = 'none';
    overlay.style.display = 'none';
}

function openApprovePopup() {
    openPopup('approvePopup');
}

function openSubmitAgainPopup() {
    openPopup('submitAgainPopup');
}

function openCancelPopup() {
    openPopup('cancelPopup');
}

// Click signature box to trigger file input
signatureBox.addEventListener('click', function() {
    signatureInput.click();
});

// Show selected image in signature box
signatureInput.addEventListener('change', function() {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            signatureBox.innerHTML = `<img src="${e.target.result}" alt="Signature Preview" style="max-width: 100%; max-height: 140px;">`;
        };
        reader.readAsDataURL(this.files[0]);
    }
});

// Submit signature
function submitSignature() {
    const fileInput = document.getElementById('signatureInput');
    if (!fileInput.files || !fileInput.files[0]) {
        showToast('Please select a signature image', 'error');
        return;
    }

    const formData = new FormData();
    formData.append('signature', fileInput.files[0]);
    formData.append('agreement_id', agreementId);

    fetch(`${URLROOT}/client/submitSignature`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        closePopup('approvePopup');
        if (data.success) {
            showToast('Agreement signed successfully', 'success');
            // Reload the page to show download button
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showToast(data.message || 'Failed to sign agreement', 'error');
        }
    })
    .catch(error => {
        closePopup('approvePopup');
        showToast('An error occurred. Please try again.', 'error');
        console.error('Error:', error);
    });
}

// Submit revision request
function submitReview() {
    const revisionNote = document.getElementById('revisionNote').value;
    if (!revisionNote.trim()) {
        showToast('Please enter revision details', 'error');
        return;
    }

    fetch(`${URLROOT}/client/requestRevision`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `agreement_id=${agreementId}&revision_note=${encodeURIComponent(revisionNote)}`
    })
    .then(response => response.json())
    .then(data => {
        closePopup('submitAgainPopup');
        if (data.success) {
            showToast('Revision request submitted successfully', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showToast(data.message || 'Failed to submit revision request', 'error');
        }
    })
    .catch(error => {
        closePopup('submitAgainPopup');
        showToast('An error occurred. Please try again.', 'error');
        console.error('Error:', error);
    });
}

// Cancel project
function confirmCancel() {
    fetch(`${URLROOT}/client/cancelProject`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `pre_project_id=${preProjectId}`
    })
    .then(response => response.json())
    .then(data => {
        closePopup('cancelPopup');
        if (data.success) {
            showToast('Project cancelled successfully', 'success');
            setTimeout(() => {
                window.location.href = `${URLROOT}/client/project`;
            }, 1500);
        } else {
            showToast(data.message || 'Failed to cancel project', 'error');
        }
    })
    .catch(error => {
        closePopup('cancelPopup');
        showToast('An error occurred. Please try again.', 'error');
        console.error('Error:', error);
    });
}

// Download agreement
function downloadAgreement(agreementId) {
    window.location.href = `${URLROOT}/client/downloadAgreement/${agreementId}`;
}

// Toast notification
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;
    toastContainer.appendChild(toast);

    // Auto remove after 3 seconds
    setTimeout(() => {
        toast.remove();
    }, 3000);
}