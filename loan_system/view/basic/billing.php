<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan</title>
    <link rel="stylesheet" href="../../css/design.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <?php include 'basic_sidebar.php'; ?>
        </div>
        <div class="content">
            <?php include 'header.php'; ?>
          
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

            // Fetch approved loans
            $sql = "SELECT * FROM loans WHERE status = 'approved'";
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
                            </tr>";

                    for ($i = 1; $i <= $payable_months; $i++) {
                        $due_date = date('Y-m-d', strtotime("+$i months", strtotime($date)));

                        $billing_sql = "INSERT INTO billing (loan_id, billing_amount, due_date, payment_status, date_generated, interest)
                                        VALUES ('$loan_id', '$monthly_payment', '$due_date', 'Pending', NOW(), '$interest_rate')";

                        if ($conn->query($billing_sql) === TRUE) {
                            echo "<tr>
                                    <td>$due_date</td>
                                    <td>" . number_format($monthly_payment, 2) . "</td>
                                  </tr>";
                        } else {
                            echo "Error: " . $billing_sql . "<br>" . $conn->error;
                        }
                    }

                    echo "</table>";
                }
            } else {
                echo "No approved loans found.";
            }

            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
