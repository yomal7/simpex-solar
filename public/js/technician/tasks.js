const projectTasks = [
    { 
        id: 1, 
        taskID: "T001", 
        dueDate: "2024/11/01", 
        completion: "completed", 
        taskDetails: "Install 5kW solar panel system at Keels.", 
        projectName: "Keels Installation", 
        comment: "Successfully completed installation and testing."
    },
    { 
        id: 2, 
        taskID: "T002", 
        dueDate: "2024/11/05", 
        completion: "completed", 
        taskDetails: "Solar panel maintenance at Watawala Industries.", 
        projectName: "Watawala Industries Maintenance", 
        comment: "Panels cleaned and inverter firmware updated."
    },
    { 
        id: 3, 
        taskID: "T003", 
        dueDate: "2024/11/10", 
        completion: "not-completed", 
        taskDetails: "Repair solar inverter at DB Ltd.", 
        projectName: "DB Ltd Repair", 
        comment: "Inverter requires a replacement part; pending delivery."
    },
    { 
        id: 4, 
        taskID: "T004", 
        dueDate: "2024/11/15", 
        completion: "completed", 
        taskDetails: "Install battery storage system for residential client.", 
        projectName: "Residential Battery Installation", 
        comment: "System installed and tested successfully."
    },
    { 
        id: 5, 
        taskID: "T005", 
        dueDate: "2024/11/20", 
        completion: "not-completed", 
        taskDetails: "Site survey at City Mall.", 
        projectName: "City Mall Site Survey", 
        comment: "Survey completed; waiting for client approval."
    }
];


const projectTasksTableBody = document.getElementById("projectTasksTableBody");
let currentProjectTaskID;

function renderTable(projectTasksToRender = projectTasks) {
    projectTasksTableBody.innerHTML = "";
    projectTasksToRender.forEach(projectTask => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${projectTask.taskID}</td>
            <td>${projectTask.projectName}</td>
            <td>${projectTask.taskDetails}</td>
            <td>${projectTask.dueDate}</td>
            <td><span class="completion ${projectTask.completion}" onclick="openCompletionPopup(${projectTask.id})">${getCompletionLabel(projectTask.completion)}</span></td>
            <td>
                <button class="action-btn add-comment-btn" onclick="openAddCommentPopup(${projectTask.id})">Add Comment</button>                
            </td>
        `;
        row.addEventListener("click", (e) => {
            if (!e.target.classList.contains('completion') && !e.target.classList.contains('add-comment-btn')) {
                viewDetails(projectTask.id);
            }
        });
        projectTasksTableBody.appendChild(row);
    });
}

function viewDetails(projectTaskID) {
    //alert('Viewing details for projectTask with ID: ${projectTaskID}');
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

function getCompletionLabel(completion) {
    switch (completion) {
        case "completed": return "Completed";
        case "not-completed": return "Not Completed";
        default: return "";
    }
}

function openCompletionPopup(projectTaskID) {
    currentProjectTaskID = projectTaskID;
    openPopup('completionPopup');
}

function updateCompletionChange() {
    const newCompletion = document.getElementById("completionSelect").value;
    const projectTask = projectTasks.find(u => u.id === currentProjectTaskID);
    if (projectTask) {
        projectTask.completion = newCompletion;
        renderTable();
    }
    closePopup('completionPopup');
}

function autoResize(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = '${textarea.scrollHeight}px';
}

function openAddCommentPopup(projectTaskID) {
    currentProjectTaskID = projectTaskID;
    const projectTask = projectTasks.find(u => u.id === projectTaskID);
    if (projectTask) {
        document.getElementById("addCommentText").value = projectTask.comment;
    }
    openPopup('addCommentPopup');
}

function confirmAddComment() {
    const projectTask = projectTasks.find(u => u.id === currentProjectTaskID);
    if (projectTask) {
        projectTask.comment = document.getElementById("addCommentText").value;
        renderTable();
    }
    closePopup('addCommentPopup');
}

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('active');
}

document.addEventListener('click', function(event) {
    if (event.target.classList.contains('overlay')) {
        closePopup('completionPopup');
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
            closePopup('projectTaskFormPopup');
        }
    });
});

renderTable();