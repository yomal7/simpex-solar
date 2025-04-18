<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
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
            text-align: center;
            letter-spacing: 2px;
            font-size: 20px;
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
            text-align: center;
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
        
        .email-info {
            text-align: center;
            margin-bottom: 20px;
            color: #666;
        }
        
        .resend-link {
            text-align: center;
            margin-top: 10px;
        }
        
        .resend-link a {
            color: #28a745;
            text-decoration: none;
            font-size: 14px;
        }
        
        .resend-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="<?php echo URLROOT; ?>/public/assets/simpex-logo.png" alt="Simpex Solar">
        </div>
        
        <h2>Verify OTP</h2>
        
        <div class="email-info">
            We've sent a 6-digit code to <strong><?php echo $data['email']; ?></strong>
        </div>
        
        <form action="<?php echo URLROOT; ?>/users/verifyResetOTP" method="POST">
            <div class="form-group">
                <input type="text" name="otp" class="form-control <?php echo (!empty($data['otp_err'])) ? 'form-control-error' : ''; ?>" placeholder="Enter 6-digit code" maxlength="6" autocomplete="off">
                <div class="error-text"><?php echo $data['otp_err']; ?></div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn">Verify</button>
            </div>
        </form>
        
        <div class="resend-link">
            <a href="<?php echo URLROOT; ?>/users/resendResetOTP">Didn't receive the code? Resend</a>
        </div>
        
        <div class="links">
            <a href="<?php echo URLROOT; ?>/users/forgotPassword">Back</a>
        </div>
    </div>
</body>
</html>