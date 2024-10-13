document.addEventListener("DOMContentLoaded", function() {
    const data = [
        {
            month: 'January',
            amount: '150,000',
            details: 'Payment1',
            status: 'Paid'
        },
        {
            month: 'February',
            amount: '150,000',
            details: 'Payment2',
            status: 'Paid'
        },
        {
            month: 'March',
            amount: '150,000',
            details: 'Payment3',
            status: 'Paid'
        },
        {
            month: 'April',
            amount: '160,000',
            details: 'Payment4',
            status: 'Paid'
        },
        {
            month: 'May',
            amount: '170,000',
            details: 'Payment5',
            status: 'Paid'
        },
        {
            month: 'June',
            amount: '180,000',
            details: 'Payment6',
            status: 'Paid'
        },
        {
            month: 'July',
            amount: '200,000',
            details: 'Payment7',
            status: 'Paid'
        }
    ];
  
    const tableBody = document.querySelector(".engineer-table tbody");
  
    data.forEach(item => {
        const row = document.createElement("tr");
  
        let statusClass = "";
        switch (item.status) {
            case "Paid":
            statusClass = "paid";
            break;
            case "Not Paid":
            statusClass = "not-paid";
            break;
            default:
            statusClass = "";
      }

        row.innerHTML = `
            <td>${item.month}</td>
            <td>${item.amount}</td>
            <td>${item.details}</td>
            <td><span class="highlight ${statusClass}">${item.status}</span></td>
        `;
    
        tableBody.appendChild(row);
    });

  });

  
  
