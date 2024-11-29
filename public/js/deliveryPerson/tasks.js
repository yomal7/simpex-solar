const tasks = [
    { 
        id: 1, 
        deliveryID: "D0001", 
        date: "2024/11/01", 
        confirmation: "confirmed", 
        address: "Keels Warehouse, 456 Main Street, Colombo", 
        phoneNumber: "0712345678", 
        comment: "Delivered solar panels and inverter for installation at Keels. Received by site supervisor."
    },
    { 
        id: 2, 
        deliveryID: "D0002", 
        date: "2024/11/05", 
        confirmation: "confirmed", 
        address: "Watawala Industries, 123 Solar Lane, Gampaha", 
        phoneNumber: "0723456789", 
        comment: "Delivered maintenance kit and inverter update tools. Package received by maintenance lead."
    },
    { 
        id: 3, 
        deliveryID: "D0003", 
        date: "2024/11/10", 
        confirmation: "not-confirmed", 
        address: "DB Ltd, 789 Inverter Drive, Kandy", 
        phoneNumber: "0765432100", 
        comment: "Replacement part for inverter delivery pending; awaiting client confirmation."
    },
    { 
        id: 4, 
        deliveryID: "D0004", 
        date: "2024/11/15", 
        confirmation: "confirmed", 
        address: "No. 12, Green Avenue, Nugegoda", 
        phoneNumber: "0776543211", 
        comment: "Delivered battery storage system for residential client. Received by homeowner."
    },
    { 
        id: 5, 
        deliveryID: "D0005", 
        date: "2024/11/20", 
        confirmation: "not-confirmed", 
        address: "City Mall, 101 Commercial Street, Colombo", 
        phoneNumber: "0787654321", 
        comment: "Delivered survey equipment and documentation for site survey. Awaiting client approval."
    }
];


const tasksTableBody = document.getElementById("tasksTableBody");
let currentTaskID;

function renderTable(tasksToRender = tasks) {
    tasksTableBody.innerHTML = "";
    tasksToRender.forEach(task => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${task.deliveryID}</td>
            <td>${task.date}</td>
            <td>
                <button class="action-btn view-btn" onclick="openViewPopup(${task.id})">View</button>                
            </td>
            <td><span class="confirmation ${task.confirmation}" onclick="openConfirmationPopup(${task.id})">${getConfirmationLabel(task.confirmation)}</span></td>
            <td>
                <button class="action-btn add-comment-btn" onclick="openAddCommentPopup(${task.id})">Add Comment</button>                
            </td>
        `;
        row.addEventListener("click", (e) => {
            if (!e.target.classList.contains('view-btn') && !e.target.classList.contains('confirmation') && !e.target.classList.contains('add-comment-btn')) {
                viewDetails(task.id);
            }
        });
        tasksTableBody.appendChild(row);
    });
}

function viewDetails(taskID) {
    //alert('Viewing details for task with ID: ${taskID}');
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

function openViewPopup(taskID) {
    currentTaskID = taskID;
    const task = tasks.find(u => u.id === taskID);
    if (task) {
        document.getElementById("viewDeliveryID").textContent = task.deliveryID;
        document.getElementById("viewDate").textContent = task.date;
        document.getElementById("viewAddress").textContent = task.address;
        document.getElementById("viewPhoneNumber").textContent = task.phoneNumber;
    }
    openPopup('viewPopup');
}

function confirmView() {
    closePopup('viewPopup');
}

function getConfirmationLabel(confirmation) {
    switch (confirmation) {
        case "confirmed": return "Confirmed";
        case "not-confirmed": return "Not Confirmed";
        default: return "";
    }
}

function openConfirmationPopup(taskID) {
    currentTaskID = taskID;
    openPopup('confirmationPopup');
}

function updateConfirmationChange() {
    const newConfirmation = document.getElementById("confirmationSelect").value;
    const task = tasks.find(u => u.id === currentTaskID);
    if (task) {
        task.confirmation = newConfirmation;
        renderTable();
    }
    closePopup('confirmationPopup');
}

function autoResize(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = '${textarea.scrollHeight}px';
}

function openAddCommentPopup(taskID) {
    currentTaskID = taskID;
    const task = tasks.find(u => u.id === taskID);
    if (task) {
        document.getElementById("addCommentText").value = task.comment;
    }
    openPopup('addCommentPopup');
}

function confirmAddComment() {
    const task = tasks.find(u => u.id === currentTaskID);
    if (task) {
        task.comment = document.getElementById("addCommentText").value;
        renderTable();
    }
    closePopup('addCommentPopup');
}

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('active');
}

document.addEventListener('click', function(event) {
    if (event.target.classList.contains('overlay')) {
        closePopup('confirmationPopup');
        closePopup('viewPopup');
        closePopup('addCommentPopup');
    }
});

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
            closePopup('taskFormPopup');
        }
    });
});

renderTable();