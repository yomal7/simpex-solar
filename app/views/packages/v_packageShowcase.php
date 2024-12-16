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