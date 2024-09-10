<?php require APPROOT.'/views/inc/header.php';?>
    <!-- Top Navbar -->
     <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>
    <h1>User sign up</h1>

    <div class="form-container">
        <div class="form-header">
            <center><h1>User Login</h1></center>
            <br>
            <p><b>Please fill the correct credentials to login</b></p>
        </div>
        <form action="" method="POST">
            
            <!-- email -->
            <div class="form-input-title">Email</div>
            <input type="text" name="email" id="email" class="email">
            <span class="form-invalid"></span>
            
            <!-- password-->
            <div class="form-input-title">Password</div>
            <input type="password" name="password" id="password" class="password">
            <span class="form-invalid"></span>
        

            <br>
            <br>
            <input type="submit" value="Register" class="form-btn">

        </form>
    </div>
<?php require APPROOT.'/views/inc/footer.php';?>