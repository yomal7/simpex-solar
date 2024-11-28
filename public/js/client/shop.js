document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.order-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
});

// Delete functionality
const deleteButtons = document.querySelectorAll('.btn-delete');
deleteButtons.forEach(button => {
    button.addEventListener('click', function() {
        const card = this.closest('.order-card');
        card.style.animation = 'slideUp 0.5s ease reverse';
        setTimeout(() => {
            card.remove();
            // Check if there are no more orders
            if (document.querySelectorAll('.order-card').length === 0) {
                const emptyState = document.createElement('div');
                emptyState.className = 'empty-state';
                emptyState.innerHTML = '<h2>No Orders Found</h2><p>You haven\'t placed any orders yet.</p>';
                document.querySelector('.orders-grid').appendChild(emptyState);
            }
        }, 500);
    });
});