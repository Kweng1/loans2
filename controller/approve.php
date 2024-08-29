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
if (isset($_GET['user_id'])) {
    $id = $conn->real_escape_string($_GET['user_id']);

    // Update the status of the user to "approved"
    $sql_update = "UPDATE user_tbl SET status = 'approved' WHERE user_id = $id";

    if ($conn->query($sql_update) === TRUE) {
        echo "User status updated to approved.";
    } else {
        echo "Error updating user status: " . $conn->error;
    }
} else {
    echo "No ID provided for approval.";
}

$conn->close();
?>
