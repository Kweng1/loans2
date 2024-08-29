<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "go_loan";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_id = $_POST["request_id"];
    
    if (isset($_POST['approve'])) {
        $status = 'Completed';
    } elseif (isset($_POST['reject'])) {
        $status = 'Rejected';
    }

    $sql = "UPDATE withdrawalrequests SET status=? WHERE request_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $status, $request_id);

    if ($stmt->execute()) {
        echo "Withdrawal request updated successfully.";
        header("Location: ../../view/AdminDashboard/savings.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
header("Location: ../../view/AdminDashboard/savings.php"); // Redirect back to the admin dashboard
exit;
?>
