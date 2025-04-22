document.addEventListener("DOMContentLoaded", function () {
  console.log("DOM loaded");

  // Cache DOM elements
  const projectCards = document.querySelectorAll(".project-card");
  const projectsGrid = document.getElementById("projectsGrid");
  const searchInput = document.querySelector(".search-input");
  const sortDropdown = document.querySelector(".sort-dropdown");
  const filterButtons = document.querySelectorAll(".filter-btn");
  const statCards = document.querySelectorAll(".stat-card");

  // Log for debugging
  console.log("Stat cards found:", statCards.length);
  console.log("Filter buttons found:", filterButtons.length);

  // Filter functionality
  function filterProjects(phase) {
    console.log("Filtering projects for phase:", phase);
    projectCards.forEach((card) => {
      if (phase === "all" || card.dataset.phase === phase) {
        card.style.display = "block";
      } else {
        card.style.display = "none";
      }
    });
  }

  // Set active filter button
  filterButtons.forEach((button) => {
    button.addEventListener("click", function () {
      console.log("Filter button clicked:", this.dataset.phase);

      // Remove active class from all buttons
      filterButtons.forEach((btn) => btn.classList.remove("active"));

      // Add active class to clicked button
      this.classList.add("active");

      // Filter projects
      const phase = this.dataset.phase;
      filterProjects(phase);
    });
  });

  // Stat card click handler
  statCards.forEach((card) => {
    card.addEventListener("click", function () {
      const phase = this.dataset.phase;
      console.log("Stat card clicked:", phase);

      // Update active filter button
      filterButtons.forEach((btn) => {
        if (btn.dataset.phase === phase) {
          btn.classList.add("active");
        } else {
          btn.classList.remove("active");
        }
      });

      // Filter projects
      filterProjects(phase);
    });
  });

  // Search functionality
  searchInput.addEventListener("input", function () {
    const searchTerm = this.value.toLowerCase().trim();

    projectCards.forEach((card) => {
      const customerName = card
        .querySelector(".customer-name")
        .textContent.toLowerCase();
      const projectId = card
        .querySelector(".project-id")
        .textContent.toLowerCase();
      const location = card
        .querySelector(".project-location")
        .textContent.toLowerCase();

      if (
        customerName.includes(searchTerm) ||
        projectId.includes(searchTerm) ||
        location.includes(searchTerm)
      ) {
        card.style.display = "block";
      } else {
        card.style.display = "none";
      }
    });
  });

  // Sort functionality
  sortDropdown.addEventListener("change", function () {
    const sortValue = this.value;
    const cards = Array.from(projectCards);

    cards.sort((a, b) => {
      const dateA = new Date(extractDate(a));
      const dateB = new Date(extractDate(b));

      return sortValue === "newest" ? dateB - dateA : dateA - dateB;
    });

    // Clear and repopulate the grid
    projectsGrid.innerHTML = "";
    cards.forEach((card) => projectsGrid.appendChild(card));
  });

  // Helper function to extract date from card
  function extractDate(card) {
    const dateText = card.querySelector(".project-date").textContent;
    return dateText.replace("📅 ", "");
  }
});

// Toggle sidebar function
function toggleSidebar() {
  document.getElementById("sidebar").classList.toggle("active");
  document.getElementById("overlay").classList.toggle("active");
}
