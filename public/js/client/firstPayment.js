        // Toast notification system
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            const icon = type === 'success' ? '✓' :
                        type === 'error' ? '✕' :
                        type === 'warning' ? '⚠' : 'ℹ';
            
            toast.innerHTML = `
                <span class="toast-icon">${icon}</span>
                <span class="toast-message">${message}</span>
                <div class="toast-progress">
                    <div class="toast-progress-bar"></div>
                </div>
            `;
            
            document.getElementById('toastContainer').appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        // Popup management

        // handle card number spacing
        let cardNumInput = document.querySelector('#cardNum');
        cardNumInput.addEventListener('keyup', () => {
            let cNumber = cardNumInput.value.replace(/\s/g, "");
            if (!isNaN(cNumber)) {
                cardNumInput.value = cNumber.match(/.{1,4}/g)?.join(" ") || cNumber;
            }
        });

        function showPopup(popupId) {
            document.getElementById(popupId).classList.add('open-popup');
            document.getElementById('overlay').classList.add('active');
        }

        function closePopup(popupId) {
            document.getElementById(popupId).classList.remove('open-popup');
            document.getElementById('overlay').classList.remove('active');
        }


        function showOnlinePayment() {
            showPopup('onlinePaymentPopup');
            showToast('Please enter your payment details', 'info');
        }

        function showUploadSlip() {
            showPopup('uploadSlipPopup');
            showToast('Please upload your payment slip', 'info');
        }

        function updateStatus(status) {
            const statusIndicator = document.getElementById('statusIndicator');
            statusIndicator.textContent = status;
            statusIndicator.className = 'status-indicator ' + status.toLowerCase();
        }

        function handlePaymentSubmit(event) {
            event.preventDefault();
            closePopup('onlinePaymentPopup');
            showPopup('successPopup');
            updateStatus('Completed');
            showToast('Payment processed successfully!', 'success');
        }

        function triggerFileInput() {
            document.getElementById('slipInput').click();
        }

        function handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                closePopup('uploadSlipPopup');
                updateStatus('Under Review');
                showToast('Payment slip uploaded successfully!', 'success');
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            showToast('Welcome to the payment portal!', 'info');
        });
        // back button functionality
        document.querySelector('.back-buttons').addEventListener('click', () => {
            window.history.back();
        });