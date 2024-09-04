// Typewriter Effect

const words = ['Time', 'Money', 'Environment'];

// Main Timeline
let mainTimeLine = gsap.timeline({
  repeat: -1
});

// Cursor Timeline (blinking effect)
let cursorTimeLine = gsap.timeline({
  repeat: -1,
  paused: true
});

cursorTimeLine.to('#cursor', {
  opacity: 0,
  ease: "power2.inOut",
  duration: 0.6,
  repeat: -1,
  yoyo: true
});

// For each word, create a new timeline, use the Text plugin, then append that timeline to the main one
words.forEach(word => {
  let textTimeLine = gsap.timeline({
    repeat: 1,
    yoyo: true,
    repeatDelay: 1
  });

  textTimeLine.to('#typewriter', {
    text: word,
    duration: 1,
    onUpdate: () => {
      cursorTimeLine.restart();
      cursorTimeLine.pause();
    },
    onComplete: () => {
      cursorTimeLine.play();
    }
  });

  mainTimeLine.add(textTimeLine);
});


// navbar

// let dropdowns = document.querySelectorAll('.navbar .dropdown-toggler')
// let dropdownIsOpen = false

// // Handle dropdown menues
// if (dropdowns.length) {
//   dropdowns.forEach((dropdown) => {
//     dropdown.addEventListener('click', (event) => {
//       let target = document.querySelector(`#${event.target.dataset.dropdown}`)

//       if (target) {
//         if (target.classList.contains('show')) {
//           target.classList.remove('show')
//           dropdownIsOpen = false
//         } else {
//           target.classList.add('show')
//           dropdownIsOpen = true
//         }
//       }
//     })
//   })
// }

// // Handle closing dropdowns if a user clicked the body
// window.addEventListener('mouseup', (event) => {
//   if (dropdownIsOpen) {
//     dropdowns.forEach((dropdownButton) => {
//       let dropdown = document.querySelector(`#${dropdownButton.dataset.dropdown}`)
//       let targetIsDropdown = dropdown == event.target

//       if (dropdownButton == event.target) {
//         return
//       }

//       if ((!targetIsDropdown) && (!dropdown.contains(event.target))) {
//         dropdown.classList.remove('show')
//       }
//     })
//   }
// })
// function handleSmallScreens() {
//   document.querySelector('.navbar-toggler')
//     .addEventListener('click', () => {
//       let navbarMenu = document.querySelector('.navbar-menu')

//       if (!navbarMenu.classList.contains('active')) {
//         navbarMenu.classList.add('active')
//       } else {
//         navbarMenu.classList.remove('active')
//       }
//     })
// }

// handleSmallScreens()

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





