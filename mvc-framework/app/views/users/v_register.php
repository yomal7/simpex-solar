<?php require APPROOT.'/views/inc/header.php';?>
    <!-- Top Navbar -->
     <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>
    <h1>User sign up</h1>

    <div class="form-container">
        <div class="form-header">
            <center><h1>User sign up</h1></center>
            <br>
            <p><b>Please fill the form to register</b></p>
        </div>
        <form action="" method="POST">
            
            <!-- name -->
            <div class="form-input-title">Name</div>
            <input type="text" name="name" id="name" class="name">
            <span class="form-invalid">ERROR</span>
            
            <!-- email -->
            <div class="form-input-title">Email</div>
            <input type="text" name="email" id="email" class="email">
            <span class="form-invalid"></span>
            
            <!-- password-->
            <div class="form-input-title">Password</div>
            <input type="password" name="password" id="password" class="password">
            <span class="form-invalid"></span>
            
            <!-- name -->
            <div class="form-input-title">Conform password</div>
            <input type="text" name="conform_password" id="conform_password" class="conform_password">
            <span class="form-invalid"></span>

            <br>
            <br>
            <input type="submit" value="Register" class="form-btn">

        </form>
    </div>
<?php require APPROOT.'/views/inc/footer.php';?>