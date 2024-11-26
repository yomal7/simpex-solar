function animateValue(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        element.innerHTML = Math.floor(progress * (end - start) + start);
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

// Simulated data (replace with actual data fetching in a real application)
const projectData = {
    total: 150,
    ongoing: 42,
    completed: 108
};

// Animate the values when the page loads
window.addEventListener('load', () => {
    animateValue(document.querySelector('#total-projects .card-value'), 0, projectData.total, 2000);
    animateValue(document.querySelector('#ongoing-projects .card-value'), 0, projectData.ongoing, 2000);
    animateValue(document.querySelector('#completed-projects .card-value'), 0, projectData.completed, 2000);
});

// project table

const projects = [
    { id: 'PRJ001', name: 'John Doe', image: 'userimg.jpg', startDate: '2024-01-15', stage: 'Planning', location: 'New York', quotationUrl: '#' },
    { id: 'PRJ002', name: 'Jane Smith', image: 'userimg.jpg', startDate: '2024-02-01', stage: 'Development', location: 'London', quotationUrl: '#' },
    { id: 'PRJ003', name: 'Mike Johnson', image: 'userimg.jpg', startDate: '2024-02-15', stage: 'Testing', location: 'Paris', quotationUrl: '#' },
    { id: 'PRJ004', name: 'Sarah Wilson', image: 'userimg.jpg', startDate: '2024-03-01', stage: 'Deployment', location: 'Berlin', quotationUrl: '#' },
    { id: 'PRJ005', name: 'Tom Brown', image: 'userimg.jpg', startDate: '2024-03-15', stage: 'Planning', location: 'Tokyo', quotationUrl: '#' },
    { id: 'PRJ006', name: 'Emily Davis', image: 'userimg.jpg', startDate: '2024-04-01', stage: 'Development', location: 'Sydney', quotationUrl: '#' },
    { id: 'PRJ007', name: 'David Lee', image: 'userimg.jpg', startDate: '2024-04-15', stage: 'Testing', location: 'Singapore', quotationUrl: '#' },
    { id: 'PRJ008', name: 'Lisa Wang', image: 'userimg.jpg', startDate: '2024-05-01', stage: 'Deployment', location: 'Hong Kong', quotationUrl: '#' },
    { id: 'PRJ009', name: 'James Wilson', image: 'userimg.jpg', startDate: '2024-05-15', stage: 'Planning', location: 'Toronto', quotationUrl: '#' },
    { id: 'PRJ010', name: 'Anna Chen', image: 'userimg.jpg', startDate: '2024-06-01', stage: 'Development', location: 'Vancouver', quotationUrl: '#' },
];

const itemsPerPage = 5;
let currentPage = 1;

function renderProjects(page) {
    const startIndex = (page - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const tableBody = document.getElementById('projectTableBody');
    tableBody.innerHTML = '';

    projects.slice(startIndex, endIndex).forEach((project, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <img src="${project.image}" alt="${project.manager}" class="profile-pic">
            </td>
            <td>${project.name}</td>
            <td>${project.id}</td>
            <td>${project.startDate}</td>
            <td><span class="stage-badge">${project.stage}</span></td>
            <td>${project.location}</td>
            <td>
                <button class="download-btn" onclick="downloadQuotation('${project.id}')">
                    Download
                </button>
            </td>
        `;
        row.style.animationDelay = `${index * 0.1}s`;
        row.addEventListener('click', () => navigateToProject(project.id));
        tableBody.appendChild(row);
    });

    renderPagination();
}

function renderPagination() {
    const totalPages = Math.ceil(projects.length / itemsPerPage);
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement('button');
        button.innerText = i;
        button.classList.toggle('active', i === currentPage);
        button.addEventListener('click', () => {
            currentPage = i;
            renderProjects(currentPage);
        });
        pagination.appendChild(button);
    }
}

function navigateToProject(projectId) {
    // In a real application, this would navigate to the project page
    console.log(`Navigating to project ${projectId}`);
}

function downloadQuotation(projectId) {
    event.stopPropagation(); // Prevent row click event
    // In a real application, this would trigger the download
    console.log(`Downloading quotation for project ${projectId}`);
}

// Initial render
renderProjects(currentPage);

        document.querySelectorAll('.phase-header').forEach(header => {
            header.addEventListener('click', () => {
                const content = header.nextElementSibling;
                const dropdownIcon = header.querySelector('.dropdown-icon');
                const isExpanded = content.style.maxHeight;

                // Close all other phase contents
                document.querySelectorAll('.phase-content').forEach(otherContent => {
                    if (otherContent !== content) {
                        otherContent.style.maxHeight = null;
                        const otherIcon = otherContent.previousElementSibling.querySelector('.dropdown-icon');
                        otherIcon.style.transform = 'rotate(0deg)';
                    }
                });

                // Toggle current phase content
                if (isExpanded) {
                    content.style.maxHeight = null;
                    dropdownIcon.style.transform = 'rotate(0deg)';
                } else {
                    content.style.maxHeight = content.scrollHeight + 'px';
                    dropdownIcon.style.transform = 'rotate(180deg)';
                }
            });
        });

        // Update progress bar for demonstration
        let progress = 0;
        const progressFill = document.querySelector('.progress-fill');
        setInterval(() => {
            if (progress < 100) {
                progress += 1;
                progressFill.style.width = `${progress}%`;
            }
        }, 100);