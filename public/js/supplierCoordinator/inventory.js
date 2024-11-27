// Global variable to store the item ID to be deleted
let itemToDeleteId = null;

/**
 * Opens the delete modal and sets the item ID for deletion.
 * @param {number} itemId - The ID of the item to delete.
 */
function openDeleteModal(itemId) {
  // Set the global item ID
  itemToDeleteId = itemId;

  // Show the delete modal
  const deleteModal = document.getElementById("deleteModal");
  deleteModal.style.display = "block";

  // Add a small delay to trigger the fade-in effect
  setTimeout(() => {
    deleteModal.classList.add("show");
  }, 10);
}

/**
 * Closes the delete modal and resets the item ID.
 */
function closeDeleteModal() {
  const deleteModal = document.getElementById("deleteModal");
  deleteModal.classList.remove("show");

  // Use a slight delay to allow fade-out animation before hiding
  setTimeout(() => {
    deleteModal.style.display = "none";
  }, 300);

  // Reset the global item ID
  itemToDeleteId = null;
}

/**
 * Confirms deletion of an item by sending a request to the server.
 */
function confirmDelete() {
  if (!itemToDeleteId) {
    showToast("Error: No item selected for deletion", "error");
    return;
  }

  // Make a POST request to delete the item
  fetch(`${URLROOT}/supplierCoordinator/deleteItem`, {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `id=${encodeURIComponent(itemToDeleteId)}`,
  })
    .then((response) => {
      // Log response details for debugging
      console.log("Response Status:", response.status);
      console.log("Response Headers:", response.headers);

      // Ensure response is JSON
      return response.json();
    })
    .then((data) => {
      if (data.success) {
        // Remove the table row for the deleted item
        const rowToRemove = document.querySelector(
          `tr[data-item-id="${itemToDeleteId}"]`
        );
        if (rowToRemove) {
          rowToRemove.remove();
        }

        // Close delete modal
        closeDeleteModal();

        // Show success message
        showToast("Item deleted successfully", "success");
      } else {
        // Show error with detailed message
        showToast(
          data.message || data.details || "Failed to delete item",
          "error"
        );
      }
    })
    .catch((error) => {
      console.error("Deletion Error:", error);
      showToast(`Deletion error: ${error.message}`, "error");
    });
}

/**
 * Displays a toast notification.
 * @param {string} message - The message to display.
 * @param {string} [type="success"] - The type of the toast (e.g., "success", "error").
 */
function showToast(message, type = "success") {
  // Create toast element if it doesn't exist
  let toast = document.querySelector(".toast");
  if (!toast) {
    toast = document.createElement("div");
    toast.classList.add("toast");
    document.body.appendChild(toast);
  }

  // Set toast content and type
  toast.textContent = message;
  toast.className = `toast toast-${type} show`;

  // Remove toast after 3 seconds
  setTimeout(() => {
    toast.classList.remove("show");
  }, 3000);
}

// Close modal when clicking outside or on the close button
document.addEventListener("DOMContentLoaded", () => {
  const deleteModal = document.getElementById("deleteModal");
  const closeBtn = deleteModal.querySelector(".close");

  // Close when clicking outside the modal
  deleteModal.addEventListener("click", (e) => {
    if (e.target === deleteModal) {
      closeDeleteModal();
    }
  });

  // Close when clicking the close button
  closeBtn.addEventListener("click", closeDeleteModal);
});
