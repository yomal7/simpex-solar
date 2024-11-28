const projects = [
    {
        id: 'PRJ001',
        customerName: 'John Doe',
        location: 'New York, NY',
        phase: 'agreement'
    },
    {
        id: 'PRJ002',
        customerName: 'Jane Smith',
        location: 'Los Angeles, CA',
        phase: 'site-visit'
    },
    {
        id: 'PRJ003',
        customerName: 'Robert Johnson',
        location: 'Chicago, IL',
        phase: 'first-payment'
    },
    {
        id: 'PRJ004',
        customerName: 'Sarah Williams',
        location: 'Houston, TX',
        phase: 'installation'
    },
    {
        id: 'PRJ005',
        customerName: 'Michael Brown',
        location: 'Phoenix, AZ',
        phase: 'final-payment'
    },
    {
        id: 'PRJ006',
        customerName: 'Emily Davis',
        location: 'Seattle, WA',
        phase: 'engineer-approval'
    }
];

function createProjectCard(project) {
    return `
        <div class="project-card" data-phase="${project.phase}" onclick="window.location.href='manageAproject'">
            <div class="project-content">
                <div class="project-id">#${project.id}</div>
                <div class="customer-name">${project.customerName}</div>
                <div class="project-location">
                    📍 ${project.location}
                </div>
                <div class="project-phase">
                    ${project.phase.replace('-', ' ').toUpperCase()}
                </div>
            </div>
        </div>
    `;
}

function renderProjects(phase = 'all') {
    const projectsGrid = document.getElementById('projectsGrid');
    projectsGrid.innerHTML = '';
    
    const filteredProjects = phase === 'all' 
        ? projects 
        : projects.filter(project => project.phase === phase);
    
    filteredProjects.forEach(project => {
        projectsGrid.innerHTML += createProjectCard(project);
    });
}

function navigateToProject(projectId) {
    window.location.href = `/manageProject?id=${projectId}`;
}

// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    renderProjects();

    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            e.target.classList.add('active');
            renderProjects(e.target.dataset.phase);
        });
    });
});