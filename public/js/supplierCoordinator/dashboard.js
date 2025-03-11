function showRequestOrders() {
  loadOrders("pending");
  highlightCard("request-orders");
}

function showProcessingOrders() {
  loadOrders("processing");
  highlightCard("processing-orders");
}

function showActiveOrders() {
  loadOrders("active");
  highlightCard("active-orders");
}

function loadOrders(type) {
  fetch(`${URLROOT}/supplierCoordinator/getOrders/${type}`)
    .then((response) => response.json())
    .then((data) => {
      updateOrdersTable(data.orders, data.show_status);
    });
}

function updateOrdersTable(orders, showStatus) {
  const tbody = document.getElementById("ordersTableBody");
  tbody.innerHTML = "";

  orders.forEach((order) => {
    const row = document.createElement("tr");
    const orderId = `ORD-${new Date(order.created_at).getFullYear()}-${String(
      order.id
    ).padStart(3, "0")}`;

    let total =
      order.status === "pending"
        ? order.product_price * order.quantity
        : calculateTotal(order);

    row.innerHTML = `
            <td>${orderId}</td>
            <td>${order.product_name}</td>
            <td class="price">Rs. ${total.toFixed(2)}</td>
            <td>${new Date(order.created_at).toLocaleDateString()}</td>
            ${
              showStatus
                ? `<td><span class="status-badge status-${order.status.replace(
                    " ",
                    "-"
                  )}">${order.status}</span></td>`
                : ""
            }
            <td>
                <button class="btn-view" onclick="viewOrder(${order.id})">
                    <i class="material-icons-sharp">visibility</i>
                </button>
            </td>
        `;
    tbody.appendChild(row);
  });
}

function calculateTotal(order) {
  let total = order.price * order.quantity;
  if (order.delivery_fee) total += parseFloat(order.delivery_fee);
  if (order.discount) total -= parseFloat(order.discount);
  return total;
}

function highlightCard(cardId) {
  document.querySelectorAll(".card").forEach((card) => {
    card.classList.remove("active");
  });
  document.getElementById(cardId).classList.add("active");
}

// Load pending orders by default
document.addEventListener("DOMContentLoaded", function () {
  showRequestOrders();
});
