let deleteItemId = null;

// public/js/supplierCoordinator/shop.js
function editProduct(id) {
  window.location.href = URLROOT + "/supplierCoordinator/editShopProduct/" + id;
}

function deleteProduct(id) {
  deleteItemId = id;
  document.getElementById("deleteModal").classList.add("show");
}

function closeDeleteModal() {
  document.getElementById("deleteModal").classList.remove("show");
  deleteItemId = null;
}

function confirmDelete() {
  if (deleteItemId) {
    const form = document.createElement("form");
    form.method = "POST";
    form.action =
      URLROOT + "/supplierCoordinator/deleteShopProduct/" + deleteItemId;
    document.body.appendChild(form);
    form.submit();
  }
}

// Close modal when clicking outside
window.onclick = function (event) {
  const modal = document.getElementById("deleteModal");
  if (event.target == modal) {
    closeDeleteModal();
  }
};

// shop.js
document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("productSearch");
  const categoryFilter = document.getElementById("categoryFilter");
  const clearSearchIcon = document.getElementById("clearSearchIcon");
  const table = document.getElementById("productsTable");

  function filterTable() {
    const searchTerm = searchInput.value.toLowerCase();
    const categoryValue = categoryFilter.value;
    const rows = table.getElementsByTagName("tr");
    let visibleCount = 0;

    // Show/hide clear button
    clearSearchIcon.style.display = searchTerm ? "block" : "none";

    // Skip header row (i=1)
    for (let i = 1; i < rows.length; i++) {
      const row = rows[i];
      const cells = row.getElementsByTagName("td");
      const productName = cells[0].textContent.toLowerCase();
      const category = cells[3].textContent.trim();

      const matchesSearch = productName.includes(searchTerm);
      const matchesCategory = !categoryValue || category === categoryValue;

      if (matchesSearch && matchesCategory) {
        row.style.display = "";
        visibleCount++;
      } else {
        row.style.display = "none";
      }
    }

    // Show/hide no results message
    updateNoResults(visibleCount === 0);
  }

  function updateNoResults(show) {
    let message = document.getElementById("noResultsMessage");
    if (show) {
      if (!message) {
        message = document.createElement("div");
        message.id = "noResultsMessage";
        message.className = "no-results";
        message.textContent = "No products found";
        table.parentNode.insertBefore(message, table.nextSibling);
      }
      message.style.display = "block";
    } else if (message) {
      message.style.display = "none";
    }
  }

  function clearSearch() {
    searchInput.value = "";
    categoryFilter.value = "";
    filterTable();
    searchInput.focus();
  }

  // Event listeners
  searchInput.addEventListener("input", filterTable);
  categoryFilter.addEventListener("change", filterTable);
  clearSearchIcon.addEventListener("click", clearSearch);
});
