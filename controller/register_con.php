<?php
// Database connection
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

// Retrieve form data and sanitize (to prevent SQL injection)
// Use mysqli_real_escape_string or prepared statements for sanitization

$plan = mysqli_real_escape_string($conn, $_POST['plan']);

// Check the count of registrations for the selected plan
$sql_count = "SELECT COUNT(*) AS plan_count FROM user_tbl WHERE plan = '$plan'";
$result_count = $conn->query($sql_count);
if ($result_count) {
    $row_count = $result_count->fetch_assoc();
    $plan_count = $row_count['plan_count'];
    if (($plan == 'basic' && $plan_count >= 50) || ($plan == 'premium' && $plan_count >= 50)) {
        echo "Registration for $plan plan is currently full. Please try again later.";
        exit(); // Stop further execution
    }
} else {
    echo "Error in counting registrations: " . $conn->error;
    exit(); // Stop further execution
}

$name = mysqli_real_escape_string($conn, $_POST['name']);
$password = mysqli_real_escape_string($conn, $_POST['password']); // Assuming this is the user's plain-text password
$password_hashed = password_hash($password, PASSWORD_DEFAULT); // Hash the password
$address = mysqli_real_escape_string($conn, $_POST['Address']);
$gender = mysqli_real_escape_string($conn, $_POST['gender']);
$birthdate = mysqli_real_escape_string($conn, $_POST['Birthdate']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$contact = mysqli_real_escape_string($conn, $_POST['contact#']);
$bankname = mysqli_real_escape_string($conn, $_POST['bankname']);
$bankAccount = mysqli_real_escape_string($conn, $_POST['bankAccount#']);
$cardHolder = mysqli_real_escape_string($conn, $_POST['cardHolder']);
$tin = mysqli_real_escape_string($conn, $_POST['tin#']);
$companyName = mysqli_real_escape_string($conn, $_POST['companyName']);
$companyAddress = mysqli_real_escape_string($conn, $_POST['companyAddress']);
$companyPhoneNumber = mysqli_real_escape_string($conn, $_POST['companyPhoneNumber']);
$position = mysqli_real_escape_string($conn, $_POST['position']);
$monthly_earnings = mysqli_real_escape_string($conn, $_POST['monthly_earnings']);

// Insert the data into your database table, including the status column
$sql = "INSERT INTO user_tbl (plan, name, password, address, gender, birthdate, email, contact, bankname, bankAccount, cardHolder, tin, companyName, companyAddress, companyPhoneNumber, position, monthly_earnings, status) VALUES ('$plan', '$name', '$password_hashed', '$address', '$gender', '$birthdate', '$email', '$contact', '$bankname', '$bankAccount', '$cardHolder', '$tin', '$companyName', '$companyAddress', '$companyPhoneNumber', '$position', '$monthly_earnings', 'pending')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
