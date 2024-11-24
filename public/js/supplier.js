// Dummy data for suppliers with more details
const suppliers = [
  {
    id: 1,
    image: "/api/placeholder/50/50",
    name: "Tech Solutions Inc.",
    email: "contact@techsolutions.com",
    phone: "+1 (555) 123-4567",
    address: "123 Tech Street, Silicon Valley, CA",
    category: "Electronics",
    contactPerson: "John Smith",
    website: "www.techsolutions.com",
    rating: 4.5,
    activeContracts: 3,
    lastDelivery: "2024-03-15",
    paymentTerms: "Net 30",
    supplierSince: "2020-01-15",
    description: "Leading provider of electronic components and solutions.",
  },
  {
    id: 2,
    image: "/api/placeholder/50/50",
    name: "Green Earth Products",
    email: "info@greenearth.com",
    phone: "+1 (555) 234-5678",
    address: "456 Eco Drive, Portland, OR",
    category: "Sustainable Goods",
    contactPerson: "Emma Wilson",
    website: "www.greenearth.com",
    rating: 4.8,
    activeContracts: 2,
    lastDelivery: "2024-03-20",
    paymentTerms: "Net 45",
    supplierSince: "2019-06-22",
    description: "Eco-friendly product supplier focused on sustainability.",
  },
  {
    id: 3,
    image: "/api/placeholder/50/50",
    name: "Global Logistics Co.",
    email: "support@globallogistics.com",
    phone: "+1 (555) 345-6789",
    address: "789 Transport Ave, Chicago, IL",
    category: "Transportation",
    contactPerson: "Michael Brown",
    website: "www.globallogistics.com",
    rating: 4.2,
    activeContracts: 5,
    lastDelivery: "2024-03-18",
    paymentTerms: "Net 60",
    supplierSince: "2021-03-10",
    description: "Comprehensive logistics and transportation solutions.",
  },
  // Add more dummy data to test pagination
  // ... (copies of above data with different IDs 4-10)
];

// Pagination settings
let currentPage = 1;
const rowsPerPage = 5;

// Function to calculate total pages
function getTotalPages() {
  return Math.ceil(suppliers.length / rowsPerPage);
}

// Function to get suppliers for current page
function getCurrentPageSuppliers() {
  const startIndex = (currentPage - 1) * rowsPerPage;
  const endIndex = startIndex + rowsPerPage;
  return suppliers.slice(startIndex, endIndex);
}

// Function to update pagination controls
function updatePaginationControls() {
  const paginationContainer = document.getElementById("paginationControls");
  const totalPages = getTotalPages();

  let paginationHTML = `
        <button onclick="changePage(${currentPage - 1})" ${
    currentPage === 1 ? "disabled" : ""
  }>
            Previous
        </button>
        <span>Page ${currentPage} of ${totalPages}</span>
        <button onclick="changePage(${currentPage + 1})" ${
    currentPage === totalPages ? "disabled" : ""
  }>
            Next
        </button>
    `;

  paginationContainer.innerHTML = paginationHTML;
}

// Function to change page
function changePage(newPage) {
  if (newPage >= 1 && newPage <= getTotalPages()) {
    currentPage = newPage;
    populateSupplierTable();
    updatePaginationControls();
  }
}

// Function to view supplier details
function viewSupplierDetails(supplierId) {
  // Store the supplier ID in session storage
  sessionStorage.setItem("selectedSupplierId", supplierId);
  // Redirect to the detailed view page
  window.location.href = `${URLROOT}/supplierCoordinator/supplierDetails`;
}

// Function to populate table with supplier data
function populateSupplierTable() {
  const tableBody = document.getElementById("tableBody");
  tableBody.innerHTML = "";

  getCurrentPageSuppliers().forEach((supplier) => {
    const row = document.createElement("tr");
    row.innerHTML = `
            <td>
                <img src="${supplier.image}" alt="${supplier.name}" class="supplier-profile-pic">
            </td>
            <td>${supplier.name}</td>
            <td>${supplier.email}</td>
            <td>
                <div class="supplier-actions">
                    <button class="supplier-view-btn" onclick="viewSupplierDetails(${supplier.id})">
                        View More
                    </button>
                    <button class="supplier-edit-btn" onclick="openEditPopup(${supplier.id})">
                        Edit
                    </button>
                    <button class="supplier-delete-btn" onclick="openDeletePopup(${supplier.id})">
                        Delete
                    </button>
                </div>
            </td>
        `;
    tableBody.appendChild(row);
  });

  updatePaginationControls();
}

// Function to handle form submission
function handleSubmit(event) {
  event.preventDefault();
  const formData = new FormData(event.target);
  const newSupplier = {
    id: suppliers.length + 1,
    image: "/api/placeholder/50/50",
    name: formData.get("firstName") + " " + formData.get("lastName"),
    email: formData.get("email"),
    phone: formData.get("phone"),
    category: formData.get("department"),
  };

  suppliers.push(newSupplier);
  populateSupplierTable();
  closePopup("userFormPopup");
  event.target.reset();
}

// Function to open popup
function openPopup(popupId) {
  document.getElementById(popupId).classList.add("open-popup");
  document.getElementById("overlay").style.visibility = "visible";
  document.getElementById("overlay").style.opacity = "1";
}

// Function to close popup
function closePopup(popupId) {
  document.getElementById(popupId).classList.remove("open-popup");
  document.getElementById("overlay").style.visibility = "hidden";
  document.getElementById("overlay").style.opacity = "0";
}

// Function to open edit popup
function openEditPopup(supplierId) {
  const supplier = suppliers.find((s) => s.id === supplierId);
  if (supplier) {
    document.getElementById("editName").value = supplier.name;
    document.getElementById("editEmail").value = supplier.email;
    openPopup("editPopup");
  }
}

// Function to confirm edit
function confirmEdit() {
  // Add edit functionality here
  closePopup("editPopup");
}

// Function to open delete popup
function openDeletePopup(supplierId) {
  // Store the supplier ID for deletion
  document.getElementById("deletePopup").dataset.supplierId = supplierId;
  openPopup("deletePopup");
}

// Function to confirm delete
function confirmDelete() {
  const supplierId = parseInt(
    document.getElementById("deletePopup").dataset.supplierId
  );
  const index = suppliers.findIndex((s) => s.id === supplierId);
  if (index > -1) {
    suppliers.splice(index, 1);
    populateSupplierTable();
  }
  closePopup("deletePopup");
}

// Initialize the table when the page loads
document.addEventListener("DOMContentLoaded", () => {
  populateSupplierTable();
});

// Toggle sidebar functionality
function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  sidebar.classList.toggle("active");
}
