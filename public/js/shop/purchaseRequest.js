document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("purchaseRequestForm");

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    // Basic form validation
    const requiredFields = this.querySelectorAll("[required]");
    let isValid = true;

    requiredFields.forEach((field) => {
      if (!field.value.trim()) {
        isValid = false;
        field.classList.add("invalid");
      } else {
        field.classList.remove("invalid");
      }
    });

    if (!isValid) {
      alert("Please fill in all required fields");
      return;
    }

    // Check terms agreement
    const termsCheckbox = document.getElementById("termsAgree");
    if (!termsCheckbox.checked) {
      alert("Please agree to the terms and conditions");
      return;
    }

    // Debug form submission
    console.log("Form submitted");
    console.log("Form data:", new FormData(this));

    // Submit the form
    this.submit();
  });
});

// Phone number validation
const phoneInputs = document.querySelectorAll('input[type="tel"]');
phoneInputs.forEach((input) => {
  input.addEventListener("input", function (e) {
    // Remove any non-numeric characters
    this.value = this.value.replace(/[^\d+\-\s()]/g, "");
  });
});

// Date validation for installation date
const installationDateInput = document.getElementById("preferredInstallation");
const today = new Date().toISOString().split("T")[0];
installationDateInput.setAttribute("min", today);
