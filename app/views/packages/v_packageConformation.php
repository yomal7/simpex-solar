<?php require APPROOT.'/views/packages/header.php';?>
    <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>


    <div class="container">
        <div class="image-section"></div>
        <div class="form-section">
            <h1>Request quotation</h1>
            <form action="<?php echo URLROOT; ?>/packages/submitQuotation" method="POST">
                <!-- Add hidden inputs -->
                <input type="hidden" name="package_id" value="<?php echo $data['package']->package_id; ?>">
                <input type="hidden" name="package_type" value="premade">
                
                <label for="address">Address:</label>
                <textarea id="address" name="address" required></textarea>
                
                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" title="Please enter phone number with 10 digits" required>
                
                <label for="monthly_consumption">Monthly Average Electricity Consumption (Unit):</label>
                <input type="number" id="monthly_consumption" name="monthly_consumption" required min="0" max="10000">
                
                <label for="nearest_city">Nearest City:</label>
                <input type="text" id="nearest_city" name="nearest_city" required>
                
                <label for="customizations">Customizations:</label>
                <textarea id="customizations" name="customizations"></textarea>
                
                <button type="submit">Submit Request</button>
            </form>
        </div>
        <div class="popup" id="popup">
            <img src="<?php echo URLROOT; ?>/public/assets/tick.png" alt="">
            <h2>Thank you</h2>
            <p>Your details has been successfully submitted. Please check your profile for more details.</p>
            <button type="button" onclick="closePopup()">Ok</button>
        </div>
    </div>


    <?php require APPROOT.'/views/inc/components/bottomfooter.php'; ?>
<?php require APPROOT.'/views/packages/footer.php';?>