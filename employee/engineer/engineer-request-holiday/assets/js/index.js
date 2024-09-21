document.addEventListener("DOMContentLoaded", function() {
    const data = [
        {
            leaveType: 'Type1',
            from: '2024/06/20',
            to: '2024/06/22',
            numberOfDays: '03',
            reason: 'Reason1',
            status: 'Approved'
        },
        {
            leaveType: 'Type4',
            from: '2024/07/05',
            to: '2024/07/06',
            numberOfDays: '02',
            reason: 'Reason2',
            status: 'Not Approved'
        },
        {
            leaveType: 'Type1',
            from: '2024/07/30',
            to: '2024/08/02',
            numberOfDays: '04',
            reason: 'Reason3',
            status: 'Approved'
        },
        {
            leaveType: 'Type2',
            from: '2024/08/20',
            to: '2024/08/20',
            numberOfDays: '01',
            reason: 'Reason4',
            status: 'Pending'
        }
    ];
  
    const tableBody = document.querySelector(".technician-table tbody");
  
    data.forEach(item => {
        const row = document.createElement("tr");

        let statusClass = "";
        switch (item.status) {
            case "Approved":
            statusClass = "approved";
            break;
            case "Not Approved":
            statusClass = "not-approved";
            break;
            case "Pending":
            statusClass = "pending";
            break;
            default:
            statusClass = "";
        }
  
        row.innerHTML = `
            <td>${item.leaveType}</td>
            <td>${item.from}</td>
            <td>${item.to}</td>
            <td>${item.numberOfDays}</td>
            <td>${item.reason}</td>
            <td><span class="highlight ${statusClass}">${item.status}</span></td>
      `;
  
      tableBody.appendChild(row);
    });

  });

  
  
