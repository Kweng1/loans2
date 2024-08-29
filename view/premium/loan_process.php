<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "go_loan";

function connectDB($servername, $dbname, $username, $password) {
    try {
        // Create PDO connection
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Set PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        // Handle connection errors
        die("Connection failed: " . $e->getMessage());
    }
}

$user_id = $_SESSION['user_id'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $loan_amount = $_POST['loan_amount'];
        $note = $_POST['note'];
        $payable_months = $_POST['payable_months'];

        $transaction_id = rand(100000, 999999);
        $status = "Pending";
        $date = date("Y-m-d");

        include '../../controller/loan_controller.php';

        insertLoan($user_id, $loan_amount, $transaction_id, $status, $note, $date, $payable_months);

        header("Location: ../../view/premium/loan.php");
    } else {
        echo "Please log in to apply for a loan.";
    }
}
?>


