document.addEventListener('DOMContentLoaded', () => {
    const imgUpload = document.getElementById('img-upload');
    const profileImg = document.getElementById('profile-img');
    const saveBtn = document.querySelector('.save-btn');

    imgUpload.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                profileImg.src = e.target.result;
                showSuccessAnimation();
            };
            reader.readAsDataURL(file);
        }
    });

    function showSuccessAnimation() {
        saveBtn.classList.add('success');
        setTimeout(() => {
            saveBtn.classList.remove('success');
        }, 2000);
    }
});

function saveChanges() {
    const phone = document.getElementById('phone').value;
    const currentPassword = document.getElementById('current-password').value;
    const newPassword = document.getElementById('new-password').value;

    // Here you would typically make an API call to save the changes
    // For demo purposes, we'll just show the success animation
    const saveBtn = document.querySelector('.save-btn');
    saveBtn.classList.add('success');
    
    // Reset password fields
    document.getElementById('current-password').value = '';
    document.getElementById('new-password').value = '';

    setTimeout(() => {
        saveBtn.classList.remove('success');
    }, 2000);
}