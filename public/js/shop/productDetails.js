document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("purchaseForm");
  const deliveryOptions = document.querySelectorAll('input[name="delivery"]');
  const deliveryOptionInput = document.getElementById("deliveryOption");
  const quantityInput = document.getElementById("quantity");
  const decreaseBtn = document.getElementById("decreaseQuantity");
  const increaseBtn = document.getElementById("increaseQuantity");

  // Set initial delivery option
  deliveryOptionInput.value = document.querySelector(
    'input[name="delivery"]:checked'
  ).value;

  // Handle delivery option changes
  deliveryOptions.forEach((option) => {
    option.addEventListener("change", function () {
      deliveryOptionInput.value = this.value;
      console.log("Delivery option changed to:", this.value);
    });
  });

  // Handle quantity controls
  decreaseBtn.addEventListener("click", () => {
    if (quantityInput.value > 1) {
      quantityInput.value = parseInt(quantityInput.value) - 1;
    }
  });

  increaseBtn.addEventListener("click", () => {
    quantityInput.value = parseInt(quantityInput.value) + 1;
  });

  // Handle form submission
  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const productId = form.dataset.productId;
    const quantity = quantityInput.value;
    const deliveryOption = document.querySelector(
      'input[name="delivery"]:checked'
    ).value;

    console.log("Selected delivery option:", deliveryOption);

    // Redirect to purchase request page with parameters
    window.location.href = `${URLROOT}/shop/requestPurchase/${productId}?quantity=${quantity}&delivery=${deliveryOption}`;
  });
});

const thumbnails = document.querySelectorAll(".thumbnail");
const mainImage = document.getElementById("mainImage");

thumbnails.forEach((thumbnail) => {
  thumbnail.addEventListener("click", () => {
    // Remove active class from all thumbnails
    thumbnails.forEach((t) => t.classList.remove("active"));
    // Add active class to clicked thumbnail
    thumbnail.classList.add("active");
    // Update main image
    mainImage.src = thumbnail.src;
  });
});

// Delivery Options
const deliveryOptionsElements = document.querySelectorAll(".delivery-option");

deliveryOptionsElements.forEach((option) => {
  option.addEventListener("click", () => {
    // Remove selected class from all options
    deliveryOptionsElements.forEach((opt) => opt.classList.remove("selected"));
    // Add selected class to clicked option
    option.classList.add("selected");
    // Check the radio input
    option.querySelector('input[type="radio"]').checked = true;
  });
});
