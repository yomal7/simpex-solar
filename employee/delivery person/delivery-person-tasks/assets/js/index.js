document.addEventListener("DOMContentLoaded", function() {
    const data = [
        {
            deliveryID: 'D001',
            date: '2024/08/31',
            confirmation: 'Confirmed'
        },
        {
            deliveryID: 'D002',
            date: '2024/09/01',
            confirmation: 'Not Confirmed'
        },
        {
            deliveryID: 'D003',
            date: '2024/09/02',
            confirmation: 'Not Confirmed'
        },
        {
            deliveryID: 'D004',
            date: '2024/09/03',
            confirmation: 'Not Confirmed'
        }
    ];
  
    const tableBody = document.querySelector(".delivery-person-table tbody");
  
    data.forEach(item => {
        const row = document.createElement("tr");
  
        let confirmationClass = "";
        switch (item.confirmation) {
            case "Confirmed":
            confirmationClass = "confirmed";
            break;
            case "Not Confirmed":
            confirmationClass = "not-confirmed";
            break;
            default:
            confirmationClass = "";
      }

        row.innerHTML = `
            <td>${item.deliveryID}</td>
            <td>${item.date}</td>
            <td class="primary"><button class="view-button" role="button">View</button></td>
            <td><span class="highlight ${confirmationClass}">${item.confirmation}</span></td>
            <td class="primary"><button class="addComment-button" role="button">Add Comment</button></td>
        `;
    
        tableBody.appendChild(row);
    });

  });

  
  
