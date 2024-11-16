<?php require APPROOT.'/views/packages/header.php';?>
    <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>


    <div class="container">
        <div class="image-section"></div>
        <div class="form-section">
            <h1>Request quotetion</h1>
            <form>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="address">Address:</label>
                <textarea id="address" name="address" required></textarea>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone" required>

                <label for="consumption">Monthly Average Electricity Consumption (Unit):</label>
                <input type="number" id="consumption" name="consumption" required>

                <label for="city">Nearest City:</label>
                <input type="text" id="city" name="city" required>

                <label for="customization">Customizations:</label>
                <textarea id="customization" name="customization"></textarea>

                <button type="submit" onclick="openPopup()">Submit Request</button>
            </form>
        </div>
        <div class="popup" id="popup">
            <img  src="<?php echo URLROOT; ?>/public/assets/tick.png" alt="">
            <h2>Thank you</h2>
            <p>Your details has been successfully submitted. Please check your profile for more details.</p>
            <button type="button" onclick="closePopup()">Ok</button>
        </div>
    </div>


    <?php require APPROOT.'/views/inc/components/bottomfooter.php'; ?>
<?php require APPROOT.'/views/packages/footer.php';?>