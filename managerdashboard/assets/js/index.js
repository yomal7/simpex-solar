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


    // supplier cards

    
      const suppliersData = [
        {
            image: 'assets/images/logo.png',
            name: 'Browns Groups',
            description: 'As you already know our Motherland has been completely freed from the clutches of separatist terrorism. From now on it is only the laws enacted by this sovereign Parliament that will be in force in every inch of Sri Lanka.'
        },
        {
            image: 'assets/images/Panasoniclogo.jpeg',
            name: 'Panasonic',
            description: 'Hon. Speaker, I believe that today is a day of positive change in our country after a struggle. Today we are putting a significant step forward and this is an important day to initiate action to eliminate corruption, fraud and theft, which have become a curse to this country, from society, government and non-governmental bodies.'
        },
        {
            image: 'assets/images/Adani_2012_logo.png',
            name: 'Adani Groups',
            description: 'Decisions were often driven by financial gains, rather than the long-term benefit to the nation, he noted. Dissanayake asserted that Sri Lanka cannot be bought or swayed, adding that the country\'s foreign policy must prioritize national interest above all else.'
        }
    ];

    const supplierCardsContainer = document.querySelector(".supplier-cards");

    suppliersData.forEach(supplier => {
        const card = document.createElement("div");
        card.className = "supplier-card";

        card.innerHTML = `
            <div class="icon-container">
                <span class="material-symbols-outlined">edit</span>
                <span class="material-symbols-outlined">delete</span>
            </div>
            <img src="${supplier.image}" alt="supplier profile picture" class="supplier-profilepic">
            <div class="name">${supplier.name}</div>
            <p class="description">${supplier.description}</p>
            <button class="supplier-viewbutton" role="button">View</button>
        `;

        supplierCardsContainer.appendChild(card);
    });
  });

  
  
