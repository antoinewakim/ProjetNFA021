<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

// Connect to the database
$con = mysqli_connect("localhost", "root", "", "jobhub");
if ($con == false) {
    die("Connection Error: " . mysqli_connect_error());
}

// Retrieve user details
$user_id = $_SESSION["id"];
$user_name = $_SESSION["name"]; // Assuming the user's name is stored in the session

// Handle form submission
if (isset($_POST['submit'])) {
    $job_id = mysqli_real_escape_string($con, $_POST['job_id']);
    $comp_name = mysqli_real_escape_string($con, $_POST['comp_name']);
    $comp_location = mysqli_real_escape_string($con, $_POST['comp_location']);
    $comp_description = mysqli_real_escape_string($con, $_POST['comp_description']);
    $job_summary = mysqli_real_escape_string($con, $_POST['job_summary']);
    $job_type = mysqli_real_escape_string($con, $_POST['job_type']);
    $job_experience = mysqli_real_escape_string($con, $_POST['job_experience']);
    $job_employmenttype = mysqli_real_escape_string($con, $_POST['job_employmenttype']);
    
    $comp_image = $_FILES['comp_image']['name'];
    
    if ($comp_image) {
        $target_dir = "imgs/";
        $target_file = $target_dir . basename($comp_image);
        move_uploaded_file($_FILES['comp_image']['tmp_name'], $target_file);
        
        // SQL query with 9 placeholders
        $update_query = "UPDATE jobs SET comp_name = ?, comp_location = ?, comp_description = ?, job_summary = ?, job_type = ?, job_experience = ?, job_employmenttype = ?, comp_image = ? WHERE id = ? AND user_id = ?";
        $stmt = $con->prepare($update_query);
        $stmt->bind_param("ssssssssii", $comp_name, $comp_location, $comp_description, $job_summary, $job_type, $job_experience, $job_employmenttype, $comp_image, $job_id, $user_id);
    } else {
        // SQL query with 8 placeholders
        $update_query = "UPDATE jobs SET comp_name = ?, comp_location = ?, comp_description = ?, job_summary = ?, job_type = ?, job_experience = ?, job_employmenttype = ? WHERE id = ? AND user_id = ?";
        $stmt = $con->prepare($update_query);
        $stmt->bind_param("ssssssssi", $comp_name, $comp_location, $comp_description, $job_summary, $job_type, $job_experience, $job_employmenttype, $job_id, $user_id);
    }
    
    if ($stmt->execute()) {
        echo "<script>alert('Job post updated successfully'); window.location.href='managepost.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
}

// Fetch job details for editing
if (isset($_GET['id'])) {
    $job_id = mysqli_real_escape_string($con, $_GET['id']);
    $query = "SELECT * FROM jobs WHERE id = ? AND user_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $job_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $job = $result->fetch_assoc();

    if (!$job) {
        echo "<script>alert('Job not found or you do not have permission to edit this job.'); window.location.href='managepost.php';</script>";
        exit();
    }
} else {
    header("Location: managepost.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>JobHub: Edit Post</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container p-1">
        <nav class="navbar navbar-expand-sm" style="font-weight: 500;">
            <div class="container-fluid">
            <a class="navbar-brand text-primary" href="home-user.php">
                <img src="imgs/JobHubLogo.png" alt="JobHub Logo" style="height: 80px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="collapsibleNavbar">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="jobs.html">Jobs</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><?php echo htmlspecialchars($user_name); ?></a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Edit Profile</a></li>
                                <li><a class="dropdown-item" href="logout.php">Sign Out</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <form method="POST" enctype="multipart/form-data">
        <div class="container-fluid" style="background-color:rgb(25, 92, 192); padding-bottom: 50px;">
            <div class="container text-center text-white p-5">
                <h1 class="display-5" style="font-weight:400;">Edit Your Job Post</h1> 
            </div>
            <div class="container bg-white rounded-3 p-2" style="width: 400px; padding-bottom: 20px !important;">
                <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job['id']); ?>">
                <div class="mt-2">Company Name <span class="text-primary">*</span></div>
                <input name="comp_name" type="text" class="form-control" value="<?php echo htmlspecialchars($job['comp_name']); ?>">
                <div class="mt-2">Location <span class="text-primary">*</span></div>
                <input name="comp_location" type="text" class="form-control" value="<?php echo htmlspecialchars($job['comp_location']); ?>">
                <div class="mt-2">Company Logo</div>
                <input type="file" name="comp_image" class="form-control" accept="image/*">
                <div class="mt-2">Company Description <span class="text-primary">*</span></div>
                <textarea name="comp_description" class="form-control" id="exampleFormControlTextarea1" rows="3"><?php echo htmlspecialchars($job['comp_description']); ?></textarea>
                <div class="mt-2">Job Summary <span class="text-primary">*</span></div>
                <textarea name="job_summary" class="form-control" id="exampleFormControlTextarea1" rows="3"><?php echo htmlspecialchars($job['job_summary']); ?></textarea>
                <div class="mt-2">Job Type <span class="text-primary">*</span></div>
                <select name="job_type" class="form-select">
                    <option <?php if ($job['job_type'] == 'Marketing') echo 'selected'; ?>>Marketing</option>
                    <option <?php if ($job['job_type'] == 'Public Administration') echo 'selected'; ?>>Public Administration</option>
                    <option <?php if ($job['job_type'] == 'Healthcare') echo 'selected'; ?>>Healthcare</option>
                    <option <?php if ($job['job_type'] == 'Engineering') echo 'selected'; ?>>Engineering</option>
                    <option <?php if ($job['job_type'] == 'IT Services') echo 'selected'; ?>>IT Services</option>
                    <option <?php if ($job['job_type'] == 'Telecommunication') echo 'selected'; ?>>Telecommunication</option>
                    <option <?php if ($job['job_type'] == 'HR Management') echo 'selected'; ?>>HR Management</option>
                </select>
                <div class="mt-2">Experience level <span class="text-primary">*</span></div>
                <select name="job_experience" class="form-select">
                    <option <?php if ($job['job_experience'] == 'Entry Level') echo 'selected'; ?>>Entry Level</option>
                    <option <?php if ($job['job_experience'] == 'Internship') echo 'selected'; ?>>Internship</option>
                    <option <?php if ($job['job_experience'] == 'Associate') echo 'selected'; ?>>Associate</option>
                    <option <?php if ($job['job_experience'] == 'Mid-Senior level') echo 'selected'; ?>>Mid-Senior level</option>
                    <option <?php if ($job['job_experience'] == 'Director') echo 'selected'; ?>>Director</option>
                </select>
                <div class="mt-2">Employment type<span class="text-primary">*</span></div>
                <select name="job_employmenttype" class="form-select">
                    <option <?php if ($job['job_employmenttype'] == 'Full-time') echo 'selected'; ?>>Full-time</option>
                    <option <?php if ($job['job_employmenttype'] == 'Part-time') echo 'selected'; ?>>Part-time</option>
                    <option <?php if ($job['job_employmenttype'] == 'Online') echo 'selected'; ?>>Online</option>
                </select>
                <div class="container text-center mt-4">
                    <button type="submit" name="submit" class="btn btn-outline-primary rounded-5" style="padding: 10px 40px 10px 40px;">Update Job</button>
                </div>
            </div>
        </div>
    </form>
</body>
</html>
