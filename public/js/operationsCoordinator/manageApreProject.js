// document.addEventListener('DOMContentLoaded', function() {
//     // Phase cards click handlers
//     const phaseCards = document.querySelectorAll('.phase-card');
//     phaseCards.forEach(card => {
//         card.addEventListener('click', function() {
//             const url = this.getAttribute('data-url');
//             if (url) {
//                 window.location.href = url;
//             }
//         });

//         // Hover effects
//         card.addEventListener('mouseenter', function() {
//             this.style.transform = 'translateY(-2px)';
//             this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
//         });
        
//         card.addEventListener('mouseleave', function() {
//             this.style.transform = 'translateY(0)';
//             this.style.boxShadow = 'none';
//         });
//     });

//     // Flash message auto-hide
//     const flashMessages = document.querySelectorAll('.flash-message');
//     flashMessages.forEach(message => {
//         setTimeout(() => {
//             message.style.opacity = '0';
//             setTimeout(() => message.remove(), 300);
//         }, 5000);
//     });

//     // Phase update handler
//     window.updatePhase = function(preProjectId, phase) {
//         fetch(`${URLROOT}/operationsCoordinator/updateProjectPhase`, {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/x-www-form-urlencoded',
//             },
//             body: `pre_project_id=${preProjectId}&phase=${phase}`
//         })
//         .then(response => response.json())
//         .then(data => {
//             if (data.success) {
//                 location.reload();
//             } else {
//                 alert('Failed to update phase. Please try again.');
//             }
//         })
//         .catch(error => {
//             console.error('Error:', error);
//             alert('An error occurred. Please try again.');
//         });
//     };
//     // Sidebar toggle
//     window.toggleSidebar = function() {
//         const sidebar = document.getElementById('sidebar');
//         const overlay = document.getElementById('overlay');
        
//         if (sidebar && overlay) {
//             sidebar.classList.toggle('active');
//             overlay.classList.toggle('active');
//         }
//     };
// });

// document.querySelectorAll('.phase-card').forEach(card => {
//     card.addEventListener('click', function() {
//         const url = this.dataset.url;
//         if (url) {
//             window.location.href = url;
//         }
//     });
// });

document.addEventListener('DOMContentLoaded', function() {
    // Get all phase cards and their statuses
    const phaseCards = document.querySelectorAll('.phase-card');
    const quotationCard = document.querySelector('.phase-card[data-url*="manageQuotation"]');
    const siteVisitCard = document.querySelector('.phase-card[data-url*="manageSiteVisit"]');
    const agreementCard = document.querySelector('.phase-card[data-url*="manageAgreement"]');
    
    const quotationStatus = quotationCard?.querySelector('.phase-status')?.textContent.toLowerCase().trim();
    const siteVisitStatus = siteVisitCard?.querySelector('.status-badge')?.textContent.toLowerCase().trim();

    // Phase cards click and hover handlers
    phaseCards.forEach(card => {
        const isSiteVisitPhase = card.getAttribute('data-url').includes('manageSiteVisit');
        const isAgreementPhase = card.getAttribute('data-url').includes('manageAgreement');

        // Check and apply phase restrictions
        if ((isSiteVisitPhase || isAgreementPhase) && quotationStatus !== 'accepted_by_customer') {
            card.style.opacity = '0.6';
            card.style.cursor = 'not-allowed';
            card.classList.add('disabled');
        }

        if (isAgreementPhase && siteVisitStatus !== 'completed') {
            card.style.opacity = '0.6';
            card.style.cursor = 'not-allowed';
            card.classList.add('disabled');
        }

        // Click handler
        card.addEventListener('click', function(e) {
            const url = this.getAttribute('data-url');
            
            if (this.classList.contains('disabled')) {
                e.preventDefault();
                
                if (isSiteVisitPhase) {
                    showFlashMessage('Please complete the quotation phase first.', 'warning');
                } else if (isAgreementPhase) {
                    if (quotationStatus !== 'accepted_by_customer') {
                        showFlashMessage('Please complete the quotation phase first.', 'warning');
                    } else if (siteVisitStatus !== 'completed') {
                        showFlashMessage('Please complete the site visit phase first.', 'warning');
                    }
                }
                return;
            }

            if (url) {
                window.location.href = url;
            }
        });

        // Hover effects
        card.addEventListener('mouseenter', function() {
            if (!this.classList.contains('disabled')) {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            if (!this.classList.contains('disabled')) {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            }
        });
    });

    // Flash message auto-hide
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            setTimeout(() => message.remove(), 300);
        }, 5000);
    });

    // Phase update handler
    window.updatePhase = function(preProjectId, phase) {
        fetch(`${URLROOT}/operationsCoordinator/updateProjectPhase`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `pre_project_id=${preProjectId}&phase=${phase}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                showFlashMessage('Failed to update phase. Please try again.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showFlashMessage('An error occurred. Please try again.', 'error');
        });
    };

    // Sidebar toggle
    window.toggleSidebar = function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        
        if (sidebar && overlay) {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }
    };
});

// Helper function to show flash messages
function showFlashMessage(message, type) {
    const flashMessage = document.createElement('div');
    flashMessage.className = `flash-message ${type}`;
    flashMessage.textContent = message;
    
    const container = document.querySelector('.phase-list');
    container.insertAdjacentElement('beforebegin', flashMessage);
    
    setTimeout(() => {
        flashMessage.style.opacity = '0';
        setTimeout(() => flashMessage.remove(), 300);
    }, 5000);
}

// Add CSS styles for the phase cards and messages
const style = document.createElement('style');
style.textContent = `
    .phase-card.disabled {
        pointer-events: auto;
        position: relative;
    }
    
    .phase-card.disabled::after {
        content: '🔒';
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.2em;
    }
    
    .flash-message {
        padding: 12px;
        margin-bottom: 15px;
        border-radius: 4px;
        transition: opacity 0.3s ease;
    }

    .flash-message.warning {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
    }

    .flash-message.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .flash-message.success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
`;
document.head.appendChild(style);