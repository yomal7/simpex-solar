// Add new item row
document.querySelector('.add-item-btn').addEventListener('click', function() {
    const tbody = document.querySelector('.items-table tbody');
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td><input type="text" placeholder="Item description"></td>
        <td><input type="number" placeholder="Qty"></td>
        <td><input type="number" placeholder="Price"></td>
        <td>$0.00</td>
    `;
    tbody.appendChild(newRow);
});

// Auto-calculate totals would be implemented here
function calculateTotals() {
    // Implementation for calculating totals
}

// Preview generation would be implemented here
function generatePreview() {
    // Implementation for generating PDF preview
}