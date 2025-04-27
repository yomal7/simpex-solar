<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo SITENAME; ?></title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <!-- <link rel="stylesheet" href="<?php echo URLROOT; ?>/app/views/inc/components/style.css"> -->
        <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/packages.css">
        <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/packages/packageSelector.css">

    </head>
    <body>
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
                    class="input-field" min="0">
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