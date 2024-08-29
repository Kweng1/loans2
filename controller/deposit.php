<?php
// Start the session
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "go_loan";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

function deposit($user_id, $amount)
{
    global $conn;

    // Validate the amount
    if ($amount < 100 || $amount > 1000) {
        return "Amount should be between 100 and 1000.";
    }

    // Fetch the user's current amount
    $sql = "SELECT current_amount FROM savingstransactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $current_amount = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $current_amount = $row['current_amount'];
    }

    // Calculate the new amount
    $new_amount = $current_amount + $amount;

    // Insert the deposit transaction
    $transaction_id = uniqid('txn_'); // Unique transaction ID
    $transaction_type = 'deposit';
    $status = 'completed';
    $created_at = date('Y-m-d H:i:s');

    $sql = "INSERT INTO savingstransactions (user_id, transaction_id, transaction_type, amount, current_amount, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issdiss', $user_id, $transaction_id, $transaction_type, $amount, $new_amount, $status, $created_at);

    if ($stmt->execute()) {
        return "Deposit successful!";
    } else {
        return "Failed to deposit amount: " . $stmt->error;
    }
}
?>