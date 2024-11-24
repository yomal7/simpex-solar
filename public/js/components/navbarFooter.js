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





