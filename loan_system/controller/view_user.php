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

$sql_savings = "SELECT u.name, t1.user_id, t1.current_amount
        FROM savingstransactions t1
        INNER JOIN (
            SELECT user_id, MAX(created_at) AS latest_transaction
            FROM savingstransactions
            GROUP BY user_id
        ) t2 ON t1.user_id = t2.user_id AND t1.created_at = t2.latest_transaction
        INNER JOIN user_tbl u ON t1.user_id = u.user_id
        ORDER BY t1.user_id";

$result_savings = $conn->query($sql_savings);

$sql_withdrawals = "SELECT wr.request_id, wr.user_id, u.name, wr.amount, wr.status, wr.created_at
        FROM withdrawalrequests wr
        INNER JOIN user_tbl u ON wr.user_id = u.user_id
        ORDER BY wr.created_at DESC";

$result_withdrawals = $conn->query($sql_withdrawals);
?>

