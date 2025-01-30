<?php require APPROOT.'/views/packages/header.php';?>
    <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>


    <div class="select-container">
        <div class="header">
            <h1>Find Your Perfect Solar Solution</h1>
            <p>Tell us your requirements and we'll find the best package for you</p>
        </div>

        <div class="selector-form-container">
            <form id="packageForm" action="<?php echo URLROOT; ?>/packages/selectPackage" method="POST">
                <div class="form-group">
                    <label for="price">Budget (Rs)</label>
                    <input name="price" type="number" id="price" min="1000" placeholder="Enter your budget" required 
                        value="<?= isset($data['price']) ? htmlspecialchars($data['price']) : '' ?>">
                </div>

                <div class="form-group">
                    <label for="consumption">Monthly Power Consumption (kWh)</label>
                    <input name="consumption" type="number" id="consumption" placeholder="Enter monthly consumption" required 
                        value="<?= isset($data['consumption']) ? htmlspecialchars($data['consumption']) : '' ?>">
                </div>

                <div class="form-group">
                    <label for="systemType">Preferred System Type</label>
                    <select name="systemType" id="systemType" required>
                        <option value="">Select system type</option>
                        <option value="on-grid" <?= isset($data['systemType']) && $data['systemType'] == 'on-grid' ? 'selected' : '' ?>>On Grid System</option>
                        <option value="off-grid" <?= isset($data['systemType']) && $data['systemType'] == 'off-grid' ? 'selected' : '' ?>>Off Grid System</option>
                        <option value="hybrid" <?= isset($data['systemType']) && $data['systemType'] == 'hybrid' ? 'selected' : '' ?>>Hybrid System</option>
                    </select>
                </div>

                <button type="submit" class="btn">Find My Package</button>
            </form>
        </div>

        <div id="packageDisplay" class="package-display">
            <h2 id="packageName"></h2>
            <img id="packageImage" class="package-image" src="" alt="Package Image">
            <div class="package-price" id="packagePrice"></div>
            <div class="package-id" id="packageId"></div>

            <div class="recommendation-box">
                <h3>Why This Package?</h3>
                <p id="aiReasoning"></p>
            </div>

            <div class="features-section-container">
                <h3>Package Features</h3>
                <ul id="featureList"></ul>
            </div>

            <div class="button-group">
                <a id="getQuoteLink" class="btn-getQuote btn">Get Quote</a>
                <!-- <button id="regenerate" class="btn btn-secondary">Show Another Option</button> -->
            </div>
        </div>
    </div>

<script>
document.getElementById('packageForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(document.getElementById('packageForm'));

    try {
        const response = await fetch("<?php echo URLROOT; ?>/packages/selectPackage", {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        console.log("Response from server:", result);

        if (result.success) {
            if (result.package && result.package.details) {
                displayPackage(result.package);
            } else {
                console.error('Incorrect package data structure:', result);
                alert('Unexpected response format. Please try again.');
            }
        } else {
            alert(result.error || 'Could not find a suitable package. Please try different criteria.');
        }
    } catch (error) {
        console.error('Error fetching package data:', error);
        alert('An error occurred. Please try again later.');
    }
});

// document.getElementById('regenerate').addEventListener('click', async () => {
//     try {
//         const response = await fetch("<php echo URLROOT; ?>/packages/selectPackage", {
//             method: 'POST',
//         });

//         const result = await response.json();
//         if (result.success && result.package) {
//             displayPackage(result.package);
//         } else {
//             alert('No alternative package found. Please try again.');
//         }
//     } catch (error) {
//         console.error('Error fetching new package:', error);
//         alert('An error occurred. Please try again later.');
//     }
// });

function displayPackage(packageData) {
    const display = document.getElementById('packageDisplay');

    if (!packageData || !packageData.details) {
        console.error('Missing required package data:', packageData);
        return;
    }

    document.getElementById('packageName').textContent = packageData.details.title;
    document.getElementById('packagePrice').textContent = `Rs ${parseFloat(packageData.details.price).toLocaleString()}`;
    document.getElementById('packageId').textContent = `Package ID: ${packageData.details.package_id}`;
    document.getElementById('aiReasoning').textContent = packageData.reasoning;

    const featureList = document.getElementById('featureList');
    featureList.innerHTML = '';
    packageData.features.forEach(feature => {
        const li = document.createElement('li');
        li.textContent = feature;
        featureList.appendChild(li);
    });

    document.getElementById('packageImage').src = packageData.image || 'path/to/default-image.jpg';
    document.getElementById('getQuoteLink').href = `<?php echo URLROOT; ?>/packages/packageDetails/${packageData.slug}`;

    display.style.display = 'block';
    display.scrollIntoView({ behavior: 'smooth' });
}
</script>


    <?php require APPROOT.'/views/inc/components/bottomfooter.php'; ?>
<?php require APPROOT.'/views/packages/footer.php';?>
