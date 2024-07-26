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
$user_name = isset($_SESSION["name"]) ? $_SESSION["name"] : ''; // Ensure user_name is set

// Handle job applications view
$job_id = isset($_GET['job_id']) ? mysqli_real_escape_string($con, $_GET['job_id']) : null;
if ($job_id) {
    // Fetch job details
    $job_query = "SELECT comp_name FROM jobs WHERE id = ?";
    $stmt_job = $con->prepare($job_query);
    $stmt_job->bind_param("i", $job_id);
    $stmt_job->execute();
    $stmt_job->bind_result($comp_name);
    $stmt_job->fetch();
    $stmt_job->close();

    // Fetch applicants for the job, including CV path
    $applicants_query = "SELECT a.user_id, u.first_name, u.last_name, u.phone_number, u.email, u.cv_path
                         FROM job_applications a 
                         JOIN users u ON a.user_id = u.id 
                         WHERE a.job_id = ?";
    $stmt_applicants = $con->prepare($applicants_query);
    $stmt_applicants->bind_param("i", $job_id);
    $stmt_applicants->execute();
    $result_applicants = $stmt_applicants->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>JobHub: View Applicants</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .download-btn {
            background-color: #28a745; /* Green background */
            color: white; /* White icon color */
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .download-btn:hover {
            background-color: #218838; /* Darker green on hover */
        }
        .download-btn i {
            font-size: 16px; /* Adjust icon size */
        }
    </style>
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
                                <li><a class="dropdown-item" href="managepost.php">Manage Posts</a></li>
                                <li><a class="dropdown-item" href="logout.php">Sign Out</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <div class="container mt-4">
        <h1 class="display-5 text-center">Applicants for Job: <?php echo htmlspecialchars($comp_name); ?></h1>
        <div class="mt-4">
            <?php if (isset($result_applicants) && $result_applicants->num_rows > 0): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>CV</th> <!-- Add CV column -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_applicants->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td>
                                    <?php if (!empty($row['cv_path'])): ?>
                                        <?php $cvFilePath = 'cv/' . htmlspecialchars($row['cv_path']); ?>
                                        <a href="<?php echo $cvFilePath; ?>" download>
                                            <button class="download-btn" title="Download CV">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </a>
                                    <?php else: ?>
                                        No CV
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">No applicants for this job.</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
