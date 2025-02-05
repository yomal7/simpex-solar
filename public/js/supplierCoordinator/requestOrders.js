document.addEventListener("DOMContentLoaded", function () {
  calculateTotal();

  // Add event listeners
  document.getElementById("price").addEventListener("input", calculateTotal);
  document.getElementById("discount").addEventListener("input", calculateTotal);

  const deliveryFeeInput = document.getElementById("deliveryFee");
  if (deliveryFeeInput) {
    deliveryFeeInput.addEventListener("input", calculateTotal);
  }
});

function calculateTotal() {
  const price = parseFloat(document.getElementById("price").value) || 0;
  const quantity = parseInt(document.getElementById("quantity").value) || 0;
  const deliveryFeeInput = document.getElementById("deliveryFee");
  const deliveryFee = deliveryFeeInput
    ? parseFloat(deliveryFeeInput.value) || 0
    : 0;
  const discount = parseFloat(document.getElementById("discount").value) || 0;

  const total = price * quantity + deliveryFee - discount;
  document.getElementById("totalAmount").textContent = `Rs. ${total.toFixed(
    2
  )}`;
}

function showModal(modalId) {
  document.getElementById(modalId).style.display = "block";
}

function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none";
}

function confirmApprove() {
  showModal("approveModal");
}

function confirmReject() {
  showModal("rejectModal");
}

function approveOrder() {
  const deliveryFeeInput = document.getElementById("deliveryFee");
  const formData = {
    orderId: document.getElementById("orderId").value,
    price: document.getElementById("price").value,
    delivery_fee: deliveryFeeInput ? parseFloat(deliveryFeeInput.value) : 0,
    discount: document.getElementById("discount").value || 0,
  };

  fetch(`${URLROOT}/supplierCoordinator/approveOrder`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(formData),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        window.location.href = `${URLROOT}/supplierCoordinator/dashboard`;
      } else {
        alert(data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Something went wrong");
    });
}

function rejectOrder() {
  const orderId = document.getElementById("orderId").value;

  fetch(`${URLROOT}/supplierCoordinator/rejectOrder`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      orderId: orderId,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        window.location.href = `${URLROOT}/supplierCoordinator/dashboard`;
      } else {
        alert(data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Something went wrong");
    });
}
