<?php
// Set database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbmsabi";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed:" . $conn->connect_error);
}



// Function to sanitize user input
function sanitize($data) {
    return htmlspecialchars(strip_tags($data));
}

$test=array();
$count=0;
$res=mysqli_query($conn,"select count(*),domain from company group by domain");

while($row=mysqli_fetch_array($res))
{
    $test[$count]["label"]=$row["domain"];
    $test[$count]["y"]=$row["count(*)"];
    $count=$count+1;

}

// Check if form is submitted for deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    // Get company ID to delete
    $companyIDToDelete = $_POST['delete'];

    // SQL query to delete record from the company table
    $deleteSql = "DELETE FROM company WHERE company_id = $companyIDToDelete";
    $conn->query($deleteSql);
}

// Check if form is submitted for updating
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $companyIDToUpdate = $_POST['update'];

    // Placeholder for update logic
    // Replace the following lines with your actual update logic
    $updateSql = "SELECT * FROM company WHERE company_id = $companyIDToUpdate";
    $result = $conn->query($updateSql);
    $row = $result->fetch_assoc();

    // Display the update form with pre-filled values
    echo '<form method="post" action="' . $_SERVER["PHP_SELF"] . '"> 
            <input type="hidden" name="companyID" value="' . $row["company_id"] . '">
            <input type="text" name="companyName" value="' . $row["company_name"] . '">
            <input type="text" name="domain" value="' . $row["domain"] . '">
            <button type="submit" name="submitUpdate">Update Record</button>
          </form>';
}

// Check if form is submitted for updating after form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitUpdate'])) {
    $companyIDToUpdate = $_POST['companyID'];
    $companyName = sanitize($_POST['companyName']);
    $domain = sanitize($_POST['domain']);

    // SQL query to update record in the company table
    $updateSql = "UPDATE company SET company_name='$companyName', domain='$domain' WHERE company_id=$companyIDToUpdate";
    $conn->query($updateSql);
}

// SQL query to select all records from the company table
$sql = "SELECT * FROM company";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Records</title>
    <!-- Bootstrap css -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script>
window.onload = function() {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	title:{
		text: "Company Placement"
	},
	axisY: {
		title: "Count (domain)"
	},
	data: [{
		type: "column",
		yValueFormatString: "#,##0.##",
		dataPoints: <?php echo json_encode($test, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
}
</script>

</head>
<body>
    <div class="container mt-5" style="display: flex; flex-direction: column;row-gap: 150px">
        <div>
        <h2 class="mb-4">Company Records</h2>
        <?php
        // Check if there are any records
        if ($result->num_rows > 0) {
            echo '<table class="table">
                    <thead>
                        <tr>
                            <th>Company ID</th>
                            <th>Company Name</th>
                            <th>Domain</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo '<tr>
                        <td>' . $row["company_id"] . '</td>
                        <td>' . $row["company_name"] . '</td>
                        <td>' . $row["domain"] . '</td>
                        <td>
                            <form method="post" style="display:inline;">
                                <button type="submit" name="delete" value="' . $row["company_id"] . '" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            <form method="post" style="display:inline;">
                                <button type="submit" name="update" value="' . $row["company_id"] . '" class="btn btn-primary btn-sm">Update</button>
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
        </div>

        <!-- Bootstrap js -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <div id="chartContainer" style="height: 370px; width: 100%;"></div>
        <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    </div>
</body>
</html>
