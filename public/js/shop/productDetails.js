const thumbnails = document.querySelectorAll('.thumbnail');
const mainImage = document.getElementById('mainImage');

thumbnails.forEach(thumbnail => {
    thumbnail.addEventListener('click', () => {
        // Remove active class from all thumbnails
        thumbnails.forEach(t => t.classList.remove('active'));
        // Add active class to clicked thumbnail
        thumbnail.classList.add('active');
        // Update main image
        mainImage.src = thumbnail.src;
    });
});

// Delivery Options
const deliveryOptions = document.querySelectorAll('.delivery-option');

deliveryOptions.forEach(option => {
    option.addEventListener('click', () => {
        // Remove selected class from all options
        deliveryOptions.forEach(opt => opt.classList.remove('selected'));
        // Add selected class to clicked option
        option.classList.add('selected');
        // Check the radio input
        option.querySelector('input[type="radio"]').checked = true;
    });
});

// Quantity Controls
const quantityInput = document.getElementById('quantity');
const decreaseBtn = document.getElementById('decreaseQuantity');
const increaseBtn = document.getElementById('increaseQuantity');

decreaseBtn.addEventListener('click', () => {
    const currentValue = parseInt(quantityInput.value);
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
});

increaseBtn.addEventListener('click', () => {
    const currentValue = parseInt(quantityInput.value);
    quantityInput.value = currentValue + 1;
});

// Purchase Request
const purchaseRequestBtn = document.getElementById('purchaseRequest');
const successMessage = document.getElementById('successMessage');

purchaseRequestBtn.addEventListener('click', () => {
    // Add loading state
    purchaseRequestBtn.disabled = true;
    purchaseRequestBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

    // Simulate API call
    setTimeout(() => {
        // Show success message
        successMessage.classList.add('show');
        
        // Reset button
        purchaseRequestBtn.disabled = false;
        purchaseRequestBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Request to Purchase';

        // Hide success message after 3 seconds
        setTimeout(() => {
            successMessage.classList.remove('show');
        }, 3000);
    }, 1500);
});