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

// Delete records older than 30 days
$sql = "DELETE FROM user_delete WHERE deleted_at < NOW() - INTERVAL 30 DAY";
if ($conn->query($sql) === TRUE) {
    echo "Old records cleaned up successfully.";
} else {
    echo "Error cleaning up records: " . $conn->error;
}

$conn->close();
?>
