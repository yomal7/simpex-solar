<?php
class M_OTP {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Save OTP to database
    public function saveOTP($email, $otp, $expiry) {
        // First check if an OTP already exists for this email
        $this->db->query('SELECT * FROM otps WHERE email = :email');
        $this->db->bind(':email', $email);
        $existingOTP = $this->db->single();

        if ($existingOTP) {
            // Update existing OTP
            return $this->updateOTP($email, $otp, $expiry);
        }

        // Insert new OTP
        $this->db->query('INSERT INTO otps (email, otp, expiry_time) VALUES(:email, :otp, :expiry)');
        $this->db->bind(':email', $email);
        $this->db->bind(':otp', $otp);
        $this->db->bind(':expiry', $expiry);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Update existing OTP
    public function updateOTP($email, $otp, $expiry) {
        $this->db->query('UPDATE otps SET otp = :otp, expiry_time = :expiry WHERE email = :email');
        $this->db->bind(':otp', $otp);
        $this->db->bind(':expiry', $expiry);
        $this->db->bind(':email', $email);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Verify OTP
    public function verifyOTP($email, $otp) {
        $now = date('Y-m-d H:i:s');
        
        $this->db->query('SELECT * FROM otps WHERE email = :email AND otp = :otp AND expiry_time > :now');
        $this->db->bind(':email', $email);
        $this->db->bind(':otp', $otp);
        $this->db->bind(':now', $now);

        $row = $this->db->single();

        // Check if row exists
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Delete OTP after verification
    public function deleteOTP($email) {
        $this->db->query('DELETE FROM otps WHERE email = :email');
        $this->db->bind(':email', $email);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Clean up expired OTPs (can be called via cron job)
    public function cleanExpiredOTPs() {
        $now = date('Y-m-d H:i:s');
        
        $this->db->query('DELETE FROM otps WHERE expiry_time < :now');
        $this->db->bind(':now', $now);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}