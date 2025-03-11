<div class="form-container">
    <div class="form-header">
        <center><h1>Verify Your Email</h1></center>
        <br>
        <p><b>Please enter the 6-digit OTP sent to your email address</b></p>
        
        <?php flash('otp_flash'); ?>
    </div>
    
    <form action="<?php echo URLROOT; ?>/users/auth" method="POST">
        <input type="hidden" name="email" value="<?php echo $data['email']; ?>">
        <input type="hidden" name="mode" value="verify_otp">
        
        <!-- OTP input -->
        <div class="form-input-title">Enter OTP Code</div>
        <div class="otp-input-container">
            <input type="text" name="otp" class="otp-input" maxlength="6" placeholder="Enter 6-digit code" value="<?php echo $data['otp']; ?>" autofocus>
        </div>
        <span class="form-invalid"><?php echo $data['otp_err']; ?></span>
        
        <p class="timer-text">OTP will expire in <span id="timer">15:00</span> minutes</p>
        
        <div class="form-actions">
            <input type="submit" value="Verify OTP" class="form-btn">
        </div>
    </form>
    
    <div class="resend-container">
        <p>Didn't receive the code? 
            <form action="<?php echo URLROOT; ?>/users/resendOTP" method="POST" id="resendForm">
                <input type="hidden" name="email" value="<?php echo $data['email']; ?>">
                <button type="button" id="resendBtn" class="resend-btn" disabled>Resend OTP</button>
                <span id="countdown">(Wait 60 seconds)</span>
            </form>
        </p>
    </div>
</div>

<script>
// OTP Timer functionality
document.addEventListener('DOMContentLoaded', function() {
    // OTP expiry timer - 15 minutes
    let time = 15 * 60; // 15 minutes in seconds
    const timerElement = document.getElementById('timer');
    
    const updateTimer = () => {
        const minutes = Math.floor(time / 60);
        let seconds = time % 60;
        seconds = seconds < 10 ? '0' + seconds : seconds;
        timerElement.textContent = minutes + ':' + seconds;
        
        if (time <= 0) {
            clearInterval(timerInterval);
            timerElement.textContent = '0:00';
            alert('OTP has expired. Please request a new one.');
        }
        time--;
    };
    
    // Initialize and start the timer
    updateTimer();
    const timerInterval = setInterval(updateTimer, 1000);
    
    // Resend OTP functionality
    const resendBtn = document.getElementById('resendBtn');
    const resendForm = document.getElementById('resendForm');
    const countdownElement = document.getElementById('countdown');
    
    // Disable resend button for 60 seconds
    let countdownTime = 60;
    
    const updateCountdown = () => {
        countdownElement.textContent = `(Wait ${countdownTime} seconds)`;
        
        if (countdownTime <= 0) {
            clearInterval(countdownInterval);
            resendBtn.disabled = false;
            countdownElement.textContent = '';
        }
        countdownTime--;
    };
    
    // Start the countdown
    const countdownInterval = setInterval(updateCountdown, 1000);
    
    // Add click event to resend button
    resendBtn.addEventListener('click', function() {
        resendForm.submit();
        resendBtn.disabled = true;
        countdownTime = 60;
        updateCountdown();
        const countdownInterval = setInterval(updateCountdown, 1000);
    });
    
    // OTP input enhancements - auto-focus on input
    const otpInput = document.querySelector('.otp-input');
    otpInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').substring(0, 6);
    });
});
</script>

<style>
.form-container {
    max-width: 500px;
    margin: 30px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.form-header {
    margin-bottom: 20px;
}

.otp-input-container {
    display: flex;
    justify-content: center;
    margin: 20px 0;
}

.otp-input {
    font-size: 20px;
    letter-spacing: 8px;
    text-align: center;
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.timer-text {
    text-align: center;
    color: #666;
    font-size: 14px;
    margin-bottom: 20px;
}

.form-actions {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.form-btn {
    width: 100%;
    padding: 12px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
}

.form-btn:hover {
    background-color: #45a049;
}

.resend-container {
    text-align: center;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.resend-btn {
    background: none;
    border: none;
    color: #4CAF50;
    cursor: pointer;
    font-weight: bold;
}

.resend-btn:disabled {
    color: #aaa;
    cursor: not-allowed;
}

#countdown {
    font-size: 12px;
    color: #888;
}
</style>