<?php require APPROOT.'/views/packages/header.php'; ?>
<?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>

<div class="select-container">
    <div class="header">
        <h1>Find Your Perfect Solar Solution</h1>
        <p>Tell us your requirements and we'll show you the best packages for your energy needs</p>
    </div>
    
    <?php if (!empty($data['error'])): ?>
        <div class="error-message">
            <i class="error-icon"></i>
            <?php echo $data['error']; ?>
        </div>
    <?php endif; ?>
    
    <div class="selector-form-container">
        <form id="packageForm" action="<?php echo URLROOT; ?>/packages/selectPackage" method="POST">
            <div class="form-group">
                <label for="price">Budget (Rs)</label>
                <input name="price" type="number" id="price" min="1000" placeholder="Enter your budget" required 
                    value="<?= isset($data['price']) ? htmlspecialchars($data['price']) : '' ?>"
                    class="input-field">
            </div>
            
            <div class="form-group">
                <label for="consumption">Monthly Power Consumption (kWh)</label>
                <input name="consumption" type="number" id="consumption" placeholder="Enter monthly consumption" required 
                    value="<?= isset($data['consumption']) ? htmlspecialchars($data['consumption']) : '' ?>"
                    class="input-field">
            </div>
            
            <div class="form-group">
                <label for="systemType">Preferred System Type</label>
                <select name="systemType" id="systemType" required class="select-field">
                    <option value="">Select system type</option>
                    <option value="on-grid" <?= isset($data['systemType']) && $data['systemType'] == 'on-grid' ? 'selected' : '' ?>>On Grid System</option>
                    <option value="off-grid" <?= isset($data['systemType']) && $data['systemType'] == 'off-grid' ? 'selected' : '' ?>>Off Grid System</option>
                    <option value="hybrid" <?= isset($data['systemType']) && $data['systemType'] == 'hybrid' ? 'selected' : '' ?>>Hybrid System</option>
                </select>
            </div>
            
            <button type="submit" class="btn">Find Matching Packages</button>
        </form>
    </div>
    
    <?php if (isset($data['packages']) && !empty($data['packages'])): ?>
        <h2 class="compare-heading">Recommended Packages</h2>
        <div id="packagesDisplay" class="packages-grid">
            <?php foreach ($data['packages'] as $index => $pkgData): ?>
                <div class="package-display" style="animation-delay: <?= 0.1 * $index ?>s">
                    <?php if ($index === 0): ?>
                        <div class="package-badge">
                            <span class="badge-text">Best Match</span>
                        </div>
                    <?php endif; ?>
                    
                    <h2><?php echo htmlspecialchars($pkgData['package']->title); ?></h2>
                    
                    <div class="image-container">
                        <img class="package-image" src="<?php echo htmlspecialchars($pkgData['imageUrl']); ?>" 
                            alt="<?php echo htmlspecialchars($pkgData['package']->title); ?> Package">
                    </div>
                    
                    <div class="package-price">
                        Rs <?php echo number_format(floatval($pkgData['package']->price)); ?>
                    </div>
                    
                    <div class="recommendation-box">
                        <h3>Why This Package?</h3>
                        <p><?php echo htmlspecialchars($pkgData['reasoning']); ?></p>
                    </div>
                    
                    <div class="features-section-container">
                        <h3>Key Features</h3>
                        <ul class="features-list">
                            <?php 
                            // Show only top 3 features to save space
                            $topFeatures = array_slice($pkgData['features'], 0, 3);
                            foreach ($topFeatures as $feature): 
                            ?>
                                <li class="feature-item"><?php echo htmlspecialchars($feature); ?></li>
                            <?php endforeach; ?>
                            
                            <?php if (count($pkgData['features']) > 3): ?>
                                <li class="feature-item">+ <?php echo count($pkgData['features']) - 3; ?> more features</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    
                    <div class="button-group">
                        <a href="<?php echo URLROOT; ?>/packages/packageDetails/<?php echo htmlspecialchars($pkgData['slug']); ?>" 
                           class="btn btn-getQuote">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Compare Table -->
        <h2 class="compare-heading">Package Comparison</h2>
        <div class="compare-table-container">
            <table class="compare-table">
                <thead>
                    <tr>
                        <th>Feature</th>
                        <?php foreach ($data['packages'] as $pkgData): ?>
                            <th><?php echo htmlspecialchars($pkgData['package']->title); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <!-- Price Row -->
                    <tr>
                        <td><strong>Price</strong></td>
                        <?php foreach ($data['packages'] as $pkgData): ?>
                            <td>Rs <?php echo number_format(floatval($pkgData['package']->price)); ?></td>
                        <?php endforeach; ?>
                    </tr>
                    
                    <!-- Power Rating Row (if available) -->
                    <tr>
                        <td><strong>Power Rating</strong></td>
                        <?php foreach ($data['packages'] as $pkgData): ?>
                            <?php 
                            $title = $pkgData['package']->title;
                            preg_match('/(\d+(\.\d+)?)kW/i', $title, $matches);
                            $kWRating = !empty($matches) ? $matches[1] : 'N/A';
                            ?>
                            <td><?php echo $kWRating; ?> kW</td>
                        <?php endforeach; ?>
                    </tr>
                    
                    <!-- System Type Row -->
                    <tr>
                        <td><strong>System Type</strong></td>
                        <?php foreach ($data['packages'] as $pkgData): ?>
                            <td><?php echo ucfirst(htmlspecialchars($pkgData['package']->type)); ?></td>
                        <?php endforeach; ?>
                    </tr>
                    
                    <!-- Common Features - Dynamically generated -->
                    <?php
                    // Get common feature names across all packages
                    $allFeatures = [];
                    foreach ($data['packages'] as $pkgData) {
                        foreach ($pkgData['features'] as $feature) {
                            if (!in_array($feature, $allFeatures)) {
                                $allFeatures[] = $feature;
                            }
                        }
                    }
                    
                    // Show top features (limit to 5 to keep table manageable)
                    $topAllFeatures = array_slice($allFeatures, 0, 5);
                    
                    foreach ($topAllFeatures as $feature):
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($feature); ?></td>
                            <?php foreach ($data['packages'] as $pkgData): ?>
                                <td>
                                    <?php echo in_array($feature, $pkgData['features']) ? 
                                        '<span style="color: var(--primary);">✓</span>' : 
                                        '<span style="color: var(--gray);">✗</span>'; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                    
                    <!-- Actions Row -->
                    <tr>
                        <td><strong>Actions</strong></td>
                        <?php foreach ($data['packages'] as $pkgData): ?>
                            <td>
                                <a href="<?php echo URLROOT; ?>/packages/packageDetails/<?php echo htmlspecialchars($pkgData['slug']); ?>" 
                                   class="btn" style="padding: 0.5rem 1rem; font-size: 0.9rem;">View Details</a>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    
