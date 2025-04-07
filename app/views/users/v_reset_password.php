<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .container {
            width: 100%;
            max-width: 400px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .logo img {
            max-width: 180px;
        }
        
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border 0.3s;
        }
        
        .form-control:focus {
            border-color: #28a745;
            outline: none;
        }
        
        .form-control-error {
            border-color: #dc3545;
        }
        
        .error-text {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
        }
        
        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background-color: #28a745;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #218838;
        }
        
        .links {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
        
        .links a {
            color: #28a745;
            text-decoration: none;
        }
        
        .links a:hover {
            text-decoration: underline;
        }
        
        .password-requirements {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="<?php echo URLROOT; ?>/public/assets/simpex-logo.png" alt="Simpex Solar">
        </div>
        
        <h2>Reset Password</h2>
        
        <form action="<?php echo URLROOT; ?>/users/resetPassword" method="POST">
            <div class="form-group">
                <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'form-control-error' : ''; ?>" placeholder="New Password">
                <div class="error-text"><?php echo $data['password_err']; ?></div>
                <div class="password-requirements">Password must be at least 6 characters long</div>
            </div>
            
            <div class="form-group">
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'form-control-error' : ''; ?>" placeholder="Confirm New Password">
                <div class="error-text"><?php echo $data['confirm_password_err']; ?></div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn">Reset Password</button>
            </div>
        </form>
    </div>
</body>
</html>