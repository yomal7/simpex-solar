<?php require APPROOT.'/views/pages/header.php';?>
    <!-- Top Navbar -->
     <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>

<div class="about-container">
    <!-- Hero Section -->
    <section class="hero-section" style="background: url('<?php echo URLROOT; ?>/public/assets/solar-hero.png');">
        <div class="hero-content">
            <h1>About Simpex Solar</h1>
            <!-- <p>Leading the way to a sustainable future with innovative solar solutions</p> -->
        </div>
    </section>

    <!-- Mission Section -->
    <section class="section-container mission-section">
        <div class="section-grid">
            <div class="content-box">
                <h2>Our Mission</h2>
                <p>At Simpex Solar, we are committed to accelerating the global transition to sustainable energy. We believe that solar power isn't just an alternative energy source.It's the future of energy production. Our mission is to make clean, reliable, and affordable solar energy accessible to everyone.</p>
            </div>
            <div class="image-box">
                <img src="<?php echo URLROOT; ?>/public/assets/mission-image.jpg" alt="Solar panels on a rooftop" onerror="this.src='<?php echo URLROOT; ?>/public/assets/simpex-logo.png'">
            </div>
        </div>
    </section>

    <!-- Story Section -->
    <section class="section-container story-section">
        <div class="section-grid reverse">
            <div class="image-box">
                <img src="<?php echo URLROOT; ?>/public/assets/story-image.png" alt="Simpex Solar team" onerror="this.src='<?php echo URLROOT; ?>/public/assets/simpex-logo.png'">
            </div>
            <div class="content-box">
                <h2>Our Story</h2>
                <p>Founded in 2015, Simpex Solar began with a simple idea: to bring the power of solar energy to homes and businesses across the country. What started as a small team of solar enthusiasts has grown into a full-service solar provider, offering everything from residential installations to large-scale commercial projects.</p>
                <p>Over the years, we've helped thousands of customers reduce their carbon footprint and energy costs while contributing to a cleaner environment for future generations.</p>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="section-container values-section">
        <h2 class="section-title">Our Core Values</h2>
        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon">
                    <i class="value-icon-sustainable"></i>
                </div>
                <h3>Sustainability</h3>
                <p>We're committed to promoting environmental sustainability in everything we do.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <i class="value-icon-innovation"></i>
                </div>
                <h3>Innovation</h3>
                <p>We continuously seek new and better ways to harness solar energy.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <i class="value-icon-integrity"></i>
                </div>
                <h3>Integrity</h3>
                <p>We believe in transparent practices and honest communication with our customers.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <i class="value-icon-excellence"></i>
                </div>
                <h3>Excellence</h3>
                <p>We strive for excellence in our products, installations, and customer service.</p>
            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section class="section-container projects-section">
        <h2 class="section-title">Featured Projects</h2>
        <p class="section-subtitle">Some of our most impactful solar installations</p>
        
        <div class="projects-grid">
            <div class="project-card">
                <div class="project-image">
                    <img src="<?php echo URLROOT; ?>/public/assets/project2.jpg" alt="Commercial Solar Project" onerror="this.src='<?php echo URLROOT; ?>/public/assets/simpex-logo.png'">
                </div>
                <div class="project-details">
                    <h3>Westside Commercial Center</h3>
                    <p class="project-location">Colombo, Sri Lanka</p>
                    <p class="project-description">A 500kW commercial rooftop installation providing 75% of the building's power needs.</p>
                    <div class="project-specs">
                        <span class="spec">500kW</span>
                        <span class="spec">1,700 Panels</span>
                        <span class="spec">Commercial</span>
                    </div>
                </div>
            </div>
            
            <div class="project-card">
                <div class="project-image">
                    <img src="<?php echo URLROOT; ?>/public/assets/project3.jpg" alt="Residential Solar Project" onerror="this.src='<?php echo URLROOT; ?>/public/assets/simpex-logo.png'">
                </div>
                <div class="project-details">
                    <h3>Green Acres Housing Complex</h3>
                    <p class="project-location">Kandy, Sri Lanka</p>
                    <p class="project-description">A community solar project serving 50 residential homes with clean energy.</p>
                    <div class="project-specs">
                        <span class="spec">250kW</span>
                        <span class="spec">850 Panels</span>
                        <span class="spec">Residential</span>
                    </div>
                </div>
            </div>
            
            <div class="project-card">
                <div class="project-image">
                    <img src="<?php echo URLROOT; ?>/public/assets/project4.jpg" alt="Industrial Solar Project" onerror="this.src='<?php echo URLROOT; ?>/public/assets/simpex-logo.png'">
                </div>
                <div class="project-details">
                    <h3>Eastern Manufacturing Plant</h3>
                    <p class="project-location">Batticaloa, Sri Lanka</p>
                    <p class="project-description">An industrial installation with battery storage to ensure 24/7 operation.</p>
                    <div class="project-specs">
                        <span class="spec">750kW</span>
                        <span class="spec">2,500 Panels</span>
                        <span class="spec">Industrial</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="projects-cta">
            <a href="<?php echo URLROOT; ?>/pages/projects" class="btn-projects">View All Projects</a>
        </div>
    </section>

    <!-- Team Section -->
    <section class="section-container team-section">
        <h2 class="section-title">Our Leadership Team</h2>
        <div class="team-grid">
            <div class="team-member">
                <div class="member-image">
                    <img src="<?php echo URLROOT; ?>/public/assets/team-ceo.jpg" alt="CEO" onerror="this.src='<?php echo URLROOT; ?>/public/assets/profile.png'">
                </div>
                <h3>John Smith</h3>
                <p class="member-title">CEO & Founder</p>
                <p class="member-bio">With over 15 years of experience in renewable energy, John leads our company with vision and dedication.</p>
            </div>
            <div class="team-member">
                <div class="member-image">
                    <img src="<?php echo URLROOT; ?>/public/assets/team-cto.jpg" alt="CTO" onerror="this.src='<?php echo URLROOT; ?>/public/assets/profile.png'">
                </div>
                <h3>Sarah Johnson</h3>
                <p class="member-title">Chief Technical Officer</p>
                <p class="member-bio">Sarah oversees all technical aspects of our solar solutions, ensuring cutting-edge performance.</p>
            </div>
            <div class="team-member">
                <div class="member-image">
                    <img src="<?php echo URLROOT; ?>/public/assets/team-ops.jpg" alt="Operations Director" onerror="this.src='<?php echo URLROOT; ?>/public/assets/profile.png'">
                </div>
                <h3>David Chen</h3>
                <p class="member-title">Operations Director</p>
                <p class="member-bio">David manages our installation teams and ensures that every project is completed to perfection.</p>
            </div>
        </div>
    </section>

    <!-- Achievement Section -->
    <!-- <section class="section-container achievement-section">
        <h2 class="section-title">Our Achievements</h2>
        <div class="achievements-grid">
            <div class="achievement-card">
                <div class="achievement-number">5,000+</div>
                <p>Solar Systems Installed</p>
            </div>
            <div class="achievement-card">
                <div class="achievement-number">75k</div>
                <p>Tons of CO2 Reduced</p>
            </div>
            <div class="achievement-card">
                <div class="achievement-number">4,500+</div>
                <p>Satisfied Customers</p>
            </div>
            <div class="achievement-card">
                <div class="achievement-number">100k</div>
                <p>MWh Generated</p>
            </div>
        </div>
    </section> -->

    <!-- Contact Section -->
    <section class="section-container contact-section">
        <div class="contact-wrapper">
            <h2>Get in Touch</h2>
            <p>Want to learn more about our solar solutions? Contact us today for a free consultation.</p>
            <a href="<?php echo URLROOT; ?>/pages/contact" class="contact-button">Contact Us</a>
        </div>
    </section>
</div>

<?php require APPROOT.'/views/inc/components/bottomfooter.php'; ?>
<?php require APPROOT.'/views/pages/footer.php'; ?>