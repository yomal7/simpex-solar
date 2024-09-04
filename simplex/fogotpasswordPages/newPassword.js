function handleSubmit(event) {
    // Prevent the form from submitting automatically
    event.preventDefault();

    // Show the popup
    document.getElementById('new-password-popup').style.display = 'flex';

    // Add a class to the body to change the background color
    document.body.classList.add('popup-active');
}

function closePopup() {
    // Hide the popup
    document.getElementById('new-password-popup').style.display = 'none';

    // Remove the class from the body to reset the background color
    document.body.classList.remove('popup-active');
    window.location.href = "../signinPage/signinPage.html";

}
