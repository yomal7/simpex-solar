document.getElementById("dateFilter").addEventListener("change", function (e) {
  // Add your date filter logic here
  const dateRange = e.target.value;
  const rows = document.querySelectorAll(".orders-table tbody tr");

  rows.forEach((row) => {
    const dateCell = row.querySelector("td:nth-child(2)").textContent;
    const orderDate = new Date(dateCell);
    const today = new Date();
    let show = true;

    switch (dateRange) {
      case "today":
        show = orderDate.toDateString() === today.toDateString();
        break;
      case "week":
        const weekAgo = new Date();
        weekAgo.setDate(today.getDate() - 7);
        show = orderDate >= weekAgo;
        break;
      case "month":
        show =
          orderDate.getMonth() === today.getMonth() &&
          orderDate.getFullYear() === today.getFullYear();
        break;
      case "year":
        show = orderDate.getFullYear() === today.getFullYear();
        break;
      default:
        show = true;
    }

    row.style.display = show ? "" : "none";
  });
});

// Search functionality
document.getElementById("orderSearch").addEventListener("input", function (e) {
  const searchTerm = e.target.value.toLowerCase();
  const rows = document.querySelectorAll(".orders-table tbody tr");

  rows.forEach((row) => {
    const orderId = row.querySelector(".order-id").textContent.toLowerCase();
    const products = row
      .querySelector(".order-products")
      .textContent.toLowerCase();
    const matches =
      orderId.includes(searchTerm) || products.includes(searchTerm);
    row.style.display = matches ? "" : "none";
  });
});

// Status filter functionality
document
  .getElementById("statusFilter")
  .addEventListener("change", function (e) {
    const selectedStatus = e.target.value.toLowerCase();
    const rows = document.querySelectorAll(".orders-table tbody tr");

    rows.forEach((row) => {
      const statusCell = row
        .querySelector(".status-badge")
        .textContent.toLowerCase();
      const show = selectedStatus === "" || statusCell.includes(selectedStatus);
      row.style.display = show ? "" : "none";
    });
  });

// Initialize tooltips for action buttons
const actionButtons = document.querySelectorAll(".action-button");
actionButtons.forEach((button) => {
  button.setAttribute("title", button.textContent.trim());
});

// Handle pagination
const pageButtons = document.querySelectorAll(".page-button");
const itemsPerPage = 10;
let currentPage = 1;

function updatePagination() {
  const rows = document.querySelectorAll(".orders-table tbody tr");
  const totalPages = Math.ceil(rows.length / itemsPerPage);

  // Show/hide rows based on current page
  rows.forEach((row, index) => {
    const shouldShow =
      index >= (currentPage - 1) * itemsPerPage &&
      index < currentPage * itemsPerPage;
    row.style.display = shouldShow ? "" : "none";
  });

  // Update pagination buttons
  pageButtons.forEach((button) => {
    if (button.textContent === currentPage.toString()) {
      button.classList.add("active");
    } else {
      button.classList.remove("active");
    }
  });
}

pageButtons.forEach((button) => {
  button.addEventListener("click", function () {
    if (this.textContent === "Previous") {
      if (currentPage > 1) currentPage--;
    } else if (this.textContent === "Next") {
      const totalRows = document.querySelectorAll(
        ".orders-table tbody tr"
      ).length;
      const totalPages = Math.ceil(totalRows / itemsPerPage);
      if (currentPage < totalPages) currentPage++;
    } else {
      currentPage = parseInt(this.textContent);
    }
    updatePagination();
  });
});

// Initialize empty state if no orders
function checkEmptyState() {
  const tbody = document.querySelector(".orders-table tbody");
  const rows = tbody.querySelectorAll("tr");
  const visibleRows = Array.from(rows).filter(
    (row) => row.style.display !== "none"
  );

  if (visibleRows.length === 0) {
    const emptyState = document.createElement("tr");
    emptyState.innerHTML = `
            <td colspan="6" class="empty-state">
                <i class="fas fa-box-open"></i>
                <p>No orders found</p>
            </td>
        `;
    tbody.appendChild(emptyState);
  } else {
    const existingEmptyState =
      tbody.querySelector(".empty-state")?.parentElement;
    if (existingEmptyState) {
      existingEmptyState.remove();
    }
  }
}

// Event listeners for order actions
function processPayment(orderId) {
  // Redirect to payment gateway
  window.location.href = `/payment/${orderId}`;
}

function downloadQuotation(orderId) {
  // Create a request to generate and download quotation
  fetch(`/api/orders/${orderId}/quotation`)
    .then((response) => response.blob())
    .then((blob) => {
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement("a");
      a.href = url;
      a.download = `quotation-${orderId}.pdf`;
      document.body.appendChild(a);
      a.click();
      window.URL.revokeObjectURL(url);
    })
    .catch((error) => {
      console.error("Error downloading quotation:", error);
      alert("Failed to download quotation. Please try again later.");
    });
}

function downloadInvoice(orderId) {
  // Create a request to generate and download invoice
  fetch(`/api/orders/${orderId}/invoice`)
    .then((response) => response.blob())
    .then((blob) => {
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement("a");
      a.href = url;
      a.download = `invoice-${orderId}.pdf`;
      document.body.appendChild(a);
      a.click();
      window.URL.revokeObjectURL(url);
    })
    .catch((error) => {
      console.error("Error downloading invoice:", error);
      alert("Failed to download invoice. Please try again later.");
    });
}

// Initialize the page
document.addEventListener("DOMContentLoaded", function () {
  updatePagination();
  checkEmptyState();
});

// Add event listeners for all filters to check empty state
["orderSearch", "statusFilter", "dateFilter"].forEach((filterId) => {
  document.getElementById(filterId).addEventListener("change", checkEmptyState);
  document.getElementById(filterId).addEventListener("input", checkEmptyState);
});

function confirmOrder(orderId) {
  window.location.href = `${URLROOT}/client/confirmOrder/${orderId}`;
}
