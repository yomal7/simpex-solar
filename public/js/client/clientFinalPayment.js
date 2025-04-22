// // clientFinalPayment.js
// document.addEventListener('DOMContentLoaded', function() {
//     // Mobile sidebar toggle
//     const menuBtn = document.querySelector('.bx-menu');
//     const sidebar = document.querySelector('.sidebar');
//     const content = document.querySelector('.content');

//     if (menuBtn) {
//         menuBtn.addEventListener('click', () => {
//             sidebar.classList.toggle('active');
//             content.classList.toggle('active');
//         });
//     }

//     // Toggle payment methods
//     const methodHeaders = document.querySelectorAll('.method-header');
    
//     methodHeaders.forEach(header => {
//         header.addEventListener('click', function() {
//             const methodId = this.getAttribute('onclick').match(/'([^']+)'/)[1];
//             toggleMethod(methodId);
//         });
//     });

//     // Open first payment method by default
//     const firstMethod = document.querySelector('.method-content');
//     if (firstMethod) {
//         firstMethod.style.display = 'block';
//         const header = firstMethod.previousElementSibling;
//         const icon = header.querySelector('.toggle-icon');
//         if (icon) {
//             icon.textContent = '-';
//         }
//     }
// });