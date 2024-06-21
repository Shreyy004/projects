<?php
// Set database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbmsabi";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed:" . $conn->connect_error);
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Get values from the form
    $placementId = $_POST['placementId'];
    $salaryAmount = $_POST['salaryAmount'];
    $employmentType = $_POST['employmentType'];

    // SQL query to insert data into the salary_details table
    $sql = "INSERT INTO salary_details (placement_id, salary_amount, employement_type) VALUES ('$placementId', '$salaryAmount', '$employmentType')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "Record inserted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Salary Details Form</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap css -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="height:100vh">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title text-center">Salary Details Form</h4>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="form-group">
                        <label for="placementId">Placement ID:</label>
                        <input type="text" name="placementId" class="form-control" id="placementId" required>
                    </div>
                    <div class="form-group">
                        <label for="salaryAmount">Salary Amount:</label>
                        <input type="text" name="salaryAmount" class="form-control" id="salaryAmount" required>
                    </div>
                    <div class="form-group">
                        <label for="employmentType">Employment Type:</label>
                        <input type="text" name="employmentType" class="form-control" id="employmentType" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary btn-block">Submit</button>
                    <button type="reset" class="btn btn-warning btn-block">Reset</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap js -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
