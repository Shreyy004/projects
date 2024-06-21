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
    $placementType = $_POST['placementType'];
    $companyId = $_POST['companyId'];

    // SQL query to insert data into the placements table
    $sql = "INSERT INTO placements (placement_id, placement_type, company_id) VALUES ('$placementId', '$placementType', '$companyId')";

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
    <title>Placement Information Form</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap css -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="height:100vh">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title text-center">Placement Information Form</h4>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="form-group">
                        <label for="placementId">Placement ID:</label>
                        <input type="text" name="placementId" class="form-control" id="placementId" required>
                    </div>
                    <div class="form-group">
                        <label for="placementType">Placement Type:</label>
                        <input type="text" name="placementType" class="form-control" id="placementType" required>
                    </div>
                    <div class="form-group">
                        <label for="companyId">Company ID:</label>
                        <input type="text" name="companyId" class="form-control" id="companyId" required>
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
