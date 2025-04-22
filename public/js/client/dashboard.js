document.addEventListener('DOMContentLoaded', function() {
    // Set greeting based on time of day
    setGreeting();
});

/**
 * Sets the greeting based on the time of day
 */
function setGreeting() {
    const hour = new Date().getHours();
    let greeting;
    
    if (hour < 12) {
        greeting = 'Good Morning';
    } else if (hour < 18) {
        greeting = 'Good Afternoon';
    } else {
        greeting = 'Good Evening';
    }
    
    const greetingElement = document.getElementById('greeting-text');
    if (greetingElement) {
        // Extract customer name from existing greeting text
        const currentText = greetingElement.textContent;
        const customerName = currentText.split(',')[1]?.trim().replace('!', '') || 'Customer';
        
        greetingElement.textContent = `${greeting}, ${customerName}!`;
    }
}