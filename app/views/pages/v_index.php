<?php require APPROOT.'/views/pages/header.php';?>
    <!-- Top Navbar -->
     <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>


    <section id="home" class="hero">
        <video autoplay muted loop id="hero-video">
            <source src="<?php echo URLROOT; ?>/public/assets/solar-roof1.webm" type="video/mp4">
        </video>
        <div class="overlay"></div>
        <div class="hero-content">
            <h1 class="fade-in">Begin Your <span>Green Energy</span> Journey with <br>
                <span  class="logo-simp">Simp</span><span style="color: gray;" class="logo-ex" >Ex</span>
            </h1>
            <p class="fade-in">Creating a greener future through innovative environmental solutions</p>
            <div class="hero-buttons">
                <button class="cta-button fade-in">Get to Know Us</button>
                <a href="<?php echo URLROOT; ?>/packages"><button class="cta-button secondary fade-in">Get Quote</button></a>
            </div>
        </div>
    </section>


    <section id="about" class="about">
        <div class="about-container">
          <div class="section-header">
            <span class="subtitle">About Us</span>
            <h2>Creating a Sustainable Future</h2>
          </div>
  
          <div class="about-content">
            <div class="about-image">
              <div class="image-wrapper">
                <img src="<?php echo URLROOT; ?>/public/assets/landing-page-solar.jpg" alt="Sustainability" class="main-image">
                <!-- <div class="experience-badge">
                  <span>10+</span>
                  <p>Years of Excellence</p>
                </div> -->
              </div>
            </div>
  
            <div class="about-text">
              <h3>Pioneering Green Solutions</h3>
              <p>We're dedicated to creating sustainable solutions that protect our planet and enhance our future. Through innovation and commitment, we're making a difference.</p>
              
              <div class="achievement-grid">
                <div class="achievement-card" data-aos="fade-up">
                  <i class="fas fa-tree"></i>
                  <div class="counter" data-target="100">0</div>
                  <p>Successful Projects</p>
                </div>
                
                <div class="achievement-card" data-aos="fade-up" data-aos-delay="100">
                  <i class="fas fa-solar-panel"></i>
                  <div class="counter" data-target="150">0</div>
                  <p>Satisfied Customers</p>
                </div>
                
                <div class="achievement-card" data-aos="fade-up" data-aos-delay="200">
                  <i class="fas fa-globe-americas"></i>
                  <div class="counter" data-target="5">0</div>
                  <p>Branches</p>
                </div>
              </div>
  
              <div class="features">
                <div class="feature">
                  <i class="fas fa-check-circle"></i>
                  <span>Sustainable Development</span>
                </div>
                <div class="feature">
                  <i class="fas fa-check-circle"></i>
                  <span>Eco-friendly Solutions</span>
                </div>
                <div class="feature">
                  <i class="fas fa-check-circle"></i>
                  <span>Global Impact</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

        <section class="banner-section">
          <div class="banner-background"></div>
          <div class="banner-overlay"></div>
          
          <h2 class="section-title">Featured Projects</h2>
          
          <div class="project-slider">
              <div class="slider-track">
                  <div class="project-slide">
                      <div class="project-card">
                          <img src="<?php echo URLROOT; ?>/assets/project1.jpeg" alt="Project 1" class="project-image">
                          <div class="project-info">
                              <h3 class="project-title">Solar Innovation Park</h3>
                          </div>
                      </div>
                  </div>

                  <div class="project-slide">
                      <div class="project-card">
                          <img src="<?php echo URLROOT; ?>/assets/project2.jpg" alt="Project 2" class="project-image">
                          <div class="project-info">
                              <h3 class="project-title">Coastal Wind Farm</h3>
                          </div>
                      </div>
                  </div>

                  <div class="project-slide">
                      <div class="project-card">
                          <img src="<?php echo URLROOT; ?>/assets/project3.jpg" alt="Project 3" class="project-image">
                          <div class="project-info">
                              <h3 class="project-title">Green Office Complex</h3>
                          </div>
                      </div>
                  </div>

                  <div class="project-slide">
                      <div class="project-card">
                          <img src="<?php echo URLROOT; ?>/assets/project4.jpg" alt="Project 4" class="project-image">
                          <div class="project-info">
                              <h3 class="project-title">10kW Energy Plant</h3>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="slider-nav">
                  <button class="nav-button prev">‹</button>
                  <button class="nav-button next">›</button>
              </div>
          </div>
      </section>
        
      <!-- <section id="services" class="services">
          <h2>Our Services</h2>
          <div class="services-grid">
              <div class="service-card">
                  <i class="fas fa-leaf"></i>
                  <h3>Eco Consulting</h3>
                  <p>Expert guidance for sustainable soar solutions</p>
              </div>
              <div class="service-card">
                  <i class="fas fa-lightbulb"></i>
                  <h3>Empowering nation</h3>
                  <p>Sustainability initiatives for a greener future</p>
              </div>
              <div class="service-card">
                  <i class="fas fa-solar-panel"></i>
                  <h3>Hassle-free Solar project</h3>
                  <p>Effortless installation and maintenance</p>
              </div>
          </div>
    </section> -->

    <!-- <section id="products" class="products">
        <h2>Featured Products</h2>
        <div class="product-slider">
            <div class="product-card">
                <img src="https://images.unsplash.com/photo-1618477388954-7852f32655ec" alt="Product 1">
                <h3>Eco-friendly Package</h3>
                <p>Biodegradable packaging solutions</p>
                <button>Learn More</button>
            </div>
            <div class="product-card">
                <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09" alt="Product 2">
                <h3>Solar Kit</h3>
                <p>Complete home solar solutions</p>
                <button>Learn More</button>
            </div>
            <div class="product-card">
                <img src="https://images.unsplash.com/photo-1535546204504-586398ee6677" alt="Product 3">
                <h3>Water Filter</h3>
                <p>Advanced water filtration systems</p>
                <button>Learn More</button>
            </div>
        </div>
    </section> -->

    <section class="testimonials">
        <h2>What Our Customers Say</h2>
        <div class="testimonial-container">
            <div class="testimonial">
                <div class="quote">"Outstanding service and remarkable results. Helped us reduce our carbon footprint by 40%."</div>
                <div class="author">- John Smith, CEO</div>
            </div>
            <div class="testimonial">
                <div class="quote">"The team's expertise in sustainable solutions is unmatched. Highly recommended!"</div>
                <div class="author">- Sarah Johnson, Director</div>
            </div>
        </div>
    </section>

    <?php require APPROOT.'/views/inc/components/bottomfooter.php';?>

<?php require APPROOT.'/views/pages/footer.php';?>


