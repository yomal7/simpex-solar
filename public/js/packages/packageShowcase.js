// Sample package data - In a real application, this would come from a backend API
const packagesData = [
    {
        id: 1,
        name: "Basic Off-Grid Package",
        type: "off-grid",
        price: "$8,999",
        image: "/api/placeholder/400/320",
        features: [
            "5kW System Capacity",
            "15 Solar Panels",
            "10-Year Warranty",
            "Basic Monitoring System"
        ]
    },
    {
        id: 2,
        name: "Premium On-Grid Package",
        type: "on-grid",
        price: "$12,999",
        image: "/api/placeholder/400/320",
        features: [
            "7.5kW System Capacity",
            "22 Solar Panels",
            "15-Year Warranty",
            "Smart Monitoring System"
        ]
    },
    {
        id: 3,
        name: "Advanced Hybrid Package",
        type: "hybrid",
        price: "$16,999",
        image: "/api/placeholder/400/320",
        features: [
            "10kW System Capacity",
            "30 Solar Panels",
            "20-Year Warranty",
            "Advanced Monitoring"
        ]
    },
    {
        id: 4,
        name: "Ultimate Off-Grid Package",
        type: "off-grid",
        price: "$22,999",
        image: "/api/placeholder/400/320",
        features: [
            "15kW System Capacity",
            "45 Solar Panels",
            "25-Year Warranty",
            "Premium Monitoring"
        ]
    },
    {
        id: 5,
        name: "Commercial Hybrid Package",
        type: "hybrid",
        price: "Custom Quote",
        image: "/api/placeholder/400/320",
        features: [
            "20kW+ System Capacity",
            "60+ Solar Panels",
            "30-Year Warranty",
            "Enterprise Monitoring"
        ]
    }
];

// Function to create a package card
function createPackageCard(package) {
    const baseURL = "http://localhost/simplex";
  // <div class="package-card" data-type="${package.type}" onclick="location.href='/package/${package.id}'">
    return `
        <div class="package-card" data-type="${package.type}" onclick="location.href='${baseURL}/packages/packageDetails'">
            <h2 class="package-name">${package.name}</h2>
            <img src="public/assets/product_poster.png" alt="${package.name}" class="package-image">
            <div class="package-price">${package.price}</div>
            <ul class="features-list">
                ${package.features.map(feature => `<li>${feature}</li>`).join('')}
            </ul>     

            <div class="learn-more-btn-wrapper">
                <button class="learn-more-btn" window.location.href='${baseURL}/packages/packageComformation'">
                    Get quote
                    <svg class="icon" viewBox="0 0 24 24" fill="currentColor">
                        <path
                        fill-rule="evenodd"
                        d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm4.28 10.28a.75.75 0 000-1.06l-3-3a.75.75 0 10-1.06 1.06l1.72 1.72H8.25a.75.75 0 000 1.5h5.69l-1.72 1.72a.75.75 0 101.06 1.06l3-3z"
                        clip-rule="evenodd"
                        ></path>
                    </svg>
                    </button>
            </div>

        </div>
    `;
}

// Function to render packages
function renderPackages(type = 'all') {
    const container = document.getElementById('packagesContainer');
    container.innerHTML = '';
    
    const filteredPackages = type === 'all' 
        ? packagesData 
        : packagesData.filter(package => package.type === type);

    filteredPackages.forEach(package => {
        container.innerHTML += createPackageCard(package);
    });

    // Reset animations
    const cards = document.querySelectorAll('.package-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
}

// Add click handlers for navigation buttons
document.querySelectorAll('.nav-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        // Update active button
        document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
        e.target.classList.add('active');

        // Filter packages
        const type = e.target.dataset.type;
        renderPackages(type);
    });
});

// Initial render
renderPackages();

// Add intersection observer for smoother animations
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.animationPlayState = 'running';
        }
    });
}, {
    threshold: 0.1
});

// Observe all package cards
function observeCards() {
    document.querySelectorAll('.package-card').forEach(card => {
        card.style.animationPlayState = 'paused';
        observer.observe(card);
    });
}

// Call observeCards after initial render
observeCards();

