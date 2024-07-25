<!DOCTYPE html>
<html lang="en">

<head>
  <title>JobHub: Jobs</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    $(document).ready(function() {
      function attachClickListener() {
        $('.apply-btn').click(function() {
          var jobid = $(this).data('jobid');
          var userid = $(this).data('userid');

          $.ajax({
            type: 'POST',
            url: 'insertjob.php',
            data: { jobid: jobid, userid: userid },
            success: function(response) {
              console.log('Data inserted successfully');
              alert("Data inserted successfully");
            },
            error: function(xhr, status, error) {
              console.error('Error inserting data:', error);
            }
          });
        });
      }

      $('.dropdown-item').click(function() {
        var jobType = $(this).data('type');

        $.ajax({
          type: 'POST',
          url: 'filterjobs.php',
          data: { job_type: jobType },
          success: function(response) {
            $('.jobs-container').html(response);
            attachClickListener();
          },
          error: function(xhr, status, error) {
            console.error('Error filtering jobs:', error);
          }
        });
      });

      // Activate the job type filter if query parameter is present
      const urlParams = new URLSearchParams(window.location.search);
      const jobType = urlParams.get('jobtype') || 'ShowAll';
      $.ajax({
        type: 'POST',
        url: 'filterjobs.php',
        data: { job_type: jobType },
        success: function(response) {
          $('.jobs-container').html(response);
          attachClickListener();
        },
        error: function(xhr, status, error) {
          console.error('Error filtering jobs:', error);
        }
      });
    });
  </script>

  <style>
    .clickable:hover {
      background-color: rgb(218, 235, 252);
    }
  </style>
</head>

<?php
session_start();
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "jobhub";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if a job type is set in the query parameters
$jobType = isset($_GET['jobtype']) ? $_GET['jobtype'] : 'ShowAll';

if ($jobType === 'ShowAll') {
    $sql = "SELECT * FROM jobs";
    $stmt = $conn->prepare($sql);
} else {
    $sql = "SELECT * FROM jobs WHERE job_type = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $jobType);
}

if ($stmt->execute()) {
    $result = $stmt->get_result();
} else {
    echo "Error executing query: " . $stmt->error;
    exit();
}

$user_id = $_SESSION["id"];
$name = $_SESSION["name"];
?>

<body>
    <div class="container p-1" style="border-bottom: solid 1px; border-color: lightgrey;">
        <nav class="navbar navbar-expand-sm" style="font-weight: 500;">
            <div class="container-fluid">
                <a class="navbar-brand text-primary" href="home-user.php">JobHub</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="collapsibleNavbar">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="jobs1.php?jobtype=ShowAll">Jobs</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <?php echo htmlspecialchars($name); ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="editprofile.php">Edit Profile</a></li>
                                <li><a class="dropdown-item" href="logout.php">Sign Out</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="btn-group">
            <button type="button" class="btn btn-success dropdown-toggle rounded-5 mt-2" data-bs-toggle="dropdown">Job type</button>
            <div class="dropdown-menu">
                <a class="dropdown-item" data-type="ShowAll" href="#">Show All</a>
                <a class="dropdown-item" data-type="Marketing" href="#">Marketing</a>
                <a class="dropdown-item" data-type="Healthcare" href="#">Healthcare</a>
                <a class="dropdown-item" data-type="Public Administration" href="#">Public Administration</a>
                <a class="dropdown-item" data-type="Engineering" href="#">Engineering</a>
                <a class="dropdown-item" data-type="IT Services" href="#">IT Services</a>
                <a class="dropdown-item" data-type="Telecommunication" href="#">Telecommunication</a>
                <a class="dropdown-item" data-type="HR Management" href="#">HR Management</a>
            </div>
        </div>
    </div>
    <div class="jobs-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "
                <div class='container mt-3' style='border-bottom: solid 1px; border-color: lightgrey;'>
                    <div class='container row mx-auto'>
                        <div class='container col-md-5'>
                            <div class='row clickable' style='margin-top:20px; margin-bottom: 20px; padding-top: 20px; border-radius: 10px;'>
                                <div class='container col-md-2'>
                                    <img class='img-fluid' src='imgs/" . htmlspecialchars($row['comp_image']) . "' alt='" . htmlspecialchars($row['comp_name']) . "'>
                                </div>
                                <div class='container col-md-10'>
                                    <p style='font-size: 19px; font-weight: 500;'>" . htmlspecialchars($row['job_title']) . "</p>
                                    <p style='font-size: 16px;'>Job Type: " . htmlspecialchars($row['job_type']) . "</p>
                                    <p style='font-size: 16px;'>Company Name: " . htmlspecialchars($row['comp_name']) . "</p>
                                    <p style='font-size: 16px;'>Job Location: " . htmlspecialchars($row['job_location']) . "</p>
                                    <a href='#' class='apply-btn btn btn-primary' data-jobid='" . $row['job_id'] . "' data-userid='" . $user_id . "'>Apply</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>";
            }
        } else {
            echo "No jobs available.";
        }
        ?>
    </div>
</body>

</html>
