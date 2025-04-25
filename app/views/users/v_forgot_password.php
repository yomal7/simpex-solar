<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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

        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="<?php echo URLROOT; ?>/public/assets/simpex-logo.png" alt="Simpex Solar">
        </div>
        
        <h2>Forgot Password</h2>
        
        <?php flash('forgot_password_message'); ?>
        
        <p style="text-align: center; margin-bottom: 20px;">Enter your email address to receive a password reset code.</p>
        
        <form action="<?php echo URLROOT; ?>/users/forgotPassword" method="POST">
            <div class="form-group">
                <input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'form-control-error' : ''; ?>" placeholder="Email Address" value="<?php echo $data['email']; ?>">
                <div class="error-text"><?php echo $data['email_err']; ?></div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn">Send Reset Code</button>
            </div>
        </form>
        
        <div class="links">
            <a href="<?php echo URLROOT; ?>/users/index">Back to Login</a>
        </div>
    </div>
</body>
</html>