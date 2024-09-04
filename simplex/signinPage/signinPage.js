document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    const eField = form.querySelector(".sign-in-email");
    const eInput = eField.querySelector("input");
    const pField = form.querySelector(".sign-in-password");
    const pInput = pField.querySelector("input");
    const pwShowHide = document.querySelectorAll("i.showHidePw");


    // JS code to show/hide password and change icon
    pwShowHide.forEach(eyeIcon => {
        eyeIcon.addEventListener("click", (e) => {
            e.preventDefault(); // Prevent default action
            e.stopPropagation(); // Stop the event from bubbling up

            if (pInput.type === "password") {
                pInput.type = "text";
                pwShowHide.forEach(icon => {
                    icon.classList.replace("uil-eye-slash", "uil-eye");
                });
            } else {
                pInput.type = "password";
                pwShowHide.forEach(icon => {
                    icon.classList.replace("uil-eye", "uil-eye-slash");
                });
            }
        });
    });

    form.onsubmit = (e) => {
        e.preventDefault(); // Preventing form submission

        // If email and password are blank, add shake class; otherwise, call the specified function
        (eInput.value === "") ? eField.classList.add("shake", "error") : checkEmail();
        (pInput.value === "") ? pField.classList.add("shake", "error") : checkPass();

        setTimeout(() => { // Remove shake class after 500ms
            eField.classList.remove("shake");
            pField.classList.remove("shake");
        }, 500);

        eInput.onkeyup = () => { checkEmail(); }; // Calling checkEmail function on email input keyup
        pInput.onkeyup = () => { checkPass(); }; // Calling checkPass function on password input keyup

        function checkEmail() { // checkEmail function
            let pattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/; // Pattern for validating email
            if (!eInput.value.match(pattern)) { // If pattern not matched, add error and remove valid class
                eField.classList.add("error");
                eField.classList.remove("valid");
                let errorTxt = eField.querySelector(".sign-in-error-txt");
                // If email value is not empty, show "please enter a valid email"; otherwise, show "Email can't be blank"
                errorTxt.innerText = (eInput.value !== "") ? "Enter a valid email address" : "Email can't be blank";
            } else { // If pattern matched, remove error and add valid class
                eField.classList.remove("error");
                eField.classList.add("valid");
            }
        }

        function checkPass() { // checkPass function
            if (pInput.value === "") { // If password is empty, add error and remove valid class
                pField.classList.add("error");
                pField.classList.remove("valid");
            } else { // If password is not empty, remove error and add valid class
                pField.classList.remove("error");
                pField.classList.add("valid");
            }
        }

        // If eField and pField don't contain error class, that means user filled details properly
        if (!eField.classList.contains("error") && !pField.classList.contains("error")) {
            window.location.href = form.getAttribute("action"); // Redirecting user to the specified URL inside action attribute of form tag
        }
    };
});
