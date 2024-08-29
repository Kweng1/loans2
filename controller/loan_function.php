<?php
class Loan {
    private $db;
    private $user_id;
    private $loan_amount;
    private $note;
    private $payable_months;

    public function __construct($db, $user_id, $loan_amount, $note, $payable_months) {
        $this->db = $db;
        $this->user_id = $user_id;
        $this->loan_amount = $loan_amount;
        $this->note = $note;
        $this->payable_months = $payable_months;
    }

    private function generateTransactionId() {
        return mt_rand(100000, 999999);
    }

    public function processLoan() {
        if ($this->loan_amount < 5000) {
            echo "Minimum loan amount is $5000.";
            return;
        }

        // Check if the user has already reached the maximum loan amount of $10,000
        $sql_total_loan = "SELECT SUM(loan_amount) AS total_loan FROM loans WHERE user_id = ?";
        $stmt_total_loan = $this->db->conn->prepare($sql_total_loan);
        $stmt_total_loan->bind_param("i", $this->user_id);
        $stmt_total_loan->execute();
        $result_total_loan = $stmt_total_loan->get_result();
        $row_total_loan = $result_total_loan->fetch_assoc();
        $total_loan = $row_total_loan['total_loan'];

        // Calculate the remaining amount the user can loan
        $remaining_amount = 10000 - $total_loan;

        // Check if the requested loan amount exceeds the remaining amount
        if ($remaining_amount <= 0) {
            echo "You have reached the maximum loan amount of $10,000.";
        } elseif ($this->loan_amount > $remaining_amount) {
            echo "You can only loan $remaining_amount more.";
        } else {
            // Generate transaction ID
            $transaction_id = $this->generateTransactionId();

            // Prepare SQL statement for inserting the loan
            $sql = "INSERT INTO loans (user_id, loan_amount, status, date, transaction_id, note, payable_months) VALUES (?, ?, 'pending', CURDATE(), ?, ?, ?)";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("iissi", $this->user_id, $this->loan_amount, $transaction_id, $this->note, $this->payable_months);

            // Execute the statement
            if ($stmt->execute()) {
                echo "Waiting for admin approval.";
            } else {
                echo "Error: " . $sql . "<br>" . $this->db->conn->error;
            }

            // Close statement
            $stmt->close();
        }
    }
}
?>
