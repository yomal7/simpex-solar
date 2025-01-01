// document.addEventListener("DOMContentLoaded", function() {
//     // Time-based greeting
//     function getGreeting() {
//         const hour = new Date().getHours();
//         if (hour >= 5 && hour < 12) return "Good Morning";
//         if (hour >= 12 && hour < 17) return "Good Afternoon";
//         if (hour >= 17 && hour < 22) return "Good Evening";
//         return "Good Night";
//     }

//     // Update greeting
//     document.getElementById('greeting-message').textContent = getGreeting();

//     // Simulate progress bar update
//     function updateProgress(progress) {
//         const progressBar = document.getElementById('progressBar');
//         const progressText = document.getElementById('progressText');
        
//         progressBar.style.width = `${progress}%`;
//         progressText.textContent = progress;
//     }
    
//     // Simulate progress update (for demo purposes)
//     setTimeout(() => {
//         updateProgress(75);
//     }, 1000);

//     // Update greeting every minute
//     setInterval(() => {
//         document.getElementById('greeting-message').textContent = getGreeting();
//     }, 60000);

//     // Example function to handle profile picture edit
//     const editIcon = document.querySelector('.edit-icon');
//     if (editIcon) {
//         editIcon.addEventListener('click', function() {
//             // You can implement file upload functionality here
//             alert('Edit profile picture functionality will be implemented here');
//         });
//     }
// });

// // timeline
// document.addEventListener('DOMContentLoaded', () => {
// // Back button functionality
// document.querySelector('.back-button').addEventListener('click', () => {
// window.history.back();
// });

// // Define the mapping of each phase to its URL
// const phaseToUrlMap = {
//     'Agreemnet phase': 'agreement',
//     'Site visit phase': 'sitevisit',
//     'Installation phase': 'installation',
//     'first payment phase': 'firstpayment',
//     'Final payment phase': 'finalpayment',
// };

// // Find the active timeline item
// const activeTimelineItem = document.querySelector('.timeline-item.active');

// // Create the "Proceed to Next Step" button if active step is found
// if (activeTimelineItem) {
//     const phaseTitle = activeTimelineItem.querySelector('h3').textContent;
//     const targetUrl = phaseToUrlMap[phaseTitle]; // Get the URL from the map

//     // Create and configure the "Proceed to Next Step" button
//     const proceedButton = document.createElement('button');
//     proceedButton.className = 'proceed-button';
//     proceedButton.textContent = 'Proceed to Next Step';

//     // Add click handler to navigate to the correct page
//     proceedButton.addEventListener('click', function () {
//         if (targetUrl) {
//             window.location.href = targetUrl; // Navigate to the target URL
//         } else {
//             alert('No URL defined for this phase');
//         }
//     });

//     // Append the button to the active timeline item
//     activeTimelineItem.querySelector('.timeline-card').appendChild(proceedButton);
// }

// // Intersection Observer for animation on scroll
// const observer = new IntersectionObserver((entries) => {
//     entries.forEach(entry => {
//         if (entry.isIntersecting) {
//             entry.target.style.opacity = '1';
//         }
//     });
// }, { threshold: 0.1 });

// document.querySelectorAll('.timeline-item').forEach(item => {
//     observer.observe(item);
// });
// });
