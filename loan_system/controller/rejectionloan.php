<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "go_loan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $loan_id = $_GET['id'];

    // Reject loan
    $sql = "UPDATE loan_tbl SET status='rejected' WHERE loan_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $loan_id);
    
    if ($stmt->execute()) {
        echo "Loan ID $loan_id has been rejected.";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();

header("Location:../../view/AdminDashboard/loans.php"); // Redirect back to the loans page
exit;
?>
