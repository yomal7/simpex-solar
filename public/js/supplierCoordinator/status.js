document.addEventListener("DOMContentLoaded", () => {
  // Get all rows with the class "quantity"
  const quantityCells = document.querySelectorAll(".quantity");

  // Iterate through each quantity cell
  quantityCells.forEach((cell) => {
    // Get the quantity from the data attribute
    const quantity = parseInt(cell.dataset.quantity, 10);

    // Determine the status based on quantity
    let status;
    if (quantity === 0) {
      status = "Out of Stock";
    } else if (quantity < 2) {
      status = "Low Stock";
    } else {
      status = "In Stock";
    }

    // Find the corresponding status cell and update its content
    const statusCell = cell.parentElement.querySelector(".status");
    if (statusCell) {
      let className =
        status === "Out of Stock"
          ? "out-of-stock"
          : status === "Low Stock"
          ? "low-stock"
          : "in-stock";
      statusCell.innerHTML = `<span class="${className}">${status}</span>`;
      // Optional: Add a class for styling based on the status
      // statusCell.classList.add(
      //     status === "Out of Stock" ? "out-of-stock" :
      //     status === "Low Stock" ? "low-stock" :
      //     "in-stock"
      // );
    }
  });
});
