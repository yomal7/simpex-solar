document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("supplierForm");

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(form);

    fetch(form.action, {
      method: "POST",
      body: formData,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Success - show message and redirect
          flash("supplier_message", data.message);
          window.location.href = "<?= URLROOT ?>/suppliers";
        } else {
          // Handle validation errors
          if (data.errors) {
            Object.keys(data.errors).forEach((field) => {
              const errorElement = document.querySelector(
                `#${field} + .invalid-feedback`
              );
              const inputElement = document.getElementById(field);

              if (errorElement) {
                errorElement.textContent = data.errors[field];
                inputElement.classList.add("is-invalid");
              }
            });
          }

          // Show error message
          flash("supplier_message", data.message);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        flash("supplier_message", "An unexpected error occurred");
      });
  });
});

// Utility function for flash messages (you'll need to implement this)
function flash(type, message) {
  // Implement flash message display logic
  const flashContainer = document.querySelector(".flash-message-container");
  if (flashContainer) {
    flashContainer.innerHTML = `
          <div class="alert alert-${
            type === "supplier_message" ? "success" : "danger"
          }">
              ${message}
          </div>
      `;
  }
}
