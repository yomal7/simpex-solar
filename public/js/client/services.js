// Character counter for description field
const descriptionField = document.getElementById("description");
const charCount = document.getElementById("charCount");

if (descriptionField) {
  // Update count on load
  charCount.textContent = descriptionField.value.length;

  descriptionField.addEventListener("input", function () {
    const currentLength = this.value.length;
    charCount.textContent = currentLength;

    if (currentLength > 500) {
      charCount.classList.add("text-danger");
      this.value = this.value.substring(0, 500);
      charCount.textContent = 500;
    } else {
      charCount.classList.remove("text-danger");
    }
  });
}