</div>

<!-- Enhanced JavaScript for animations and smooth interactions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add active class to form fields when focused
    const formFields = document.querySelectorAll('.input-field, .select-field');
    
    formFields.forEach(field => {
        field.addEventListener('focus', function() {
            this.parentElement.classList.add('active');
        });
        
        field.addEventListener('blur', function() {
            if (this.value === '') {
                this.parentElement.classList.remove('active');
            }
        });
        
        // Initialize fields that already have values
        if (field.value !== '') {
            field.parentElement.classList.add('active');
        }
    });
    
    // Smooth scroll to results if they exist
    <?php if ((isset($data['packages']) && !empty($data['packages'])) || (isset($data['package']) && $data['package'])): ?>
    setTimeout(function() {
        const targetElement = document.getElementById('packagesDisplay') || document.getElementById('packageDisplay');
        if (targetElement) {
            targetElement.scrollIntoView({ 
                behavior: 'smooth',
                block: 'start'
            });
        }
    }, 500);
    <?php endif; ?>
    
    // Add ripple effect to buttons
    const buttons = document.querySelectorAll('.btn');
    
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (this.tagName.toLowerCase() === 'a') return; // Skip for anchor tags
            
            const x = e.clientX - e.target.getBoundingClientRect().left;
            const y = e.clientY - e.target.getBoundingClientRect().top;
            
            const ripple = document.createElement('span');
            ripple.className = 'ripple';
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;
            
            this.appendChild(ripple);
            
            setTimeout(function() {
                ripple.remove();
            }, 600);
        });
    });
});
</script>

<?php require APPROOT.'/views/inc/components/bottomfooter.php'; ?>
<?php require APPROOT.'/views/packages/footer.php'; ?>