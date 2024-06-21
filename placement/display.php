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

// Function to sanitize user input
function sanitize($data) {
    return htmlspecialchars(strip_tags($data));
}

// Perform actions based on form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete'])) {
        $placementIDToDelete = sanitize($_POST['delete']);
        $deleteSql = "DELETE FROM placements WHERE placement_id = $placementIDToDelete";
        $conn->query($deleteSql);
    } elseif (isset($_POST['update'])) {
        $placementIDToUpdate = sanitize($_POST['update']);
        
        // Placeholder for update logic
        // Replace the following lines with your actual update logic
        $updateSql = "SELECT * FROM placements WHERE placement_id = $placementIDToUpdate";
        $result = $conn->query($updateSql);
        $row = $result->fetch_assoc();

        // Display the update form with pre-filled values
        echo '<form method="post" action="' . $_SERVER["PHP_SELF"] . '">
                <input type="hidden" name="placementID" value="' . $row["placement_id"] . '">
                <input type="text" name="placementType" value="' . $row["placement_type"] . '">
                <input type="text" name="companyID" value="' . $row["company_id"] . '">
                <button type="submit" name="submitUpdate">Update Record</button>
              </form>';
    } elseif (isset($_POST['submitUpdate'])) {
        $placementIDToUpdate = $_POST['placementID'];
        $placementType = sanitize($_POST['placementType']);
        $companyID = sanitize($_POST['companyID']);

        // SQL query to update record in the placements table
        $updateSql = "UPDATE placements SET placement_type='$placementType', company_id='$companyID' WHERE placement_id=$placementIDToUpdate";
        $conn->query($updateSql);
    }
}

// SQL query to select all records from the placements table
$sql = "SELECT * FROM placements";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placement Records</title>
    <!-- Bootstrap css -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Placement Records</h2>
        <?php
        // Check if there are any records
        if ($result->num_rows > 0) {
            echo '<table class="table">
                    <thead>
                        <tr>
                            <th>Placement ID</th>
                            <th>Placement Type</th>
                            <th>Company ID</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo '<tr>
                        <td>' . $row["placement_id"] . '</td>
                        <td>' . $row["placement_type"] . '</td>
                        <td>' . $row["company_id"] . '</td>
                        <td>
                            <form method="post" style="display:inline;">
                                <button type="submit" name="delete" value="' . $row["placement_id"] . '" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            <form method="post" style="display:inline;">
                                <button type="submit" name="update" value="' . $row["placement_id"] . '" class="btn btn-primary btn-sm">Update</button>
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

        <!-- Bootstrap js -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </div>
</body>
</html>
