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

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id']; // Get the logged-in user ID from the session

// Function to get savings transactions
function getSavingsTransactions($user_id)
{
    global $conn;

    // Fetch all transactions
    $sql = "SELECT * FROM savingstransactions WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $transactions = [];
    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row;
    }

    // Fetch the latest current amount
    $latest_current_amount = 0;
    if (!empty($transactions)) {
        $latest_current_amount = $transactions[0]['current_amount'];
    }

    return ['transactions' => $transactions, 'latest_current_amount' => $latest_current_amount];
}

// Function to deposit amount
function deposit($user_id, $amount) {
    global $conn;

    // Validate the amount
    if ($amount < 100 || $amount > 10000) {
        return "Amount should be between 100 and 10000.";
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

    // Generate a unique transaction ID
    do {
        $transaction_id = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $sql = "SELECT COUNT(*) as count FROM savingstransactions WHERE transaction_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $transaction_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
    } while ($row['count'] > 0);

    $transaction_type = 'Deposit';
    $status = 'Completed';
    $created_at = date('Y-m-d H:i:s');

    $sql = "INSERT INTO savingstransactions (user_id, transaction_id, transaction_type, amount, current_amount, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issdiss', $user_id, $transaction_id, $transaction_type, $amount, $new_amount, $status, $created_at);

    if ($stmt->execute()) {
        header("Location: saving.php?message=Deposit successful!"); // Redirect to prevent form resubmission
        exit;
    } else {
        return "Failed to deposit amount: " . $stmt->error;
    }
}

// Function to withdraw amount
function withdraw($user_id, $amount) {
    global $conn;

    // Validate the amount
    if ($amount < 500 || $amount > 5000) {
        return "Amount should be between 500 and 5000.";
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

    // Check if the user has enough balance
    if ($amount > $current_amount) {
        return "Insufficient balance.";
    }

    // Insert the withdrawal request
    $sql = "INSERT INTO withdrawalrequests (user_id, amount, status, created_at) VALUES (?, ?, 'Pending', ?)";
    $stmt = $conn->prepare($sql);
    $created_at = date('Y-m-d H:i:s');
    $stmt->bind_param('ids', $user_id, $amount, $created_at);

    if ($stmt->execute()) {
        return "Withdrawal request submitted successfully!";
    } else {
        return "Failed to submit withdrawal request: " . $stmt->error;
    }
}

// Function to process approved withdrawals
function processApprovedWithdrawals() {
    global $conn;

    // Fetch completed withdrawal requests
    $sql = "SELECT request_id, user_id, amount FROM withdrawalrequests WHERE status = 'Completed'";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $request_id = $row['request_id'];
        $user_id = $row['user_id'];
        $amount = $row['amount'];

        // Fetch the user's current amount
        $sql = "SELECT current_amount FROM savingstransactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result2 = $stmt->get_result();

        $current_amount = 0;
        if ($result2->num_rows > 0) {
            $row2 = $result2->fetch_assoc();
            $current_amount = $row2['current_amount'];
        }

        // Calculate the new amount
        $new_amount = $current_amount - $amount;

        // Insert the withdrawal transaction
        $transaction_id = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT); // Random 6-digit transaction ID
        $transaction_type = 'Withdrawal';
        $status = 'Completed';
        $created_at = date('Y-m-d H:i:s');

        $sql = "INSERT INTO savingstransactions (user_id, transaction_id, transaction_type, amount, current_amount, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('issdiss', $user_id, $transaction_id, $transaction_type, $amount, $new_amount, $status, $created_at);

        if ($stmt->execute()) {
            // Update the withdrawal request status to processed
            $sql = "UPDATE withdrawalrequests SET status = 'Processed' WHERE request_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $request_id);
            $stmt->execute();
        }
    }
}

// Fetch savings transactions for the user, including the latest current amount
$savingsData = getSavingsTransactions($user_id);
$savingsTransactions = $savingsData['transactions'];
$latest_current_amount = $savingsData['latest_current_amount'];

// Process approved withdrawals (for demonstration, should be triggered by an appropriate event in real application)
processApprovedWithdrawals();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['deposit'])) {
        $amount = $_POST['deposit_amount'];
        $message = deposit($user_id, $amount);
        echo "<p>$message</p>";
    }

    if (isset($_POST['withdraw'])) {
        $amount = $_POST['withdraw_amount'];
        $message = withdraw($user_id, $amount);
        echo "<p>$message</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Savings</title>
    <link rel="stylesheet" href="../css/design.css">
</head>
<body>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <?php include 'header.php'; ?>
        <div class="content">
            <h2>Savings Transactions</h2>

            <!-- Display the latest current amount -->
            <p>Latest Current Amount: <?php echo htmlspecialchars($latest_current_amount); ?></p>

            <!-- Display transactions table if transactions exist -->
            <?php if (!empty($savingsTransactions)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Current Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($savingsTransactions as $transaction): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($transaction['transaction_id']); ?></td>
                                <td><?php echo htmlspecialchars($transaction['transaction_type']); ?></td>
                                <td><?php echo htmlspecialchars($transaction['amount']); ?></td>
                                <td><?php echo htmlspecialchars($transaction['current_amount']); ?></td>
                                <td><?php echo htmlspecialchars($transaction['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No transactions found.</p>
            <?php endif; ?>

            <p>Transactions:</p>

            <!-- Trigger/Open The Modal -->
            <button id="depositBtn" onclick="disableButton(this)">Deposit</button>
            <button id="withdrawBtn" onclick="disableButton(this)">Withdraw</button>

            <!-- Deposit Modal -->
            <div id="depositModal" class="modal">
                <div class="modal-content">
                    <span class="close" id="depositClose">&times;</span>
                    <form action="saving.php" method="POST">
                        <h3>Deposit</h3>
                        <label for="deposit_amount">Amount:</label>
                        <input type="number" id="deposit_amount" name="deposit_amount" min="100" max="10000" required>
                        <button type="submit" name="deposit">Deposit</button>
                    </form>
                </div>
            </div>

            <!-- Withdrawal Modal -->
            <div id="withdrawModal" class="modal">
                <div class="modal-content">
                    <span class="close" id="withdrawClose">&times;</span>
                    <form action="saving.php" method="POST">
                        <h3>Withdraw</h3>
                        <label for="withdraw_amount">Amount:</label>
                        <input type="number" id="withdraw_amount" name="withdraw_amount" min="100" max="10000" required>
                        <button type="submit" name="withdraw">Withdraw</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function disableButton(btn) {
            btn.disabled = true;
        }

        // Get the modal elements
        var depositModal = document.getElementById('depositModal');
        var withdrawModal = document.getElementById('withdrawModal');

        // Get the button elements
        var depositBtn = document.getElementById('depositBtn');
        var withdrawBtn = document.getElementById('withdrawBtn');

        // Get the <span> elements that close the modals
        var depositClose = document.getElementById('depositClose');
        var withdrawClose = document.getElementById('withdrawClose');

        // When the user clicks the button, open the modal
        depositBtn.onclick = function() {
            depositModal.style.display = "block";
        }
        withdrawBtn.onclick = function() {
            withdrawModal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        depositClose.onclick = function() {
            depositModal.style.display = "none";
        }
        withdrawClose.onclick = function() {
            withdrawModal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == depositModal) {
                depositModal.style.display = "none";
            }
            if (event.target == withdrawModal) {
                withdrawModal.style.display = "none";
            }
        }
    </script>
</body>
</html>
