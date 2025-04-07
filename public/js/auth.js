const inputs = document.querySelectorAll(".input-field");
const toggle_btn = document.querySelectorAll(".toggle");
const main = document.querySelector("main");
const bullets = document.querySelectorAll(".bullets span");
const images = document.querySelectorAll(".image");

// Show active state for filled inputs on page load
window.addEventListener('DOMContentLoaded', () => {
    inputs.forEach((inp) => {
        if (inp.value !== "") {
            inp.classList.add("active");
        }
    });
});

inputs.forEach((inp) => {
    inp.addEventListener("focus", () => {
        inp.classList.add("active");
    });
    inp.addEventListener("blur", () => {
        if (inp.value != "") return;
        inp.classList.remove("active");
    });
});

toggle_btn.forEach((btn) => {
    btn.addEventListener("click", (e) => {
        e.preventDefault();
        main.classList.toggle("sign-up-mode");
        
        // Update hidden mode input
        const signupForm = document.querySelector('.sign-up-form');
        const signinForm = document.querySelector('.sign-in-form');
        const isSignUpMode = main.classList.contains('sign-up-mode');
        
        // Clear previous error messages when switching forms
        const errorSpans = document.querySelectorAll('.form-invalid');
        errorSpans.forEach(span => span.textContent = '');
        
        // Clear form inputs when switching
        if (isSignUpMode) {
            signinForm.reset();
        } else {
            signupForm.reset();
        }
        
        // Remove active class from inputs
        inputs.forEach(input => {
            input.classList.remove('active');
        });
    });
});

function moveSlider() {
    let index = this.dataset.value;

    let currentImage = document.querySelector(`.img-${index}`);
    images.forEach((img) => img.classList.remove("show"));
    currentImage.classList.add("show");

    const textSlider = document.querySelector(".text-group");
    textSlider.style.transform = `translateY(${-(index - 1) * 2.2}rem)`;

    bullets.forEach((bull) => bull.classList.remove("active"));
    this.classList.add("active");
}

bullets.forEach((bullet) => {
    bullet.addEventListener("click", moveSlider);
});

// If there are any error messages, make sure inputs stay active
document.querySelectorAll('.form-invalid').forEach(errorSpan => {
    if (errorSpan.textContent.trim() !== '') {
        const input = errorSpan.previousElementSibling;
        if (input) {
            input.classList.add('active');
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const homeBtn = document.querySelector('.home-btn');
    
    // Add hover effect
    homeBtn.addEventListener('mouseover', function() {
        this.style.backgroundColor = '#f8f9fa';
    });
    
    homeBtn.addEventListener('mouseout', function() {
        this.style.backgroundColor = 'white';
    });
    
    // Add click effect
    homeBtn.addEventListener('mousedown', function() {
        this.style.transform = 'scale(0.98)';
    });
    
    homeBtn.addEventListener('mouseup', function() {
        this.style.transform = 'translateY(-2px)';
    });
});