document.addEventListener("DOMContentLoaded", function () {
  const sideLinks = document.querySelectorAll(
    ".sidebar .side-menu li a:not(.logout)"
  );
  if (sideLinks.length) {
    sideLinks.forEach((item) => {
      const li = item.parentElement;
      item.addEventListener("click", () => {
        sideLinks.forEach((i) => {
          i.parentElement.classList.remove("active");
        });
        li.classList.add("active");
      });
    });
  }
  calculateTotal();
});

// Move functions outside of DOMContentLoaded
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

  showConfirmationModal(paymentMethod.value, orderId);
}

function downloadQuotation(orderId) {
  window.location.href = `${URLROOT}/client/downloadQuotation/${orderId}`;
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

function showConfirmationModal(paymentMethod, orderId) {
  const modal = document.getElementById("confirmModal");
  const modalContent = document.querySelector("#confirmModal .modal-body");
  if (!modal || !modalContent) {
    console.error("Modal elements not found");
    return;
  }

  modalContent.innerHTML = `Confirm payment method: ${paymentMethod}`;
  modal.style.display = "block";

  const confirmBtn = document.getElementById("confirmPaymentBtn");
  if (confirmBtn) {
    confirmBtn.onclick = () => processPayment(paymentMethod, orderId);
  }
}

function processPayment(paymentMethod, orderId) {
  fetch(`${URLROOT}/client/processOrder`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      orderId: orderId,
      paymentMethod: paymentMethod,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        if (data.redirect === "shop") {
          window.location.href = `${URLROOT}/client/shop`;
        } else {
          window.location.href = `${URLROOT}/client/${data.redirect}`;
        }
      } else {
        throw new Error(data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert(error.message || "Something went wrong. Please try again.");
    });
}
