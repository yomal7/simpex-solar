pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';

document.addEventListener('DOMContentLoaded', function() {
    // Popup handling
    function openPopup(popupId) {
        document.getElementById(popupId).classList.add('open-popup');
        document.getElementById('overlay').style.visibility = 'visible';
        document.getElementById('overlay').style.opacity = '1';
    }

    function closePopup(popupId) {
        document.getElementById(popupId).classList.remove('open-popup');
        document.getElementById('overlay').style.visibility = 'hidden';
        document.getElementById('overlay').style.opacity = '0';
    }

    window.openApprovePopup = function() {
        openPopup('approvePopup');
    }

    window.openSubmitAgainPopup = function() {
        openPopup('submitAgainPopup');
    }

    window.openCancelPopup = function() {
        openPopup('cancelPopup');
    }

    window.closePopup = closePopup;

    // Handle signature upload preview
    const signatureBox = document.getElementById('signatureBox');
    const signatureInput = document.getElementById('signatureInput');

    if (signatureBox && signatureInput) {
        signatureBox.addEventListener('click', () => signatureInput.click());
        signatureInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    signatureBox.innerHTML = `<img src="${event.target.result}" style="max-width: 100%; max-height: 200px;">`;
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }

    // Toast notification function
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        const icon = type === 'success' ? '✓' : type === 'error' ? '✕' : type === 'warning' ? '⚠' : 'ℹ';
        toast.innerHTML = `
            <span class="toast-icon">${icon}</span>
            <span class="toast-message">${message}</span>
            <div class="toast-progress"><div class="toast-progress-bar"></div></div>
        `;
        document.getElementById('toastContainer').appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    // Submit handlers
    window.submitSignature = async function() {
        const signatureFile = signatureInput.files[0];
        if (!signatureFile) {
            showToast('Please select a signature', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('signature', signatureFile);
        formData.append('agreement_id', document.querySelector('[name="agreement_id"]').value);

        try {
            const response = await fetch(`${URLROOT}/client/submitSignature`, {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast('Signature uploaded successfully', 'success');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showToast(result.message || 'Upload failed', 'error');
            }
        } catch (error) {
            console.error('Signature upload error:', error);
            showToast('Error uploading signature', 'error');
        }
        
        closePopup('approvePopup');
    };

    window.submitReview = async function() {
        const revisionNote = document.querySelector('.comment-box').value;
        if (!revisionNote.trim()) {
            showToast('Please enter revision details', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('revision_note', revisionNote);
        formData.append('agreement_id', document.querySelector('[name="agreement_id"]').value);

        try {
            const response = await fetch(URLROOT + '/client/requestRevision', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            
            if (result.success) {
                showToast(result.message, 'success');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('Error submitting revision request', 'error');
        }
        closePopup('submitAgainPopup');
    }

    window.confirmCancel = async function() {
        try {
            const response = await fetch(URLROOT + '/client/cancelProject', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'pre_project_id=' + document.querySelector('[name="pre_project_id"]').value
            });
            const result = await response.json();
            
            if (result.success) {
                showToast(result.message, 'success');
                setTimeout(() => window.location.href = URLROOT + '/client/project', 1500);
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('Error cancelling project', 'error');
        }
        closePopup('cancelPopup');


        
    }
});