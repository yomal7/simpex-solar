
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Sign in & Sign up Form</title>
        <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/auth.css">
    </head>
    <body>
    <main class="<?php echo ($data['mode'] === 'signup') ? 'sign-up-mode' : ''; ?>">
            <div class="box">
                <div class="inner-box">
                    <div class="forms-wrap">
                        <!-- Sign In Form -->
                        <form action="<?php echo URLROOT; ?>/users/auth" method="POST" autocomplete="off" class="sign-in-form">
                            <input type="hidden" name="mode" value="signin">
                            
                            <div class="logo">
                                <img src="<?php echo URLROOT; ?>/public/assets/simpex-logo.png" alt="simpex" />
                            </div>

                            <div class="heading">
                                <h2>Welcome Back</h2>
                                <h6>Not registered yet?</h6>
                                <a href="#" class="toggle">Sign up</a>
                            </div>

                            <div class="actual-form">
                                <div class="input-wrap">
                                    <input
                                        type="text"
                                        name="email"
                                        minlength="4"
                                        class="input-field email"
                                        id="email"
                                        value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>"
                                        autocomplete="off"
                                        required
                                        placeholder="Email"
                                    />
                                    
                                </div>
                                <span class="form-invalid" style="color: red; font-size: 0.8em;"><?php echo isset($data['email_err']) ? $data['email_err'] : ''; ?></span>

                                <div class="input-wrap">
                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        minlength="4"
                                        class="input-field password"
                                        autocomplete="off"
                                        placeholder="Password"
                                        required
                                    />
                                    
                                </div>
                                <span class="form-invalid" style="color: red; font-size: 0.8em;"><?php echo isset($data['password_err']) ? $data['password_err'] : ''; ?></span>

                                <input type="submit" value="Sign In" class="sign-btn" />

                                <p class="text">
                                    Forgotten your password or your login details?
                                    <a href="#">Get help</a> signing in
                                </p>
                            </div>
                        </form>

                        <?php flash('reg_flash'); ?>

                        <!-- Sign Up Form -->
                        <form action="<?php echo URLROOT; ?>/users/auth" method="POST" autocomplete="off" class="sign-up-form">
                            <input type="hidden" name="mode" value="signup">
                            
                            <div class="logo">
                                <img  src="<?php echo URLROOT; ?>/public/assets/simpex-logo.png" alt="easyclass" />
                            </div>

                            <div class="heading">
                                <h2>Get Started</h2>
                                <h6>Already have an account?</h6>
                                <a href="#" class="toggle">Sign in</a>
                            </div>

                            <div class="actual-form">
                                <div class="input-wrap">
                                    <input
                                        type="text"
                                        name="name"
                                        minlength="4"
                                        class="input-field name"
                                        value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>"
                                        autocomplete="off"
                                        placeholder="Name"
                                        required
                                    />
                                    
                                </div>
                                <span class="form-invalid" style="color: red; font-size: 0.8em;"><?php echo isset($data['name_err']) ? $data['name_err'] : ''; ?></span>

                                <div class="input-wrap">
                                    <input
                                        type="email"
                                        name="email"
                                        class="input-field"
                                        value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>"
                                        autocomplete="off"
                                        placeholder="Email"
                                        required
                                    />
                                    
                                </div>
                                <span class="form-invalid" style="color: red; font-size: 0.8em;"><?php echo isset($data['email_err']) ? $data['email_err'] : ''; ?></span>

                                <div class="input-wrap">
                                    <input
                                        type="password"
                                        name="password"
                                        minlength="4"
                                        class="input-field"
                                        autocomplete="off"
                                        placeholder="Password"
                                        required
                                    />
                                    
                                </div>
                                <span class="form-invalid" style="color: red; font-size: 0.8em;"><?php echo isset($data['password_err']) ? $data['password_err'] : ''; ?></span>

                                <div class="input-wrap">
                                    <input
                                        type="password"
                                        name="confirm_password"
                                        minlength="4"
                                        class="input-field"
                                        autocomplete="off"
                                        placeholder="Confirm Password"
                                        required
                                    />
                                    
                                </div>
                                <span class="form-invalid" style="color: red; font-size: 0.8em;"><?php echo isset($data['confirm_password_err']) ? $data['confirm_password_err'] : ''; ?></span>

                                <input type="submit" value="Sign Up" class="sign-btn" />

                                <p class="text">
                                    By signing up, I agree to the
                                    <a href="#">Terms of Services</a> and
                                    <a href="#">Privacy Policy</a>
                                </p>
                            </div>
                        </form>
                    </div>

                    <div class="carousel">
                        
                        <div class="text-slider">
                            <div class="images-wrapper">
                                <img  src="<?php echo URLROOT; ?>/public/assets/image1.png"  class="image img-1 show" alt="" />
                                <img src="<?php echo URLROOT; ?>/public/assets/image2.png" class="image img-2" alt="" />
                                <img src="<?php echo URLROOT; ?>/public/assets/image3.png" class="image img-3" alt="" />
                            </div>

                            <div class="text-wrap">
                                <div class="text-group">
                                <h2>Choose the Perfect Solar Package</h2>
                                <h2>Customize Your Solar Solution</h2>
                                <h2>Get Expert Support Every Step</h2>
                                </div>
                            </div>

                            <div class="bullets">
                                <span class="active" data-value="1"></span>
                                <span data-value="2"></span>
                                <span data-value="3"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Javascript file -->

        <script>
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
    </script>
    </body>
    </html>

