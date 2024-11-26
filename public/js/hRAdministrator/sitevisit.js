document.addEventListener('DOMContentLoaded', () => {
    const phaseHeader = document.getElementById('phaseHeader');
    const phaseContent = document.getElementById('phaseContent');
    const scheduleBtn = document.getElementById('scheduleBtn');
    const completeBtn = document.getElementById('completeBtn');
    const visitStatus = document.getElementById('visitStatus');
    const toast = document.getElementById('toastContainer');

    let isExpanded = false;
    let isScheduled = false;



    // Schedule visit
    scheduleBtn.addEventListener('click', () => {
        const date = document.getElementById('visitDate').value;
        const time = document.getElementById('visitTime').value;
        
        if (!date || !time) {
            showToast("warning", 'Please select both date and time');
            return;
        }

        isScheduled = true;
        completeBtn.disabled = false;
        showToast('Site visit scheduled successfully!');
        scheduleBtn.innerHTML = `
            <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Reschedule Visit
        `;
    });

    // Complete visit
    completeBtn.addEventListener('click', () => {
        visitStatus.textContent = 'Completed';
        visitStatus.className = 'phase-status status-completed';
        document.querySelector('.phase-box').classList.add('completed');
        showToast("success",'Site visit marked as completed!');
        completeBtn.disabled = true;
    });

    // Toast message handler
    function showToast(message) {
        toast.textContent = message;
        toast.classList.add('show');
        
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    // Form input handlers
    const inputs = document.querySelectorAll('.input-field');
    inputs.forEach(input => {
        input.addEventListener('focus', () => {
            input.parentElement.style.transform = 'translateX(5px)';
        });

        input.addEventListener('blur', () => {
            input.parentElement.style.transform = 'translateX(0)';
        });
    });

    function showToast(type = 'info', message) {
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
});