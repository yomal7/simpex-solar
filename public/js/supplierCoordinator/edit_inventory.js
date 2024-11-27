document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".inventory-form");

  // Price validation
  const priceInput = document.getElementById("price");
  priceInput.addEventListener("input", function () {
    // Remove non-numeric characters except decimal point
    this.value = this.value.replace(/[^0-9.]/g, "");

    // Ensure only one decimal point
    const parts = this.value.split(".");
    if (parts.length > 2) {
      this.value = parts[0] + "." + parts.slice(1).join("");
    }

    // Limit to 2 decimal places
    if (parts[1] && parts[1].length > 2) {
      this.value = parseFloat(this.value).toFixed(2);
    }
  });

  // Quantity validation
  const quantityInput = document.getElementById("quantity");
  quantityInput.addEventListener("input", function () {
    // Remove non-numeric characters
    this.value = this.value.replace(/[^0-9]/g, "");

    // Prevent negative numbers
    if (this.value < 0) {
      this.value = 0;
    }
  });

  // Blog link validation
  const blogLinkInput = document.getElementById("blog_link");
  blogLinkInput.addEventListener("input", function () {
    // Optional: Real-time URL validation
    const urlPattern =
      /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
    if (urlPattern.test(this.value)) {
      this.setCustomValidity("");
    } else {
      this.setCustomValidity("Please enter a valid URL");
    }
  });

  // Form submission validation
  form.addEventListener("submit", function (event) {
    let isValid = true;
    const requiredFields = form.querySelectorAll("[required]");

    requiredFields.forEach((field) => {
      if (!field.value.trim()) {
        field.classList.add("is-invalid");
        isValid = false;
      } else {
        field.classList.remove("is-invalid");
      }
    });

    // Additional specific validations
    // Price validation
    if (priceInput.value) {
      const price = parseFloat(priceInput.value);
      if (isNaN(price) || price <= 0) {
        priceInput.classList.add("is-invalid");
        isValid = false;
      } else {
        priceInput.classList.remove("is-invalid");
      }
    }

    // Quantity validation
    if (quantityInput.value) {
      const quantity = parseInt(quantityInput.value);
      if (isNaN(quantity) || quantity < 0) {
        quantityInput.classList.add("is-invalid");
        isValid = false;
      } else {
        quantityInput.classList.remove("is-invalid");
      }
    }

    // Blog link validation
    if (blogLinkInput.value) {
      const urlPattern =
        /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
      if (!urlPattern.test(blogLinkInput.value)) {
        blogLinkInput.classList.add("is-invalid");
        isValid = false;
      } else {
        blogLinkInput.classList.remove("is-invalid");
      }
    }

    if (!isValid) {
      event.preventDefault();
    }
  });

  // Reset form functionality
  const resetBtn = document.querySelector(".reset-btn");
  resetBtn.addEventListener("click", function () {
    // Clear validation errors
    form.querySelectorAll(".is-invalid").forEach((el) => {
      el.classList.remove("is-invalid");
    });

    // Optional: Custom reset logic if needed
    priceInput.value = '<?php echo $data["price"]; ?>';
    quantityInput.value = '<?php echo $data["quantity"]; ?>';
    document.getElementById("description").value =
      '<?php echo $data["description"]; ?>';
    document.getElementById("blog_link").value =
      '<?php echo $data["blog_link"]; ?>';
    document.getElementById("supplier").value =
      '<?php echo $data["supplier_id"]; ?>';
  });
});
