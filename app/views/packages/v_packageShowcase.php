<?php require APPROOT.'/views/packages/header.php';?>
<!-- <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/packages/packageShowcase.css">   -->

    <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>

<div class="container">
    <h1 class="page-title">Solar Energy Packages</h1>
    
    <div class="mini-navbar">
        <button class="nav-btn active" data-type="all">All Packages</button>
        <button class="nav-btn" data-type="off-grid">Off-Grid</button>
        <button class="nav-btn" data-type="on-grid">On-Grid</button>
        <button class="nav-btn" data-type="hybrid">Hybrid</button>
    </div>

    <div class="info-section">
        <button class="info-toggle-btn" onclick="toggleInfo()">
            No idea what to choose? 🤔 We got you covered! Get to kwnow more about the different types of solar systems.
            <svg class="toggle-icon" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        
        <div class="info-content" id="infoContent">
            <div class="info-grid">
                <div class="info-card">
                    <h3>Off-Grid Systems</h3>
                    <p>Completely independent from the utility grid. Perfect for remote locations or those seeking energy independence. Includes battery storage for 24/7 power supply.</p>
                </div>
                <div class="info-card">
                    <h3>On-Grid Systems</h3>
                    <p>Connected to the utility grid. Excess power is fed back to the grid, potentially earning credits. Most economical option for urban areas.</p>
                </div>
                <div class="info-card">
                    <h3>Hybrid Systems</h3>
                    <p>Best of both worlds. Connected to the grid but includes battery backup for power outages. Ideal for areas with unreliable grid power.</p>
                </div>
            </div>

            <div class="tools-section">
                <div class="video-container">
                    <h3>Watch How Solar Works</h3>
                    <div class="video-wrapper" id="videoWrapper">
                        <!-- Replace VIDEO_ID with your actual YouTube video ID -->
                        <!-- <iframe width="560" height="315" src="about:blank" data-src="https://youtu.be/pajmtcTTCJg?si=DXvpzs2L0WpEs7d6" frameborder="0" allowfullscreen></iframe> -->
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/pajmtcTTCJg?si=uLLyKxO9ITDEsT7t" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                </div>

                <div class="calculator-container">
                    <h3>Energy Calculator</h3>
                    <div class="calculator-form">
                        <label for="monthlyBill">Monthly Electricity Bill (Rs)</label>
                        <input type="number" id="monthlyBill" placeholder="Enter amount" min="0">
                        <button onclick="calculateEnergy()" class="calculate-btn">Calculate</button>
                        <div class="result" id="calculatorResult">
                            <p>Estimated Energy Usage: <span id="energyValue">0</span> kWh</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="packages-grid" id="packagesContainer">
        <?php foreach($data['packages'] as $package): ?>
            <div class="package-card" data-type="<?php echo $package->type; ?>">
                <h2 class="package-name"><?php echo $package->title; ?></h2>
                <img src="<?php echo URLROOT; ?>/public/<?php echo $package->image ?: 'default-package.jpg'; ?>"
                alt="<?php echo $package->title; ?>"
                class="package-image"
                onerror="this.src='<?php echo URLROOT; ?>/public/assets/product_poster.png'">
                <div class="package-price">Rs <?php echo number_format($package->final_price, 2); ?></div>
                <ul class="features-list">
                    <?php
                    if($package->features) {
                        $features = array_slice(explode(',', $package->features), 0, 4); // Limit to 4 features
                        foreach($features as $feature): ?>
                            <li><?php echo htmlspecialchars(trim($feature)); ?></li>
                        <?php endforeach;
                    }
                    ?>
                </ul>
                <button class="learn-more-btn" 
                    onclick="location.href='<?php echo URLROOT; ?>/packages/packageDetails/<?php echo $package->slug; ?>'">
                    Get quote
                    <svg class="icon" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm4.28 10.28a.75.75 0 000-1.06l-3-3a.75.75 0 10-1.06 1.06l1.72 1.72H8.25a.75.75 0 000 1.5h5.69l-1.72 1.72a.75.75 0 101.06 1.06l3-3z"></path>
                    </svg>
                </button>
            </div>
        <?php endforeach; ?>
    </div>
</div>

    <?php require APPROOT.'/views/inc/components/bottomfooter.php'; ?>
<?php require APPROOT.'/views/packages/footer.php';?>