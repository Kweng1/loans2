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

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id']; // Get the logged-in user ID from the session

// Function to create a withdrawal request
function createWithdrawalRequest($user_id, $amount) {
    global $conn;

    // Validate the amount
    if ($amount < 500 || $amount > 5000) {
        return "Amount should be between 500 and 5000.";
    }

    // Check if the user has enough balance
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

    if ($amount > $current_amount) {
        return "Insufficient balance.";
    }

    // Insert the withdrawal request
    $sql = "INSERT INTO WithdrawalRequests (user_id, amount, status) VALUES (?, ?, 'Pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('id', $user_id, $amount);

    if ($stmt->execute()) {
        header("Location: ../../view/premium/saving.php?message=Withdrawal request created successfully!"); // Redirect to prevent form resubmission
        exit;
    } else {
        return "Failed to create withdrawal request: " . $stmt->error;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['withdraw'])) {
    $amount = $_POST['withdraw_amount'];
    $message = createWithdrawalRequest($user_id, $amount);
    echo "<p>$message</p>";
}

// Close the database connection
$conn->close();
?>
