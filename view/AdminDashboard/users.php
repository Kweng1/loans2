<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/design.css">
    <title>Users</title>
</head>
<body>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
<main class="mainContent">
    <div class="cardsColumn">
        <div class="card2">
            <div class="cardHeader">
                <h2>Registration Request</h2>
            </div>

            <div class="card-body">
                <table class="table table-bordered" id="loan-list">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Plan</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Gender</th>
                            <th>Birthdate</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Bank Name</th>
                            <th>Bank Account</th>
                            <th>Card Holder</th>
                            <th>TIN</th>
                            <!-- <th>Company Name</th>
                            <th>Company Address</th>
                            <th>Company Phone</th> -->
                            <th>Position</th>
                            <th>Monthly Earnings</th>
                            <th>Status</th>
                            <th>Actions</th>
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

                        // Retrieve user list from database
                        $sql = "SELECT * FROM user_tbl";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row["user_id"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["plan"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["address"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["gender"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["birthdate"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["contact"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["bankname"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["bankAccount"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["cardHolder"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["tin"]) . "</td>";
                                // echo "<td>" . htmlspecialchars($row["companyName"]) . "</td>";
                                // echo "<td>" . htmlspecialchars($row["companyAddress"]) . "</td>";
                                // echo "<td>" . htmlspecialchars($row["companyPhoneNumber"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["position"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["monthly_earnings"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
                                echo "<td><button style='background-color: blue; color: white;' onclick=\"location.href='../../controller/approve.php?user_id=" . $row["user_id"] . "'\">Approve</button></td>";
                                echo "<td><button style='background-color: red; color: white;' onclick=\"location.href='delete.php?id=" . $row["user_id"] . "'\">Reject</button></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='19'>No requests found</td></tr>";
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
