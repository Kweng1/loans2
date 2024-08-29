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

// Check if an ID is provided in the URL parameter for approval
if (isset($_GET['id'])) { // changed from 'loan_id' to 'id' to match the button URL
    $id = $conn->real_escape_string($_GET['id']);

    // Update the status of the user to "approved"
    $sql_update = "UPDATE loans SET status = 'approved' WHERE loan_id = $id";

    if ($conn->query($sql_update) === TRUE) {
        echo "User status updated to approved.";
        header("Location: ../../view/AdminDashboard/loans.php");
    } else {
        echo "Error updating user status: " . $conn->error;
    }
} else {
    echo "No ID provided for approval.";
}

$conn->close();
?>