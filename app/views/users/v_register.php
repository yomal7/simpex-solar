<?php require APPROOT.'/views/inc/header.php';?>
    <div class="form-container">
        <div class="form-header">
            <center><h1>User Registration</h1></center>
            <br>
            <p><b>Please fill in the form to register</b></p>
        </div>
        <form action="<?php echo URLROOT; ?>/users/register" method="POST">
            <!-- name -->
            <div class="form-input-title">Name</div>
            <input type="text" name="name" class="name" value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>">
            <span class="form-invalid"><?php echo isset($data['name_err']) ? $data['name_err'] : ''; ?></span>
           
            <!-- email -->
            <div class="form-input-title">Email</div>
            <input type="email" name="email" class="email" value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>">
            <span class="form-invalid"><?php echo isset($data['email_err']) ? $data['email_err'] : ''; ?></span>
           
            <!-- password-->
            <div class="form-input-title">Password</div>
            <input type="password" name="password" class="password">
            <span class="form-invalid"><?php echo isset($data['password_err']) ? $data['password_err'] : ''; ?></span>

            <!-- confirm password -->
            <div class="form-input-title">Confirm Password</div>
            <input type="password" name="confirm_password" class="password">
            <span class="form-invalid"><?php echo isset($data['confirm_password_err']) ? $data['confirm_password_err'] : ''; ?></span>
       
            <br>
            <input type="submit" value="Register" class="form-btn">
        </form>
    </div>
<?php require APPROOT.'/views/inc/footer.php';?>