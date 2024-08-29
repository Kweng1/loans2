<?php
// Function to establish a database connection
function connectDB() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "go_loan";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Function to count total number of loan IDs
function countTotalLoanIDs() {
    $conn = connectDB(); // Establish database connection

    // SQL query to count loan IDs
    $sql = "SELECT COUNT(loan_id) AS total_ids FROM loans";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $totalIDs = $row["total_ids"];
    } else {
        $totalIDs = 0; // Default to 0 if no loans found
    }

    $conn->close();

    return $totalIDs;
}

// Function to get saving transactions for a specific user
function get_saving_file($user_id) {
    $conn = connectDB(); // Establish database connection

    $sql = "SELECT * FROM savingstransactions WHERE user_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        throw new Exception("Failed to prepare the SQL statement.");
    }

    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $transactions = [];
    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row;
    }

    $stmt->close();
    $conn->close();

    return $transactions;
}

// Function to count unique user IDs
function countUniqueUserIDs() {
    $conn = connectDB(); // Establish database connection

    // SQL query to count unique user IDs
    $sql = "SELECT COUNT(DISTINCT user_id) AS total_unique_users FROM loans";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $totalUniqueUsers = $row["total_unique_users"];
    } else {
        $totalUniqueUsers = 0; // Default to 0 if no users found
    }

    $conn->close();

    return $totalUniqueUsers;
}

// Example usage
$user_id = 123; // Replace 123 with the actual user ID
$totalLoanIDs = countTotalLoanIDs();
$savingTransactions = get_saving_file($user_id);
$totalUniqueUserIDs = countUniqueUserIDs();
?>