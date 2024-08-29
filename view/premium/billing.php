<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bills</title>
    <link rel="stylesheet" href="../css/design.css">
    <script>
        function confirmPayment(loan_id, due_date) {
            if (confirm("Are you sure you want to pay?")) {
                window.location.href = "pay.php?loan_id=" + loan_id + "&due_date=" + due_date;
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <?php include 'header.php'; ?>
        <div class="content">
            <?php
                session_start();

                // Database connection
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "go_loan";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch approved loans for the logged-in user
                $user_id = $_SESSION['user_id'];
                $sql = "SELECT * FROM loans WHERE status = 'approved' AND user_id = '$user_id'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $loan_id = $row['loan_id'];
                        $loan_amount = $row['loan_amount'];
                        $date = $row['date'];
                        $payable_months = $row['payable_months'];
                        $interest_rate = 0.03; // 3% interest rate

                        $total_interest = $loan_amount * $interest_rate;
                        $total_amount = $loan_amount + $total_interest;
                        $monthly_payment = $total_amount / $payable_months;

                        echo "<h2>Billing Summary</h2>";
                        echo "<table border='1'>
                                <tr>
                                    <td>Loan Amount</td>
                                    <td>" . number_format($loan_amount, 2) . "</td>
                                </tr>
                                <tr>
                                    <td>Interest Rate (3%)</td>
                                    <td>" . number_format($total_interest, 2) . "</td>
                                </tr>
                                <tr>
                                    <td>Amount to Pay</td>
                                    <td>" . number_format($total_amount, 2) . "</td>
                                </tr>
                              </table>";

                        echo "<h3>Monthly Payments [$payable_months Months]</h3>";
                        echo "<table border='1'>
                                <tr>
                                    <th>Due Date</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>";

                        for ($i = 1; $i <= $payable_months; $i++) {
                            $due_date = date('Y-m-d', strtotime("+$i months", strtotime($date)));

                            $billing_sql = "INSERT INTO billing (loan_id, billing_amount, due_date, payment_status, date_generated, interest)
                                            VALUES ('$loan_id', '$monthly_payment', '$due_date', 'Pending', NOW(), '$interest_rate')";

                            if ($conn->query($billing_sql) === TRUE) {
                                echo "<tr>
                                        <td>$due_date</td>
                                        <td>" . number_format($monthly_payment, 2) . "</td>
                                        <td><button onclick=\"confirmPayment('$loan_id', '$due_date')\">Pay Now</button></td>
                                      </tr>";
                            } else {
                                echo "Error: " . $billing_sql . "<br>" . $conn->error;
                            }
                        }

                        echo "</table>";
                    }
                } else {
                    echo "No Loan to Pay.";
                }

                $conn->close();
            ?>
        </div>
    </div>
    <script>
        function confirmPayment(loan_id, due_date) {
            if (confirm("Are you sure you want to pay?")) {
                window.location.href = "pay.php?loan_id=" + loan_id + "&due_date=" + due_date;
            }
        }
    </script>
</body>
</html>
