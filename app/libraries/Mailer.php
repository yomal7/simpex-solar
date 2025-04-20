<?php
// libraries/Mailer.php

// Import the PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    private $mailer;
    
    public function __construct() {
        // Require PHPMailer
        require_once APPROOT . '/libraries/PHPMailer/src/Exception.php';
        require_once APPROOT . '/libraries/PHPMailer/src/PHPMailer.php';
        require_once APPROOT . '/libraries/PHPMailer/src/SMTP.php';
        
        // Create a new PHPMailer instance
        $this->mailer = new PHPMailer(true);
        
        // Configure SMTP
        $this->mailer->isSMTP();
        $this->mailer->Host = SMTP_HOST;
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = SMTP_USER; 
        $this->mailer->Password = SMTP_PASS; 
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = 587;
        $this->mailer->setFrom(SMTP_USER, SITE_NAME);
        
    }
    
    public function sendOTP($email, $name, $otp) {
        try {
            // Get the logo path
            $logoPath = dirname(APPROOT) . '/public/assets/simpex-logo.png';

            // Reset recipients
            $this->mailer->clearAddresses();
            
            // Set email parameters
            $this->mailer->addAddress($email, $name);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Email Verification OTP';

            $logoTag = '';
            if (file_exists($logoPath)) {
                $this->mailer->addEmbeddedImage($logoPath, 'logo_id', 'simpex-logo.png');
                $logoTag = '<img src="cid:logo_id" alt="Simpex Solar" class="logo">';
                error_log('Logo embedded with ID: logo_id');
            } else {
                error_log('Logo file not found at: ' . $logoPath);
            }
            
            // Email body
            $body = "
            <html>
            <head>
                <style>
                    body {
                        font-family: 'Segoe UI', Arial, sans-serif;
                        line-height: 1.6;
                        color: #333333;
                        background-color: #f7f7f7;
                        margin: 0;
                        padding: 0;
                    }
                    .container {
                        max-width: 600px;
                        margin: 20px auto;
                        background-color: #ffffff;
                        border-radius: 8px;
                        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                        overflow: hidden;
                    }
                    .header {
                        background-color: #4CAF50;
                        padding: 20px;
                        text-align: center;
                        color: white;
                    }
                    .logo {
                        max-height: 60px;
                        margin-bottom: 10px;
                    }
                    .content {
                        padding: 30px;
                        background-color: #ffffff;
                    }
                    .otp {
                        font-size: 32px;
                        font-weight: bold;
                        text-align: center;
                        padding: 15px;
                        margin: 25px auto;
                        background-color: #e8f5e9;
                        border: 1px dashed #4CAF50;
                        border-radius: 8px;
                        color: #2E7D32;
                        letter-spacing: 5px;
                        width: 50%;
                    }
                    h2 {
                        color: #ffffff;
                        margin: 0;
                        font-size: 24px;
                    }
                    p {
                        margin-bottom: 16px;
                        color: #555555;
                    }
                    .footer {
                        background-color: #f5f5f5;
                        padding: 15px;
                        text-align: center;
                        font-size: 12px;
                        color: #777777;
                        border-top: 1px solid #eeeeee;
                    }
                    .highlight {
                        font-weight: bold;
                        color: #4CAF50;
                    }
                    .button {
                        display: inline-block;
                        background-color: #4CAF50;
                        color: white;
                        text-decoration: none;
                        padding: 10px 20px;
                        border-radius: 4px;
                        margin-top: 15px;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        $logoTag
                        <h2>Email Verification</h2>
                    </div>
                    <div class='content'>
                        <p>Hello <span class='highlight'>$name</span>,</p>
                        <p>Thank you for registering with Simpex Solar. To complete your registration, please use the following One-Time Password (OTP) to verify your email address:</p>
                        <div class='otp'>$otp</div>
                        <p>This OTP will expire in <span class='highlight'>15 minutes</span>.</p>
                        <p>If you did not request this verification, please ignore this email.</p>
                        <p>Regards,<br><span class='highlight'>" . SITE_NAME . " Team</span></p>
                    </div>
                    <div class='footer'>
                        &copy; " . date('Y') . " " . SITE_NAME . ". All rights reserved.<br>
                        Providing reliable solar energy solutions for a sustainable future.
                    </div>
                </div>
            </body>
            </html>";
            
            $this->mailer->Body = $body;
            $this->mailer->AltBody = "Hello $name, Your OTP for email verification is: $otp. This OTP will expire in 15 minutes.";
            
            // Send email
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            // Log the error message
            error_log('Mailer Error: ' . $this->mailer->ErrorInfo);
            return false;
        }
    }

    public function sendPasswordResetOTP($email, $name, $otp) {


        try {

            $logoPath = dirname(APPROOT) . '/public/assets/simpex-logo.png';

            // Reset recipients
            $this->mailer->clearAddresses();
            
            // Set email parameters
            $this->mailer->addAddress($email, $name);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Password Reset Code';
            

            // Add logo as embedded image if the file exists
            $logoTag = '';
            if (file_exists($logoPath)) {
                $this->mailer->addEmbeddedImage($logoPath, 'logo_id', 'simpex-logo.png');
                $logoTag = '<img src="cid:logo_id" alt="Simpex Solar" class="logo">';
                error_log('Logo embedded with ID: logo_id');
            } else {
                error_log('Logo file not found at: ' . $logoPath);
            }


            // Email body
            $body = "
            <html>
            <head>
                <style>
                    body {
                        font-family: 'Segoe UI', Arial, sans-serif;
                        line-height: 1.6;
                        color: #333333;
                        background-color: #f7f7f7;
                        margin: 0;
                        padding: 0;
                    }
                    .container {
                        max-width: 600px;
                        margin: 20px auto;
                        background-color: #ffffff;
                        border-radius: 8px;
                        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                        overflow: hidden;
                    }
                    .header {
                        background-color:rgb(92, 215, 96);
                        padding: 10px;
                        text-align: center;
                        color: white;
                    }
                    .logo {
                        max-height: 80px;
                        margin-bottom: 2px;
                    }
                    .content {
                        padding: 30px;
                        background-color: #ffffff;
                    }
                    .otp {
                        font-size: 32px;
                        font-weight: bold;
                        text-align: center;
                        padding: 15px;
                        margin: 25px auto;
                        background-color: #e8f5e9;
                        border: 1px dashed #4CAF50;
                        border-radius: 8px;
                        color: #2E7D32;
                        letter-spacing: 5px;
                        width: 50%;
                    }
                    h2 {
                        color: #ffffff;
                        margin: 0;
                        font-size: 24px;
                    }
                    p {
                        margin-bottom: 16px;
                        color: #555555;
                    }
                    .footer {
                        background-color: #f5f5f5;
                        padding: 15px;
                        text-align: center;
                        font-size: 12px;
                        color: #777777;
                        border-top: 1px solid #eeeeee;
                    }
                    .highlight {
                        font-weight: bold;
                        color: #4CAF50;
                    }
                    .button {
                        display: inline-block;
                        background-color: #4CAF50;
                        color: white;
                        text-decoration: none;
                        padding: 10px 20px;
                        border-radius: 4px;
                        margin-top: 15px;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        $logoTag
                        <h2>Password Reset</h2>
                    </div>
                    <div class='content'>
                        <p>Hello <span class='highlight'>$name</span>,</p>
                        <p>We received a request to reset your password. Use the verification code below to reset your password:</p>
                        <div class='otp'>$otp</div>
                        <p>This code will expire in <span class='highlight'>15 minutes</span>.</p>
                        <p>If you did not request a password reset, please ignore this email or contact our support team if you have concerns.</p>
                        <p>Regards,<br><span class='highlight'>" . SITE_NAME . " Team</span></p>
                    </div>
                    <div class='footer'>
                        &copy; " . date('Y') . " " . SITE_NAME . ". All rights reserved.<br>
                        Providing reliable solar energy solutions for a sustainable future.
                    </div>
                </div>
            </body>
            </html>";
            
            $this->mailer->Body = $body;
            $this->mailer->AltBody = "Hello $name, Your password reset code is: $otp. This code will expire in 15 minutes.";
            
            // Send email
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            // Log the error message
            error_log('Mailer Error: ' . $this->mailer->ErrorInfo);
            return false;
        }
    }

    public function sendFeedbackResponse($email, $name, $subject, $message) {
        try {
            // Get the logo path
            $logoPath = dirname(APPROOT) . '/public/assets/simpex-logo.png';
    
            // Reset recipients
            $this->mailer->clearAddresses();
            
            // Set email parameters
            $this->mailer->addAddress($email, $name);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            
            // Add logo as embedded image if the file exists
            $logoTag = '';
            if (file_exists($logoPath)) {
                $this->mailer->addEmbeddedImage($logoPath, 'logo_id', 'simpex-logo.png');
                $logoTag = '<img src="cid:logo_id" alt="Simpex Solar" class="logo">';
            }
            
            // Email body
            $body = "
            <html>
            <head>
                <style>
                    body {
                        font-family: 'Segoe UI', Arial, sans-serif;
                        line-height: 1.6;
                        color: #333333;
                        background-color: #f7f7f7;
                        margin: 0;
                        padding: 0;
                    }
                    .container {
                        max-width: 600px;
                        margin: 20px auto;
                        background-color: #ffffff;
                        border-radius: 8px;
                        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                        overflow: hidden;
                    }
                    .header {
                        background-color: #4CAF50;
                        padding: 20px;
                        text-align: center;
                        color: white;
                    }
                    .logo {
                        max-height: 60px;
                        margin-bottom: 10px;
                    }
                    .content {
                        padding: 30px;
                        background-color: #ffffff;
                    }
                    h2 {
                        color: #ffffff;
                        margin: 0;
                        font-size: 24px;
                    }
                    p {
                        margin-bottom: 16px;
                        color: #555555;
                    }
                    .footer {
                        background-color: #f5f5f5;
                        padding: 15px;
                        text-align: center;
                        font-size: 12px;
                        color: #777777;
                        border-top: 1px solid #eeeeee;
                    }
                    .highlight {
                        font-weight: bold;
                        color: #4CAF50;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        $logoTag
                        <h2>Feedback Response</h2>
                    </div>
                    <div class='content'>
                        <p>Hello <span class='highlight'>$name</span>,</p>
                        " . nl2br($message) . "
                    </div>
                    <div class='footer'>
                        &copy; " . date('Y') . " " . SITE_NAME . ". All rights reserved.<br>
                        Providing reliable solar energy solutions for a sustainable future.
                    </div>
                </div>
            </body>
            </html>";
            
            $this->mailer->Body = $body;
            $this->mailer->AltBody = strip_tags($message);
            
            // Send email
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            // Log the error message
            error_log('Mailer Error: ' . $this->mailer->ErrorInfo);
            return false;
        }
    }
}