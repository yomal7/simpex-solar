document.addEventListener("DOMContentLoaded", function() {
    const data = [
        {
            taskID: 'T001',
            projectName: 'Project1',
            task: 'Task1',
            dueDate: '2024/08/31',
            status: 'Completed'
        },
        {
            taskID: 'T002',
            projectName: 'Project2',
            task: 'Task2',
            dueDate: '2024/09/01',
            status: 'Not Completed'
        },
        {
            taskID: 'T003',
            projectName: 'Project3',
            task: 'Task3',
            dueDate: '2024/09/02',
            status: 'Not Completed'
        },
        {
            taskID: 'T004',
            projectName: 'Project4',
            task: 'Task4',
            dueDate: '2024/09/03',
            status: 'Not Completed'
        }
    ];
  
    const tableBody = document.querySelector(".technician-table tbody");
  
    data.forEach(item => {
        const row = document.createElement("tr");
  
        let statusClass = "";
        switch (item.status) {
            case "Completed":
            statusClass = "completed";
            break;
            case "Not Completed":
            statusClass = "not-completed";
            break;
            default:
            statusClass = "";
      }

        row.innerHTML = `
            <td>${item.taskID}</td>
            <td>${item.projectName}</td>
            <td>${item.task}</td>
            <td>${item.dueDate}</td>
            <td><span class="highlight ${statusClass}">${item.status}</span></td>
            <td class="primary"><button class="addComment-button" role="button">Add Comment</button></td>
        `;
    
        tableBody.appendChild(row);
    });

  });

  
  
