pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';

        let pdfDoc = null;
        const pdfContainer = document.getElementById('pdfContainer');
        const canvasContainer = document.getElementById('canvasContainer');

        // Load local PDF file
        async function loadPDF() {
            try {
                // Replace with your local PDF path
                const loadingTask = pdfjsLib.getDocument('./quatation1.pdf');
                const pdfDoc = await loadingTask.promise;
                renderAllPages(pdfDoc);
            } catch (error) {
                console.error(error);
                showToast('Error loading PDF', 'error');
            }
        }

        async function renderAllPages(pdfDoc) {
            const numPages = pdfDoc.numPages;
            const canvasContainer = document.getElementById('canvasContainer');  // Ensure this element exists in HTML
            for (let pageNum = 1; pageNum <= numPages; pageNum++) {
                const page = await pdfDoc.getPage(pageNum);
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');

                const viewport = page.getViewport({ scale: 1.5 });
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                await page.render({
                    canvasContext: context,
                    viewport: viewport
                }).promise;

                canvasContainer.appendChild(canvas);
            }
        }

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

        function openApprovePopup() {
            openPopup('approvePopup');
        }

        function openSubmitAgainPopup() {
            openPopup('submitAgainPopup');
        }

        function openCancelPopup() {
            openPopup('cancelPopup');
        }

        // Signature handling
        document.getElementById('signatureBox').addEventListener('click', function() {
            document.getElementById('signatureInput').click();
        });

        document.getElementById('signatureInput').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('signatureBox').innerHTML = '<img src="' + event.target.result + '" style="max-width: 100%; max-height: 200px;">';
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // Toast notification function
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

        // Button click handlers
        function downloadPDF() {
            showToast('PDF downloaded successfully', 'success');
        }

        function submitSignature() {
            showToast('Signature submitted successfully', 'success');
            closePopup('approvePopup');
        }

        function submitReview() {
            showToast('Review submitted for changes', 'info');
            closePopup('submitAgainPopup');
        }

        function confirmCancel() {
            showToast('Equation cancelled', 'warning');
            closePopup('cancelPopup');
        }

        // Initialize PDF viewer
        loadPDF();

        // back button functionality
        document.querySelector('.back-buttons').addEventListener('click', () => {
            window.history.back();
        });