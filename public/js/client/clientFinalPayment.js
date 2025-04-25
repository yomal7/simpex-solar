// clientFinalPayment.js

// Wait for the DOM to be fully loaded
document.addEventListener("DOMContentLoaded", function () {
  // Mobile sidebar toggle
  const menuBtn = document.querySelector(".bx-menu");
  const sidebar = document.querySelector(".sidebar");
  const content = document.querySelector(".content");

  if (menuBtn) {
    menuBtn.addEventListener("click", function () {
      sidebar.classList.toggle("active");
      content.classList.toggle("active");
    });
  }

  // Open first payment method by default
  const firstMethodContent = document.querySelector(".method-content");
  if (firstMethodContent) {
    firstMethodContent.style.display = "block";
    const firstHeader = firstMethodContent.previousElementSibling;
    if (firstHeader) {
      const icon = firstHeader.querySelector(".toggle-icon");
      if (icon) {
        icon.textContent = "-";
      }
    }
  }

  // Ensure back button has proper styling
  const backButton = document.querySelector(".back-buttons");
  if (backButton) {
    backButton.style.display = "flex";
    backButton.style.alignItems = "center";
    backButton.style.gap = "10px";
  }
});

// Bank account selection handling - Global functions
function updateBankDetails(selectedIndex, amount) {
  const bankDetails = document.getElementById("selectedBankDetails");
  if (!bankDetails || !bankAccounts || selectedIndex === "") return;

  const bank = bankAccounts[selectedIndex];
  bankDetails.innerHTML = `
        <div class="bank-details">
            <h5>${bank.bank_name} Details</h5>
            <p><strong>Account Name:</strong> ${bank.account_name}</p>
            <p><strong>Account Number:</strong> ${bank.account_number}</p>
            <p><strong>Branch:</strong> ${bank.branch} (${bank.branch_code})</p>
            <p><strong>Amount to Pay:</strong> Rs. ${parseFloat(
              amount
            ).toLocaleString("en-US", {
              minimumFractionDigits: 2,
              maximumFractionDigits: 2,
            })}</p>
        </div>
    `;
}

// Modal functions - keeping these as global functions
function openModal() {
  const modal = document.getElementById("confirmationModal");
  const overlay = document.getElementById("overlay");

  if (modal) modal.style.display = "block";
  if (overlay) overlay.style.display = "block";
}

function closeModal() {
  const modal = document.getElementById("confirmationModal");
  const overlay = document.getElementById("overlay");

  if (modal) modal.style.display = "none";
  if (overlay) overlay.style.display = "none";
}

// Cash payment functions - keeping these as global functions
function confirmCashPayment() {
  openModal();
}

function submitCashPayment() {
  const form = document.getElementById("cashPaymentForm");
  if (form) {
    form.submit();
    closeModal();
  }
}

function changePaymentMethod() {
  if (
    confirm(
      "Are you sure you want to change your payment method? This will cancel your current payment method selection."
    )
  ) {
    const urlRoot = typeof URLROOT !== "undefined" ? URLROOT : "";
    const preProjectId = document.querySelector(
      'input[name="pre_project_id"]'
    )?.value;

    if (urlRoot && preProjectId) {
      window.location.href = `${urlRoot}/client/cancelFinalPayment/${preProjectId}`;
    }
  }
}

// Add event listener for bank account selection
document.addEventListener("DOMContentLoaded", function () {
  const bankSelect = document.getElementById("bank_account");
  if (bankSelect) {
    bankSelect.addEventListener("change", function () {
      const selectedIndex = this.value;
      const amount = document.getElementById("amount")?.value || 0;
      updateBankDetails(selectedIndex, amount);
    });
  }
});
