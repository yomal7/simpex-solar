    // Counter Animation
    const counters = document.querySelectorAll('.counter');
    const speed = 200;

    const animateCounter = (counter) => {
    const target = +counter.getAttribute('data-target');
    let count = 0;
    
    const updateCount = () => {
        const increment = target / speed;
        
        if (count < target) {
        count += increment;
        counter.innerText = Math.ceil(count).toLocaleString();
        setTimeout(updateCount, 1);
        } else {
        counter.innerText = target.toLocaleString();
        }
    };
    
    updateCount();
    };

    // Intersection Observer for counter animation
    const observerOptions = {
    threshold: 0.5
    };

    const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
        const counter = entry.target;
        animateCounter(counter);
        observer.unobserve(counter);
        }
    });
    }, observerOptions);

    counters.forEach(counter => {
    observer.observe(counter);
    });

    // Smooth scroll for navigation
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
        behavior: 'smooth'
        });
    });
    });

    // Add scroll reveal animations
    const revealElements = document.querySelectorAll('.achievement-card, .feature');

    const scrollReveal = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
        entry.target.classList.add('revealed');
        scrollReveal.unobserve(entry.target);
        }
    });
    }, {
    threshold: 0.1
    });


// Handle dropdown menus
let dropdowns = document.querySelectorAll('.nav-links > li > input[type="checkbox"]');
let dropdownIsOpen = false;

if (dropdowns.length) {
  dropdowns.forEach((dropdown) => {
    dropdown.addEventListener('change', (event) => {
      let target = event.target.nextElementSibling.nextElementSibling;
      if (target) {
        if (event.target.checked) {
          target.classList.add('show');
          dropdownIsOpen = true;
        } else {
          target.classList.remove('show');
          dropdownIsOpen = false;
        }
      }
    });
  });
}

// Handle closing dropdowns if a user clicked outside
document.addEventListener('click', (event) => {
  if (dropdownIsOpen) {
    dropdowns.forEach((dropdown) => {
      if (!dropdown.contains(event.target)) {
        dropdown.checked = false;
        dropdown.nextElementSibling.nextElementSibling.classList.remove('show');
      }
    });
  }
});

// Handle mobile menu
function handleSmallScreens() {
  const menuBtn = document.getElementById('menu-btn');
  const closeBtn = document.getElementById('close-btn');
  const navLinks = document.querySelector('.nav-links');

  menuBtn.addEventListener('change', () => {
    if (menuBtn.checked) {
      navLinks.classList.add('active');
    }
  });

  closeBtn.addEventListener('change', () => {
    if (closeBtn.checked) {
      navLinks.classList.remove('active');
    }
  });
}

handleSmallScreens();







