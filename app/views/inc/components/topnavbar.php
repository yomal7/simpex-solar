<nav class="nav-bar navbar">
        <div class="wrapper">
          <div class="logo"><a href="<?php echo URLROOT; ?>"><img src="<?php echo URLROOT; ?>/public/assets/simpex-logo.png" alt="SimpEx Logo" class="simpex-logo"></a></div>
          <input type="radio" name="slider" id="menu-btn">
          <input type="radio" name="slider" id="close-btn">
          <ul class="nav-links">
            <label for="close-btn" class="btn close-btn"><i class="fas fa-times"></i></label>
            <li><a href="<?php echo URLROOT; ?>">Home</a></li>
            <li><a href="<?php echo URLROOT; ?>/pages/about">About</a></li>
            <li><a href="<?php echo URLROOT; ?>/packages">Packages</a></li>
            <li><a href="<?php echo URLROOT; ?>/store">Shop</a></li>
 
            <li>
              <a href="<?php echo URLROOT; ?>/blog" class="desktop-item">Blog</a>
              <input type="checkbox" id="showMega">
              <label for="showMega" class="mobile-item">Mega Menu</label>
              <div class="mega-box">
                <div class="content">
                  <div class="row">
                    <img src="<?php echo URLROOT; ?>/public/assets/solar_panels.jpg" alt="Default Image">
                  </div>
                  <div class="row" id="first-row">
                    <header>Get to know</header>
                    <ul class="mega-links" id="first-row-links">
                      <li><a href="<?php echo URLROOT; ?>/blog?category=package-selection">Package selection</a></li>
                      <li><a href="<?php echo URLROOT; ?>/blog?category=equipment">Equipment section</a></li>
                      <li><a href="<?php echo URLROOT; ?>/blog?category=energy-management">Energy managment</a></li>
                      <li><a href="<?php echo URLROOT; ?>/blog?category=user-guide">User guide</a></li>
                      
                    </ul>
                  </div>
                  <div class="row" id="second-row">
                    <header>Project services</header>
                    <ul class="mega-links" id="second-row-links">
                      <li><a href="<?php echo URLROOT; ?>/blog?category=agreements">Agreemnet and documentation</a></li>
                      <li><a href="#">Installation Process</a></li>
                      <li><a href="<?php echo URLROOT; ?>/blog?category=payment-financing">Payment and financing</a></li>
                    </ul>
                  </div>
                  <div class="row" id="last-row">
                    <header>Other sections</header>
                    <ul class="mega-links" id="last-row-links">
                      <li><a href="<?php echo URLROOT; ?>/blog?category=customer-support">Customer support</a></li>
                      <li><a href="<?php echo URLROOT; ?>/blog?category=industry-news">Industry news and innovations</a></li>
                      <li><a href="<?php echo URLROOT; ?>/blog?category=sustainability">Enviromental</a></li>

                    </ul>
                  </div>
                </div>
              </div>
            </li>
            <li><a href="<?php echo URLROOT; ?>/pages/feedback">Feedback</a></li>

              <?php
                $navbarData = getNavbarData();
                $isLoggedIn = $navbarData['isLoggedIn'] ?? false; // Default to false if undefined
                $profilePicture = $navbarData['profile_picture'] ?? 'profile.png'; // Default to a placeholder image
              ?>

              <li id="auth-section">
                <?php if ($isLoggedIn): ?>
                    <div class="profile-section" id="profile-section">
                        <!-- <img src="<php echo $profilePicture; ?>" > -->
                        <img src="<?php echo isset($_SESSION['user_picture']) && !empty($_SESSION['user_picture']) ? URLROOT . '/public/uploads/profile_pictures/' . $_SESSION['user_picture'] : URLROOT . '/public/assets/profile.png'; ?>" alt="Profile" class="profile-pic" id="profile-pic">

                        <div class="profile-dropdown">
                            <ul>
                                <?php if ($_SESSION['role'] === 'customer'): ?>
                                    <li><a href="<?php echo URLROOT; ?>/client/dashboard">Profile</a></li>
                                <?php else: ?>
                                    <li><a href="<?php echo URLROOT . '/' . $_SESSION['role']; ?>/">Profile</a></li>
                                <?php endif; ?>
                                <li><a href="<?php echo URLROOT; ?>/users/logout" id="logout-btn">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?php echo URLROOT; ?>/users/login" class="sign-in-btn" id="sign-in-btn">Sign In</a>
                <?php endif; ?>
            </li>
          </ul>
          <label for="menu-btn" class="btn menu-btn">
            <i class="fas fa-bars" style="color: black !important;"></i>
        </label>
        </div>
      </nav>
