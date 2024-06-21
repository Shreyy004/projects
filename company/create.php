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
    $companyId = $_POST['companyId'];
    $companyName = $_POST['companyName'];
    $domain = $_POST['domain'];

    // SQL query to insert data into the company table
    $sql = "INSERT INTO company (company_id, company_name, domain) VALUES ('$companyId', '$companyName', '$domain')";

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
    <title>Company Information Form</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap css -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="height:100vh">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title text-center">Company Information Form</h4>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="form-group">
                        <label for="companyId">Company ID:</label>
                        <input type="text" name="companyId" class="form-control" id="companyId" required>
                    </div>
                    <div class="form-group">
                        <label for="companyName">Company Name:</label>
                        <input type="text" name="companyName" class="form-control" id="companyName" required>
                    </div>
                    <div class="form-group">
                        <label for="domain">Domain:</label>
                        <input type="text" name="domain" class="form-control" id="domain" required>
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
