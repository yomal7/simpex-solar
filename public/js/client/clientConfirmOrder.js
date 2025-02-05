function confirmOrder(orderId) {
  const paymentMethod = document.querySelector(
    'input[name="payment_method"]:checked'
  );
  const termsAgreed = document.getElementById("terms").checked;

  if (!paymentMethod) {
    alert("Please select a payment method");
    return;
  }

  if (!termsAgreed) {
    alert("Please agree to the terms and conditions");
    return;
  }

  // Submit order confirmation
  fetch(`${URLROOT}/client/processOrder`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      orderId: orderId,
      paymentMethod: paymentMethod.value,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        window.location.href = `${URLROOT}/client/shop`;
      }
    });
}

function downloadQuotation(orderId) {
  window.location.href = `${URLROOT}/client/downloadQuotation/${orderId}`;
}

function cancelOrder(orderId) {
  if (confirm("Are you sure you want to cancel this order?")) {
    fetch(`${URLROOT}/client/cancelOrder/${orderId}`, {
      method: "POST",
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          window.location.href = `${URLROOT}/client/shop`;
        }
      });
  }
}

function cancelOrder(orderId) {
  document.getElementById("cancelModal").style.display = "block";
}

function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none";
}

function confirmCancel(orderId) {
  fetch(`${URLROOT}/client/cancelOrder/${orderId}`, {
    method: "POST",
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        window.location.href = `${URLROOT}/client/shop`;
      } else {
        alert(data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Something went wrong");
    });
}
