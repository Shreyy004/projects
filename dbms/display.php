<?php
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

// Function to sanitize user input
function sanitize($data) {
    return htmlspecialchars(strip_tags($data));
}

$dataPoints = array( 
    array("label"=>"Chrome", "y"=>64.02),
    array("label"=>"Firefox", "y"=>12.55),
    array("label"=>"IE", "y"=>8.47),
    array("label"=>"Safari", "y"=>6.08),
    array("label"=>"Edge", "y"=>4.29),
    array("label"=>"Others", "y"=>4.59)
);

$test = array();
$count = 0;
$res = mysqli_query($conn, "SELECT COUNT(*) AS count, department FROM student GROUP BY department");

while ($row = mysqli_fetch_array($res)) {
    $test[$count]["label"] = $row["department"];
    $test[$count]["y"] = $row["count"];
    $count = $count + 1;
}

// Perform actions based on form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete'])) {
        $studentIDToDelete = sanitize($_POST['delete']);
        $deleteSql = "DELETE FROM student WHERE Student_ID = $studentIDToDelete";
        $conn->query($deleteSql);
    } elseif (isset($_POST['update'])) {
        $studentIDToUpdate = sanitize($_POST['update']);
        
        $updateSql = "SELECT * FROM student WHERE Student_ID = $studentIDToUpdate";
        $result = $conn->query($updateSql);
        $row = $result->fetch_assoc();
        // Display the update form with pre-filled values
        echo '<form method="post" action="' . $_SERVER["PHP_SELF"] . '">
                <input type="hidden" name="studentID" value="' . $row["Student_ID"] . '">
                <input type="text" name="firstName" value="' . $row["first_name"] . '">
                <input type="text" name="lastName" value="' . $row["last_name"] . '">
                <input type="date" name="dob" value="' . $row["DOB"] . '">
                <input type="number" name="age" value="' . $row["age"] . '">
                <input type="text" name="department" value="' . $row["department"] . '">
                <button type="submit" name="submitUpdate" class="btn btn-primary btn-sm">Update</button>
              </form>';
    } elseif (isset($_POST['submitUpdate'])) {
        // Handle the form submission for update
        $studentIDToUpdate = sanitize($_POST['studentID']);
        $firstName = sanitize($_POST['firstName']);
        $lastName = sanitize($_POST['lastName']);
        $dob = sanitize($_POST['dob']);
        $age = sanitize($_POST['age']);
        $department = sanitize($_POST['department']);

        // SQL query to update record in the student table
        $updateSql = "UPDATE student SET first_name='$firstName', last_name='$lastName', DOB='$dob', age='$age', department='$department' WHERE Student_ID=$studentIDToUpdate";
        $conn->query($updateSql);
    }
}
$sql = "SELECT * FROM student";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records</title>
    <!-- Bootstrap css -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script>
        window.onload = function () {
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                title: {
                    text: "Student Info"
                },
                data: [{
                    type: "pie",
                    yValueFormatString: "#,##0",
                    indexLabel: "{label} ({y})",
                    dataPoints: <?php echo json_encode($test, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Student Records</h2>
        <?php
        if ($result->num_rows > 0) {
            echo '<table class="table">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Date of Birth</th>
                            <th>Age</th>
                            <th>Department</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo '<tr>
                        <td>' . $row["Student_ID"] . '</td>
                        <td>' . $row["first_name"] . '</td>
                        <td>' . $row["last_name"] . '</td>
                        <td>' . $row["DOB"] . '</td>
                        <td>' . $row["age"] . '</td>
                        <td>' . $row["department"] . '</td>
                        <td>
                            <form method="post" style="display:inline;">
                                <button type="submit" name="delete" value="' . $row["Student_ID"] . '" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            <form method="post" style="display:inline;">
                                <button type="submit" name="update" value="' . $row["Student_ID"] . '" class="btn btn-primary btn-sm">Update</button>
                            </form>
                        </td>
                      </tr>';
            }
            echo '</tbody></table>';
        } else {
            echo "No records found";
        }


 
        $conn->close();
        ?>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
    </div>
</body>
</html>
