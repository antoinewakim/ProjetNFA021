<?php

$servername = "localhost";
$username = "root";
$password = ""; 
$database = "jobhub"; 


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT Name, Email, Message, PhoneNumber FROM contactus";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Data</title>
    <link rel="stylesheet" href="contactus3.css">
</head>
<body>
    <div class="container">
        <h1>Submitted Data</h1>
        <table border="1">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Phone Number</th>
            </tr>
            <?php
            
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["Name"] . "</td>
                            <td>" . $row["Email"] . "</td>
                            <td>" . $row["Message"] . "</td>
                            <td>" . $row["PhoneNumber"] . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No data found</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>

<?php

$conn->close();
?>
