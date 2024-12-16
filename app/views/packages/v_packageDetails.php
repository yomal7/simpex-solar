<?php require APPROOT.'/views/packages/header.php';?>
    <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>
        
    <div class="wrapper">
        <div class="container">
            <main class="main-content">
                <div class="product-details">
                    <div class="product-image">
                        <img 
                            src="<?php echo URLROOT; ?>/public//<?php echo $data['package']->image ?: 'default-package.jpg'; ?>"
                            alt="<?php echo $data['package']->title; ?>"
                            class="main-image"
                            onerror="this.src='<?php echo URLROOT; ?>/public/assets/product_poster.png'"
                        >
                    </div>
                    <div class="product-info">
                        <h1><?php echo $data['package']->title; ?></h1>
                        <div class="product-description">
                            <p><?php echo $data['package']->description; ?></p>
                        </div>
                        <p class="price">Rs <?php echo $data['package']->price; ?></p>
                        <p class="warrenty">
                            <svg class="warranty-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                            <?php echo $data['package']->warranty_years; ?> Year Warranty
                        </p>
                        <div class="features-section">
                            <ul class="features-list">
                                <?php 
                                if($data['package']->features) {
                                    $features = explode(',', $data['package']->features);
                                    foreach($features as $feature): 
                                ?>
                                    <li class="feature-item">
                                        <span><?php echo htmlspecialchars(trim($feature)); ?></span>
                                    </li>
                                <?php 
                                    endforeach;
                                } else {
                                    echo '<li class="no-features">Features information not available</li>';
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="get-quote-btn-wrapper">
                            <!-- <button class="get-quote-btn" onclick="window.location.href='packageComformation.html'"> -->
                            <!-- <button class="get-quote-btn" onclick="window.location.href='<= URLROOT; ?>/packages/packageConformation'"> -->
                            <button class="get-quote-btn" onclick="handleQuoteClick('<?php echo $data['package']->slug; ?>')">
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
                    <div class="table-wrapper">
                        <div id="description" class="tab-content active">
                            <table class="equipment-table">
                                <thead>
                                    <tr>
                                        <th>Equipment</th>
                                        <th>Details</th>
                                        <th>Quantity</th>
                                        <th>More Info</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($data['equipment']) && !empty($data['equipment'])): ?>
                                        <?php foreach($data['equipment'] as $item): ?>
                                            <tr>
                                                <td class="equipment-name">
                                                    <?php if($item->item_image): ?>
                                                        <img src="<?php echo URLROOT; ?>/public/<?php echo $item->item_image; ?>" 
                                                            alt="<?php echo $item->item_name; ?>" 
                                                            class="equipment-thumb"
                                                            onerror="this.style.display='none'">
                                                    <?php endif; ?>
                                                    <?php echo $item->item_name; ?>
                                                </td>
                                                <td><?php echo $item->item_description ?: 'Details not available'; ?></td>
                                                <td class="text-center"><?php echo $item->quantity; ?></td>
                                                <td class="text-center">
                                                    <?php if(!empty($item->blog_link)): ?>
                                                        <a href="<?php echo $item->blog_link; ?>" target="_blank" class="learn-more-link">
                                                            Learn More
                                                            <svg class="icon" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                                                                <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
                                                            </svg>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="no-link">Details on request</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center">No equipment details available</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>


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
    <script>
        function handleQuoteClick(packageSlug) {
            fetch('<?php echo URLROOT; ?>/users/checkLogin', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.isLoggedIn) {
                    window.location.href = '<?php echo URLROOT; ?>/packages/packageConformation/' + packageSlug;
                } else {
                    // Store the redirect URL in session storage using slug
                    sessionStorage.setItem('redirectAfterLogin', '/packages/packageConformation/' + packageSlug);
                    window.location.href = '<?php echo URLROOT; ?>/users/index';
                }
            });
        }
    </script>
    <?php require APPROOT.'/views/inc/components/bottomfooter.php'; ?>
<?php require APPROOT.'/views/packages/footer.php';?>