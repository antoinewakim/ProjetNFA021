<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "jobhub");

if ($conn === false) {
    die("Connection Error: " . mysqli_connect_error());
}

// Check if the admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}

// Get the number of users
$userCountResult = mysqli_query($conn, "SELECT COUNT(*) AS total_users FROM users");
$userCountRow = mysqli_fetch_assoc($userCountResult);
$totalUsers = $userCountRow['total_users'];

// Get the number of jobs
$jobCountResult = mysqli_query($conn, "SELECT COUNT(*) AS total_jobs FROM jobs");
$jobCountRow = mysqli_fetch_assoc($jobCountResult);
$totalJobs = $jobCountRow['total_jobs'];

// Handle user deletion
if (isset($_GET['delete_user'])) {
    $userId = mysqli_real_escape_string($conn, $_GET['delete_user']);
    $deleteUserQuery = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($deleteUserQuery);
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('User deleted successfully'); window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('Error deleting user');</script>";
    }
}

// Handle job deletion
if (isset($_GET['delete_job'])) {
    $jobId = mysqli_real_escape_string($conn, $_GET['delete_job']);
    $deleteJobQuery = "DELETE FROM jobs WHERE id = ?";
    $stmt = $conn->prepare($deleteJobQuery);
    if ($stmt) {
        $stmt->bind_param("i", $jobId);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('Job deleted successfully'); window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('Error deleting job');</script>";
    }
}

// Fetch all users
$userQuery = "SELECT * FROM users";
$userResult = mysqli_query($conn, $userQuery);

// Fetch all jobs
$jobQuery = "SELECT * FROM jobs";
$jobResult = mysqli_query($conn, $jobQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - JobHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/x-icon" href="imgs/favicon.ico">
    <style>
        .table thead th {
            vertical-align: middle;
            text-align: center;
        }
        .table td, .table th {
            vertical-align: middle;
            text-align: center;
        }
        .action-btns {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Admin Dashboard</h1>
        <a href="admin-logout.php" class="btn btn-danger">Logout</a>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text"><?php echo $totalUsers; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Jobs</h5>
                    <p class="card-text"><?php echo $totalJobs; ?></p>
                </div>
            </div>
        </div>
    </div>

    <h2>All Users</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($userRow = mysqli_fetch_assoc($userResult)): ?>
                <tr>
                    <td><?php echo $userRow['id']; ?></td>
                    <td><?php echo htmlspecialchars($userRow['first_name'] . ' ' . $userRow['last_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($userRow['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($userRow['phone_number'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="action-btns">
                        <a href="admin.php?delete_user=<?php echo $userRow['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h2>All Jobs</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Company Name</th>
                <th>Job Type</th>
                <th>Location</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($jobRow = mysqli_fetch_assoc($jobResult)): ?>
                <tr>
                    <td><?php echo $jobRow['id']; ?></td>
                    <td><?php echo htmlspecialchars($jobRow['comp_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($jobRow['job_type'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($jobRow['comp_location'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="action-btns">
                        <a href="admin.php?delete_job=<?php echo $jobRow['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this job?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
mysqli_close($conn);
?>
