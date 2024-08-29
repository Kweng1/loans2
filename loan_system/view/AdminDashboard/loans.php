<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/design.css">
    <title>Loans</title>
</head>
<body>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<main class="mainContent">
    <div class="cardsColumn">
        <div class="card">
            <div class="cardHeader">
                <h2>Loan Request</h2>
            </div>

            <div class="card-body">
                <table class="table table-bordered" id="loan-list">
                    <colgroup>
                        <col width="10%">
                        <col width="25%">
                        <col width="25%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Option</th>

                        </tr>
                    </thead>
                    <tbody>
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

                    // Retrieve loan list from database where status is "pending"
                    $sql = "SELECT * FROM loans WHERE status = 'pending'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["loan_id"] . "</td>";
                            echo "<td>" . $row["loan_amount"] . "</td>";
                            echo "<td>" . $row["date"] . "</td>";
                            echo "<td><button style='background-color: blue; color: white; margin-right: 10px;' onclick=\"location.href='../../controller/approve_loan.php?id=" . $row["loan_id"] . "'\">Approve</button></td>";
                            echo "<td><button style='background-color: red; color: white; margin-right: 10px;' onclick=\"location.href='../../controller/delete_loan.php?id=" . $row["loan_id"] . "'\">Delete</button></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No requests found</td></tr>";
                    }

                    $conn->close();
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
</body>
</html>l