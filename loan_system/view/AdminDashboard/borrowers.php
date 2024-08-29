<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/design.css">
    <title>Borrowers</title>
</head>
<body>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
<main class="mainContent">
    <div class="cardsColumn">
        <div class="card">
            <div class="cardHeader">
                <h2>Borrowers</h2>
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
                            <th class="text-center">User_id</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Transaction ID</th>
                            <th class="text-center">Loan Amount</th>
                         
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

                    // Retrieve loan list from database with user information
                    $sql = "SELECT loans.*, user_tbl.name 
                            FROM loans 
                            JOIN user_tbl ON loans.user_id = user_tbl.user_id";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["user_id"] . "</td>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . $row["transaction_id"] . "</td>";
                            echo "<td>" . $row["loan_amount"] . "</td>";

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
</html>