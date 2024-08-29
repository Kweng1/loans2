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
    $user_id = intval($_GET['id']);

    // Start transaction
    $conn->begin_transaction();

    try {
        // Get the user data
        $sql = "SELECT * FROM user_tbl WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Insert into user_delete table
            $sql = "INSERT INTO user_delete (user_id, plan, name, email, status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issss", $row['user_id'], $row['plan'], $row['name'], $row['email'], $row['status']);
            $stmt->execute();

            // Delete from user_tbl table
            $sql = "DELETE FROM user_tbl WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            // Commit transaction
            $conn->commit();

            echo "User deleted successfully.";
        } else {
            echo "User not found.";
        }
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $stmt->close();
} else {
    echo "No user ID specified.";
}

$conn->close();
?>
