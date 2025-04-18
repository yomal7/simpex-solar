
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
    <a href="<?php echo URLROOT; ?>" class="home-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            <polyline points="9 22 9 12 15 12 15 22"></polyline>
        </svg>
        Back to Home</a>
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
                                    <a href="<?php echo URLROOT; ?>/users/forgotPassword" style="text-decoration: none; color: gray;"><h5>Reset it here</h5></a>       </p>      </div>
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
                                <input type="hidden" name="role" value="customer">
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

        
    </body>

    <script src="<?php echo URLROOT; ?>/js/auth.js"></script>
    </html>

