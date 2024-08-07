/*const customers = [
    {
        image: 'assets/images/userimg.jpg',
        name: 'Anura Kumara',
        status: 'Feasibility Test',
        availability: 'Low Stock'
    },
    {
        image: 'assets/images/userimg.jpg',
        name: 'Sajith Premadasa',
        status: 'Document Submission',
        availability: 'In Stock'
    },
    {
        image: 'assets/images/userimg.jpg',
        name: 'Sanath Nishantha',
        status: 'System Verification',
        availability: 'Out of Stock'
    },
    {
        image: 'assets/images/userimg.jpg',
        name: 'Ranil Wickramasighe',
        status: 'System Verification',
        availability: 'Low Stock'
    }
];

customers.forEach(customer => {
    const tr = document.createElement('tr');
    const availabilityClass = customer.availability.toLowerCase().replace(' ', '-');
    const trContent = `
        <td><img src="${customer.image}" alt="${customer.name}" class="profile-image"></td>
        <td>${customer.name}</td>
        <td>${customer.status}</td>
        <td class="${availabilityClass}">${customer.availability}</td>
        <td class="primary"><button class="view-button" role="button">View</button></td>
        <td><span class="material-symbols-outlined">edit</span></td>
        <td><span class="material-symbols-outlined">delete</span></td>
    `;
    tr.innerHTML = trContent;
    document.querySelector('table tbody').appendChild(tr);
});
*/

document.addEventListener("DOMContentLoaded", function() {
    const data = [
        {
            image: 'assets/images/userimg.jpg',
            name: 'Oshada Yomal',
            status: 'Feasibility Test',
            availability: 'Low Stock'
        },
        {
            image: 'assets/images/userimg.jpg',
            name: 'Binula Dimantha',
            status: 'Document Submission',
            availability: 'In Stock'
        },
        {
            image: 'assets/images/userimg.jpg',
            name: 'Thisum Dinujaya',
            status: 'System Verification',
            availability: 'Out of Stock'
        },
        {
            image: 'assets/images/userimg.jpg',
            name: 'Peshani Rasangika',
            status: 'System Verification',
            availability: 'Low Stock'
        }
    ];
  
    const tableBody = document.querySelector(".customer-table tbody");
  
    data.forEach(item => {
      const row = document.createElement("tr");
  
      let availabilityClass = "";
      switch (item.availability) {
        case "In Stock":
          availabilityClass = "in-stock";
          break;
        case "Out of Stock":
          availabilityClass = "out-of-stock";
          break;
        case "Low Stock":
          availabilityClass = "low-stock";
          break;
        default:
          availabilityClass = "";
      }
  
      row.innerHTML = `
        <td><img src="${item.image}" alt="${item.name}" class="profile-picture"></td>
        <td>${item.name}</td>
        <td>${item.status}</td>
        <td><span class="highlight ${availabilityClass}">${item.availability}</span></td>
        <td class="primary"><button class="view-button" role="button">View</button></td>
        <td><span class="material-symbols-outlined">edit</span></td>
        <td><span class="material-symbols-outlined">delete</span></td>
      `;
  
      tableBody.appendChild(row);
    });
  });
  