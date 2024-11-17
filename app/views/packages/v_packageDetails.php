<?php require APPROOT.'/views/packages/header.php';?>
    <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>

        
    <div class="wrapper">
        <div class="container">
            <main class="main-content">
                <div class="product-details">
                    <div class="product-image">
                        <img src="../assets/product_poster.png" alt="40kW 3 Phase Ongrid Solution">
                    </div>
                    <div class="product-info">
                        <h1>40kW 3 Phase Ongrid Solution</h1>
                        <div class="product-description">
                            <p>This package includes 73 JA 550W P Type Panels and a Huawei 40kW Inverter, both with 10-year warranties. Enjoy free insurance for 1 year, 2 complimentary services, and CEB charges included for a seamless solar experience.

                                Save on electricity with this powerful solar solution!</p>
                        </div>
                        <p class="price">Rs 5,463,000.00</p>
                        <p class="warrenty">5 years warrenty</p>
                        <div class="get-quote-btn-wrapper">
                            <!-- <button class="get-quote-btn" onclick="window.location.href='packageComformation.html'"> -->
                            <button class="get-quote-btn" onclick="window.location.href='<?= URLROOT; ?>/packages/packageConformation'">
                                Get quote
                                <svg class="icon" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                    fill-rule="evenodd"
                                    d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm4.28 10.28a.75.75 0 000-1.06l-3-3a.75.75 0 10-1.06 1.06l1.72 1.72H8.25a.75.75 0 000 1.5h5.69l-1.72 1.72a.75.75 0 101.06 1.06l3-3z"
                                    clip-rule="evenodd"
                                    ></path>
                                </svg>
                                </button>
                        </div>
                    </div>
                </div>
                <div class="tabs">
                    <div class="tab-buttons">
                        <button class="tab-button active" data-tab="description">Description</button>
                        <button class="tab-button" data-tab="reviews">Reviews</button>
                    </div>
                    <div id="description" class="tab-content active">
                        <table class="equipment-table">
                            <thead>
                                <tr>
                                    <th>Equipment</th>
                                    <th>Details</th>
                                    <th>Link</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>JA 550W P Type Panels</td>
                                    <td>73 Panels - 10 Years Warranty</td>
                                    <td><a href="https://example.com/ja-550w-panels" target="_blank">Learn More</a></td>
                                </tr>
                                <tr>
                                    <td>Huawei 40kW Inverter</td>
                                    <td>10 Years Warranty</td>
                                    <td><a href="https://example.com/huawei-40kw-inverter" target="_blank">Learn More</a></td>
                                </tr>
                                <tr>
                                    <td>Insurance</td>
                                    <td>Free for 1 year</td>
                                    <td><a href="https://example.com/insurance" target="_blank">Learn More</a></td>
                                </tr>
                                <tr>
                                    <td>Service</td>
                                    <td>Free 2 Services</td>
                                    <td><a href="https://example.com/free-service" target="_blank">Learn More</a></td>
                                </tr>
                                <tr>
                                    <td>CEB Charges</td>
                                    <td>Included</td>
                                    <td><a href="https://example.com/ceb-charges" target="_blank">Learn More</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div id="reviews" class="tab-content">
                        <h2>Customer Reviews</h2>
                        <p>No reviews yet. Be the first to review this product!</p>
                        <!-- Add a form for submitting reviews here -->
                    </div>
                </div>
        
    </div>

    </main>
    </div>
    
    <?php require APPROOT.'/views/inc/components/bottomfooter.php'; ?>
<?php require APPROOT.'/views/packages/footer.php';?>