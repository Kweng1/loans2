<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "go_loan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $loan_id = intval($_GET['id']);

    // Retrieve loan details
    $sql = "SELECT * FROM loans WHERE loan_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $loan_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $loan = $result->fetch_assoc();

        // Move to trash
        $sql_insert = "INSERT INTO trash (loan_id, loan_amount, date) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ids", $loan['loan_id'], $loan['loan_amount'], $loan['date']);
        $stmt_insert->execute();

        // Delete from loans table
        $sql_delete = "DELETE FROM loans WHERE loan_id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $loan_id);
        $stmt_delete->execute();

        echo "Loan moved to trash and will be deleted after 30 days.";
    } else {
        echo "Loan not found.";
    }

    $stmt->close();
}

$conn->close();
?>