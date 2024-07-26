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
if ($con === false) {
    die("Connection Error: " . mysqli_connect_error());
}

// Retrieve user details
$user_id = $_SESSION["id"];
$user_name = isset($_SESSION["name"]) ? $_SESSION["name"] : ''; // Ensure $user_name is set

// Handle post deletion
if (isset($_GET['delete'])) {
    $job_id = mysqli_real_escape_string($con, $_GET['delete']);
    $delete_query = "DELETE FROM jobs WHERE id = ? AND user_id = ?";
    $stmt = $con->prepare($delete_query);
    if ($stmt) {
        $stmt->bind_param("ii", $job_id, $user_id);
        if ($stmt->execute()) {
            echo "<script>alert('Job post deleted successfully'); window.location.href='managepost.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Failed to prepare delete statement');</script>";
    }
}

// Fetch user's job posts
$query = "SELECT * FROM jobs WHERE user_id = ?";
$stmt = $con->prepare($query);
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
    } else {
        echo "Error executing query: " . $stmt->error;
        $result = null;
    }
    $stmt->close();
} else {
    echo "Error preparing query: " . mysqli_error($con);
    $result = null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>JobHub: Manage Your Posts</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .action-icons {
            font-size: 1.2rem; /* Adjust the size of the icons */
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
                <li><a class="dropdown-item" href="editprofile.php">Edit Profile</a></li>
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
        <h1 class="display-5 text-center">Manage Your Job Posts</h1>
        <div class="mt-4">
            <?php if ($result && $result->num_rows > 0): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Company Name</th>
                            <th>Location</th>
                            <th>Description</th>
                            <th>Summary</th>
                            <th>Type</th>
                            <th>Experience</th>
                            <th>Employment Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['comp_name'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['comp_location'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['comp_description'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['job_summary'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['job_type'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['job_experience'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['job_employmenttype'] ?? ''); ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="editpost.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm me-2 action-icons" title="Edit"><i class="fas fa-edit"></i></a>
                                        <a href="view_applicants.php?job_id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm me-2 action-icons" title="View Applicants"><i class="fas fa-users"></i></a>
                                        <a href="managepost.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm action-icons" onclick="return confirm('Are you sure you want to delete this post?')" title="Delete"><i class="fas fa-trash-alt"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">You have not posted any jobs yet.</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
