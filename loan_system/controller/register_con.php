<?php
class Registration {
    private $conn;

    public function __construct($servername, $username, $password, $dbname) {
        // Create connection
        $this->conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function sanitize($data) {
        return mysqli_real_escape_string($this->conn, $data);
    }

    public function checkPlanAvailability($plan) {
        // Check the count of registrations for the selected plan
        $sql_count = "SELECT COUNT(*) AS plan_count FROM user_tbl WHERE plan = '$plan'";
        $result_count = $this->conn->query($sql_count);
        if ($result_count) {
            $row_count = $result_count->fetch_assoc();
            $plan_count = $row_count['plan_count'];
            if (($plan == 'basic' && $plan_count >= 50) || ($plan == 'premium' && $plan_count >= 50)) {
                return false; // Plan is full
            }
            return true; // Plan is available
        } else {
            throw new Exception("Error in counting registrations: " . $this->conn->error);
        }
    }

    public function registerUser($data) {
        $plan = $this->sanitize($data['plan']);
        if (!$this->checkPlanAvailability($plan)) {
            throw new Exception("Registration for $plan plan is currently full. Please try again later.");
        }

        $name = $this->sanitize($data['name']);
        $password = $this->sanitize($data['password']);
        $password_hashed = password_hash($password, PASSWORD_DEFAULT); // Hash the password
        $address = $this->sanitize($data['Address']);
        $gender = $this->sanitize($data['gender']);
        $birthdate = $this->sanitize($data['Birthdate']);
        $email = $this->sanitize($data['email']);
        $contact = $this->sanitize($data['contact#']);
        $bankname = $this->sanitize($data['bankname']);
        $bankAccount = $this->sanitize($data['bankAccount#']);
        $cardHolder = $this->sanitize($data['cardHolder']);
        $tin = $this->sanitize($data['tin#']);
        $companyName = $this->sanitize($data['companyName']);
        $companyAddress = $this->sanitize($data['companyAddress']);
        $companyPhoneNumber = $this->sanitize($data['companyPhoneNumber']);
        $position = $this->sanitize($data['position']);
        $monthly_earnings = $this->sanitize($data['monthly_earnings']);

        $sql = "INSERT INTO user_tbl (plan, name, password, address, gender, birthdate, email, contact, bankname, bankAccount, cardHolder, tin, companyName, companyAddress, companyPhoneNumber, position, monthly_earnings, status) VALUES ('$plan', '$name', '$password_hashed', '$address', '$gender', '$birthdate', '$email', '$contact', '$bankname', '$bankAccount', '$cardHolder', '$tin', '$companyName', '$companyAddress', '$companyPhoneNumber', '$position', '$monthly_earnings', 'pending')";

        if ($this->conn->query($sql) === TRUE) {
            return "New record created successfully";
        } else {
            throw new Exception("Error: " . $sql . "<br>" . $this->conn->error);
        }
    }

    public function closeConnection() {
        $this->conn->close();
    }
}

try {
    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "go_loan";

    // Create an instance of the Registration class
    $registration = new Registration($servername, $username, $password, $dbname);

    // Retrieve form data
    $formData = $_POST;

    // Register the user
    echo $registration->registerUser($formData);

    // Close the database connection
    $registration->closeConnection();
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
