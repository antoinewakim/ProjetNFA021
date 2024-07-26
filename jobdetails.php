<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "jobhub";

$conn = new mysqli($servername, $username, $password, $database);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['job_id'])) {
    $job_id = $_POST['job_id'];

    $query = "SELECT comp_description, comp_location, job_type, comp_name, job_summary, job_experience, job_employmenttype, comp_image FROM jobs WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $job_id);

    if ($stmt->execute()) {
        $stmt->bind_result($comp_description, $comp_location, $job_type, $comp_name, $job_summary, $job_experience, $job_employmenttype, $comp_image);
        if ($stmt->fetch()) {
            echo "<h3>$comp_name</h3>";
            echo "<p><strong>Company Description:</strong> $comp_description</p>";
            echo "<p><strong>Location:</strong> $comp_location</p>";
            echo "<p><strong>Job Type:</strong> $job_type</p>";
            echo "<p><strong>Job Summary:</strong> $job_summary</p>";
            echo "<p><strong>Experience Required:</strong> $job_experience</p>";
            echo "<p><strong>Employment Type:</strong> $job_employmenttype</p>";
            echo "<img src='images/$comp_image' alt='$comp_name' style='width: 200px; height: auto;'>";
        }
    }

    $stmt->close();
}

$conn->close();
?>
