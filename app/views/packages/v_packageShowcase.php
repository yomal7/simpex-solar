<?php require APPROOT.'/views/packages/header.php';?>
    <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>

<div class="container">
        <h1>Solar Energy Packages</h1>
        
        <div class="mini-navbar">
            <button class="nav-btn active" data-type="all">All Packages</button>
            <button class="nav-btn" data-type="off-grid">Off-Grid</button>
            <button class="nav-btn" data-type="on-grid">On-Grid</button>
            <button class="nav-btn" data-type="hybrid">Hybrid</button>
        </div>

        <div class="packages-grid" id="packagesContainer">
            <!-- Packages will be dynamically inserted here -->
        </div>
</div>
    <?php require APPROOT.'/views/inc/components/bottomfooter.php'; ?>
<?php require APPROOT.'/views/packages/footer.php';?>