<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/registration.css">
</head>
<body>
<?php include '../controller/header.php'; ?>
<div class="register-container">
    
<div class="acctype">  
   
<form id="regId" action="../controller/register_con.php" method="post" autocomplete="off">
    <div class="page" id="page1">
        <p>REGISTER YOUR ACCOUNT</p>
        <br>
        <label for="acctype">CHOOSE PLAN:</label>
        <select name="plan" id="plan">
            <option value="basic">Basic</option>
            <option value="premium">Premium</option>
        </select>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>
        <label for="password">password:</label>
        <input type="password" name="password" id="password" style="width: 270px;" required>
        <label for="address">Address:</label>
        <input type="text" name="Address" id="address" required>
        <label for="gender">Gender:</label>
        <select name="gender" id="gender" required>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select>
        <label for="Birthdate">Birthdate:</label>
        <input type="date" name="Birthdate" id="Birthdate" required>
        <label for="email">Email:</label>
        <input type="text" name="email" id="email" required>
        <label for="contact#">Contact number:</label>
        <input type="text" name="contact#" id="contact#" required>
    </div>
    <div class="page" id="page2" style="display: none;">
        <p>BANK DETAILS</p>
        <br>
        <br>
        <label for="bankname">Bank Name:</label>
        <input type="text" name="bankname" id="bankname" required>
        <label for="bankAccount#">Bank Account Number:</label>
        <input type="text" name="bankAccount#" id="bankAccount#" required>
        <label for="cardHolder">Card Holder's Name:</label>
        <input type="text" name="cardHolder" id="cardHolder" required>
        <label for="tin#">TIN number:</label>
        <input type="text" name="tin#" id="tin#" required>
    </div>
    <div class="page" id="page3" style="display:none;">
        <p>EMPLOYMENT DETAILS</p>
        <br>
        <br>
        <label for="companyName">Company Name:</label>
        <input type="text" name="companyName" id="companyName" required>
        <label for="companyAddress">Company Address:</label>
        <input type="text" name="companyAddress" id="companyAddress" required>
        <label for="companyPhoneNumber">Company Phone Number:</label>
        <input type="text" name="companyPhoneNumber" id="companyPhoneNumber" required>
        <label for="position">Position:</label>
        <input type="text" name="position" id="position" required>
        <label for="monthly_earnings">Monthly Earnings:</label>
        <input type="text" id="monthly_earnings" name="monthly_earnings" required>
        <label for="proof_billing">Proof of Billing:</label>
        <input type="file" id="proof_billing" name="proof_billing" required>
        <label for="valid_id_primary">Valid ID (Primary):</label>
        <input type="file" id="valid_id_primary" name="valid_id_primary" required>
        <label for="coe">Certificate of Employment:</label>
        <input type="file" id="coe" name="coe" required>

        <hr>
    </div>

    <div class="pagination">
        <button onclick="prevPage()">Back</button>
        <button onclick="nextPage()">Next</button>
    </div>
</form>

</div>
<script>
var currentPage = 1;
var maxPage = 3; // Set to the total number of pages in your form

function nextPage() {
    // Check if all required inputs on the current page are filled
    var inputs = document.querySelectorAll('#page' + currentPage + ' input[required], #page' + currentPage + ' select[required], #page' + currentPage + ' textarea[required]');
    var isValid = true;
    for (var i = 0; i < inputs.length; i++) {
        if (!inputs[i].value) {
            isValid = false;
            break;
        }
    }
    if (isValid && currentPage < maxPage) {
        document.getElementById('page' + currentPage).style.display = 'none';
        currentPage++;
        document.getElementById('page' + currentPage).style.display = 'flex';
    }
}

function prevPage() {
    if (currentPage > 1) {
        document.getElementById('page' + currentPage).style.display = 'none';
        currentPage--;
        document.getElementById('page' + currentPage).style.display = 'flex';
    }
}
</script>

</body>
</html>
