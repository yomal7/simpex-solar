document.addEventListener("DOMContentLoaded", function () {
  const navButtons = document.querySelectorAll(".nav-btn");

  navButtons.forEach((button) => {
    button.addEventListener("click", function () {
      // Remove active class from all buttons
      navButtons.forEach((btn) => btn.classList.remove("active"));

      // Add active class to clicked button
      this.classList.add("active");

      // Get category from data attribute
      const category = this.dataset.category;

      // Update URL without page reload
      const newUrl = updateQueryStringParameter(
        window.location.href,
        "category",
        category
      );
      window.history.pushState({ path: newUrl }, "", newUrl);

      // Fetch and update products
      fetchProducts(category);
    });
  });
});

function updateQueryStringParameter(uri, key, value) {
  const re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
  const separator = uri.indexOf("?") !== -1 ? "&" : "?";

  if (uri.match(re)) {
    return uri.replace(re, "$1" + key + "=" + value + "$2");
  } else {
    return uri + separator + key + "=" + value;
  }
}

function fetchProducts(category) {
  fetch(`index.php?action=list&category=${category}`)
    .then((response) => response.text())
    .then((html) => {
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, "text/html");
      const newProducts = doc.querySelector("#packagesContainer").innerHTML;
      document.querySelector("#packagesContainer").innerHTML = newProducts;

      // Add animation class to new cards
      const cards = document.querySelectorAll(".package-card");
      cards.forEach((card) => {
        card.style.opacity = "0";
        card.style.transform = "translateY(20px)";
        requestAnimationFrame(() => {
          card.style.transition = "all 0.6s ease";
          card.style.opacity = "1";
          card.style.transform = "translateY(0)";
        });
      });
    });
}
