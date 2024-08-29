<?php

function get_saving_file($user_id) {
    $conn = connectDB();

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
?>
