<?php

$servername = "localhost";
$username = "root";
$password = ""; 
$database = "jobhub"; 


$conn = new mysqli($servername, $username, $password, $database);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jobid'])&&isset($_POST['userid'])) {
    
    $post_id = intval($_POST['jobid']); 
    $user_id = intval($_POST['userid']);
    
    $query = "INSERT INTO applytojob (user_id, job_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $post_id, $user_id); // Replace $user_id with the actual user ID
    
    
    if ($stmt->execute()) {
        
        echo json_encode(['success' => true]);
    } else {
       
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }
} else {
   
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>