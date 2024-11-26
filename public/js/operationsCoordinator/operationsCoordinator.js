const users = [
    { id: 1, name: "John Doe", address: "123 Main St", email: "john@example.com", availability: "in-stock" },
    { id: 2, name: "Jane Smith", address: "456 Elm St", email: "jane@example.com", availability: "low-stock" },
    { id: 3, name: "Bob Johnson", address: "789 Oak St", email: "bob@example.com", availability: "out-of-stock" },
];

const tableBody = document.getElementById("tableBody");
let currentUserId;

function renderTable(usersToRender = users) {
    tableBody.innerHTML = "";
    usersToRender.forEach(user => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td><img src="userimg.jpg" alt="${user.name}" class="profile-pic"></td>
            <td>${user.name}</td>
            <td>${user.address}</td>
            <td>${user.email}</td>
            <td><span class="availability ${user.availability}" onclick="openAvailabilityPopup(${user.id})">${getAvailabilityLabel(user.availability)}</span></td>
            <td>
                <button class="action-btn edit-btn" onclick="openEditPopup(${user.id})">Edit</button>
                <button class="action-btn delete-btn" onclick="openDeletePopup(${user.id})">Delete</button>
            </td>
        `;
        row.addEventListener("click", (e) => {
            if (!e.target.classList.contains('action-btn') && !e.target.classList.contains('availability')) {
                viewDetails(user.id);
            }
        });
        tableBody.appendChild(row);
    });
}

function getAvailabilityLabel(availability) {
    switch (availability) {
        case "in-stock": return "In Stock";
        case "low-stock": return "Low Stock";
        case "out-of-stock": return "Out of Stock";
        default: return "";
    }
}

function openPopup(popupId) {
    document.getElementById(popupId).classList.add('open-popup');
    document.getElementById('overlay').style.visibility = 'visible';
    document.getElementById('overlay').style.opacity = '1';
}

function closePopup(popupId) {
    document.getElementById(popupId).classList.remove('open-popup');
    document.getElementById('overlay').style.visibility = 'hidden';
    document.getElementById('overlay').style.opacity = '0';
}

function openAvailabilityPopup(userId) {
    currentUserId = userId;
    openPopup('availabilityPopup');
}

function confirmAvailabilityChange() {
    const newAvailability = document.getElementById("availabilitySelect").value;
    const user = users.find(u => u.id === currentUserId);
    if (user) {
        user.availability = newAvailability;
        renderTable();
    }
    closePopup('availabilityPopup');
}

function openEditPopup(userId) {
    currentUserId = userId;
    const user = users.find(u => u.id === userId);
    if (user) {
        document.getElementById("editName").value = user.name;
        document.getElementById("editAddress").value = user.address;
        document.getElementById("editEmail").value = user.email;
    }
    openPopup('editPopup');
}

function confirmEdit() {
    const user = users.find(u => u.id === currentUserId);
    if (user) {
        user.name = document.getElementById("editName").value;
        user.address = document.getElementById("editAddress").value;
        user.email = document.getElementById("editEmail").value;
        renderTable();
    }
    closePopup('editPopup');
}

function openDeletePopup(userId) {
    currentUserId = userId;
    openPopup('deletePopup');
}

function confirmDelete() {
    const index = users.findIndex(u => u.id === currentUserId);
    if (index !== -1) {
        users.splice(index, 1);
        renderTable();
    }
    closePopup('deletePopup');
}

function viewDetails(userId) {
    alert(`Viewing details for user with ID: ${userId}`);
}

function searchUsers() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const filteredUsers = users.filter(user => 
        user.name.toLowerCase().includes(searchTerm) ||
        user.email.toLowerCase().includes(searchTerm) ||
        user.address.toLowerCase().includes(searchTerm)
    );
    renderTable(filteredUsers);
}

function handleSubmit(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData);
    console.log('Form submitted with data:', data);
    closePopup('userFormPopup');
}

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('active');
}

// Close popups when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('overlay')) {
        closePopup('availabilityPopup');
        closePopup('editPopup');
        closePopup('deletePopup');
    }
});

function handleSecondarySubmit(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const projectData = Object.fromEntries(formData.entries());
    console.log('New Project Data:', projectData);
    // Here you would typically send the data to your server
    alert('Project added successfully!');
    event.target.reset();
}

// Add animation to cards
const cards = document.querySelectorAll('.card');
cards.forEach(card => {
    card.addEventListener('mouseover', () => {
        card.style.transform = 'translateY(-10px)';
    });
    card.addEventListener('mouseout', () => {
        card.style.transform = 'translateY(0)';
    });
});

// Validate date inputs
const secondaryStartDate = document.getElementById('secondaryStartDate');
const secondaryEndDate = document.getElementById('secondaryEndDate');

secondaryStartDate.addEventListener('change', () => {
    secondaryEndDate.min = secondaryStartDate.value;
});

secondaryEndDate.addEventListener('change', () => {
    if (secondaryEndDate.value < secondaryStartDate.value) {
        alert('End date must be after the start date');
        secondaryEndDate.value = '';
    }
});

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const menuToggle = document.querySelector('.menu-toggle');
    if (window.innerWidth <= 768 && sidebar.classList.contains('active') && 
        !sidebar.contains(event.target) && event.target !== menuToggle) {
        sidebar.classList.remove('active');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('overlay');
    
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            closePopup('userFormPopup');
        }
    });
});

// Revenue Chart
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Revenue',
            data: [12, 19, 3, 5, 2, 3],
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

renderTable();

