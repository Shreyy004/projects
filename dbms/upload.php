<?php
// Set database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbmsabi";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed:" . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    // Get values from the form
    $studentId = $_POST['studentId'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $department = $_POST['department'];

    // SQL query to insert data into the student table
    $sql = "INSERT INTO student (Student_ID, first_name, last_name, DOB, age, department) VALUES ('$studentId', '$firstName', '$lastName', '$dob', '$age', '$department')";

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
    <title>Student Information Form</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap css -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="height:100vh">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title text-center">Student Information Form</h4>
            </div>
            <div class="card-body">
                <form method="post">    <!--form-->
                    <div class="form-group">
                        <label for="studentId">Student ID:</label>
                        <input type="text" name="studentId" class="form-control" id="studentId" required>
                    </div>
                    <div class="form-group">
                        <label for="firstName">First Name:</label>
                        <input type="text" name="firstName" class="form-control" id="firstName" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name:</label>
                        <input type="text" name="lastName" class="form-control" id="lastName" required>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth:</label>
                        <input type="date" name="dob" class="form-control" id="dob" required>
                    </div>
                    <div class="form-group">
                        <label for="age">Age:</label>
                        <input type="number" name="age" class="form-control" id="age" required>
                    </div>
                    <div class="form-group">
                        <label for="department">Department:</label>
                        <input type="text" name="department" class="form-control" id="department" required>
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
