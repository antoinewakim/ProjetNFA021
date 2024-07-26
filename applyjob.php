<?php
session_start();

if (!isset($_SESSION["id"])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobid = isset($_POST['jobid']) ? intval($_POST['jobid']) : 0;
    $userid = isset($_POST['userid']) ? intval($_POST['userid']) : 0;

    if ($jobid <= 0 || $userid <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid job ID or user ID']);
        exit();
    }

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "jobhub";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'error' => 'Database connection failed']);
        exit();
    }

    // Check if the user has already applied for this job
    $checkSql = "SELECT * FROM job_applications WHERE job_id = ? AND user_id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param('ii', $jobid, $userid);

    if ($checkStmt->execute()) {
        $result = $checkStmt->get_result();
        if ($result->num_rows > 0) {
            echo json_encode(['success' => false, 'error' => 'Already applied']);
            $conn->close();
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Query failed']);
        $conn->close();
        exit();
    }

    // Record the application
    $insertSql = "INSERT INTO job_applications (job_id, user_id) VALUES (?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param('ii', $jobid, $userid);

    if ($insertStmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to record application']);
    }

    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
