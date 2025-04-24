<?php
// app/models/M_Payment.php
class M_Payment
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Create payment record
    public function createPayment($data)
    {
        $this->db->query('INSERT INTO payments (order_id, payment_method, amount, status, bank_slip) 
                         VALUES (:order_id, :payment_method, :amount, :status, :bank_slip)');

        $this->db->bind(':order_id', $data['order_id']);
        $this->db->bind(':payment_method', $data['payment_method']);
        $this->db->bind(':amount', $data['amount'] ?? 0);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':bank_slip', $data['bank_slip'] ?? null);

        return $this->db->execute();
    }

    // Get payment by order ID
    public function getPaymentByOrderId($orderId)
    {
        $this->db->query('SELECT * FROM payments WHERE order_id = :order_id');
        $this->db->bind(':order_id', $orderId);
        return $this->db->single();
    }

    // Update payment status
    public function updatePaymentStatus($paymentId, $status)
    {
        $this->db->query('UPDATE payments 
                         SET status = :status, 
                             updated_at = CURRENT_TIMESTAMP 
                         WHERE id = :id');

        $this->db->bind(':status', $status);
        $this->db->bind(':id', $paymentId);

        return $this->db->execute();
    }

    // Get pending payments
    public function getPendingPayments()
    {
        $this->db->query('SELECT p.*, o.user_id, u.name as customer_name, o.order_number 
                         FROM payments p 
                         JOIN orders o ON p.order_id = o.id 
                         JOIN users u ON o.user_id = u.user_id 
                         WHERE p.status = "pending_verification"');
        return $this->db->resultSet();
    }

    // Approve payment
    public function approvePayment($paymentId)
    {
        return $this->updatePaymentStatus($paymentId, 'approved');
    }

    // Reject payment
    public function rejectPayment($paymentId, $reason)
    {
        $this->db->query('UPDATE payments 
                         SET status = "rejected", 
                             rejection_reason = :reason,
                             updated_at = CURRENT_TIMESTAMP 
                         WHERE id = :id');

        $this->db->bind(':reason', $reason);
        $this->db->bind(':id', $paymentId);

        return $this->db->execute();
    }

    // Get payment by ID
    public function getPaymentById($paymentId)
    {
        $this->db->query('SELECT * FROM payments WHERE id = :id');
        $this->db->bind(':id', $paymentId);
        return $this->db->single();
    }

    // Update payment slip
    public function updatePaymentSlip($orderId, $fileName)
    {
        $this->db->query('UPDATE payments 
                         SET bank_slip = :bank_slip, 
                             status = "pending_verification",
                             rejection_reason = NULL, 
                             updated_at = CURRENT_TIMESTAMP 
                         WHERE order_id = :order_id');

        $this->db->bind(':bank_slip', $fileName);
        $this->db->bind(':order_id', $orderId);

        return $this->db->execute();
    }
}
