<!DOCTYPE html>
<html lang="en">

<head>
  <title>JobHub: Home</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    /* Additional styles if needed */
    .footer-notice {
      background-color: #f8d7da;
      color: #721c24;
      padding: 1rem;
      text-align: center;
      position: fixed;
      bottom: 0;
      width: 100%;
      z-index: 1000;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .footer-notice a.btn {
      color: #007bff;
      background-color: #ffffff;
      border-color: #007bff;
      padding: 0.5rem 1.5rem;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .footer-notice a.btn:hover {
      background-color: #007bff;
      color: #ffffff;
      text-decoration: none;
    }
  </style>
</head>

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["id"])) {
    header("Location: home-guest.html");
    exit();
}

$name = htmlspecialchars($_SESSION["name"]);

// Check if user has uploaded a CV
$conn = mysqli_connect("localhost", "root", "", "jobhub");
if ($conn === false) {
    die("Connection Error: " . mysqli_connect_error());
}

$user_id = $_SESSION["id"];
$query = "SELECT cv_path FROM users WHERE id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$cvUploaded = !empty($row["cv_path"]);
?>

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
              <a class="nav-link" href="jobs1.php?jobtype=ShowAll">Jobs</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><?php echo $name; ?></a>
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

  <div class="container mt-5">
    <div class="row">
      <div class="col-md-6">
        <p class="display-4" style="color: #b03a19;">Welcome to your professional community</p>
        <div class="container" class="col-md-8" style="width: 500px;">
          <div class="container">
            <button type="button" class="btn btn-outline-primary w-100 rounded-5 mt-5" onclick="location.href='jobs1.php'">Find a new job</button>
            <button type="button" class="btn btn-outline-primary w-100 rounded-5 mt-1" onclick="location.href='postjob.php'">Post a job</button>
          </div>
        </div>
        <div class="text-center w-100 mt-4">
          <i class="text-secondary">Begin your journey now. Explore opportunities, unlock your potential, and find the job that's right for you. Start your career adventure today!</i>
        </div>
      </div>
      <div class="col-md-6">
        <img class="img-fluid mt-2" src="imgs/dxf91zhqd2z6b0bwg85ktm5s4.svg">
      </div>
    </div>
  </div>

  <div class="container-fluid mt-5" style="background-color: #f3f2f0; color:#b03a19; padding:2rem ;">
    <div class="container row mx-auto">
      <div class="container col-md-5">
        <div class="col-md-4">
          <h1 class="display-5">Explore collaborative articles</h1>
        </div>
      </div>
      <div class="container col-md-7 mt-4">
        <a href="jobs1.php?jobtype=Marketing" class="btn btn-outline-primary rounded-5 mt-1" style="padding: 0.8rem;">Marketing</a>
        <a href="jobs1.php?jobtype=Public Administration" class="btn btn-outline-primary rounded-5 mt-1" style="padding: 0.8rem;">Public Administration</a>
        <a href="jobs1.php?jobtype=Healthcare" class="btn btn-outline-primary rounded-5 mt-1" style="padding: 0.8rem;">Healthcare</a>
        <a href="jobs1.php?jobtype=Engineering" class="btn btn-outline-primary rounded-5 mt-1" style="padding: 0.8rem;">Engineering</a>
        <a href="jobs1.php?jobtype=IT Services" class="btn btn-outline-primary rounded-5 mt-1" style="padding: 0.8rem;">IT Services</a>
        <a href="jobs1.php?jobtype=Telecommunication" class="btn btn-outline-primary rounded-5 mt-1" style="padding: 0.8rem;">Telecommunication</a>
        <a href="jobs1.php?jobtype=HR Management" class="btn btn-outline-primary rounded-5 mt-1" style="padding: 0.8rem;">HR Management</a>
      </div>
    </div>
  </div>

  <div class="container-fluid mt-5">
    <div class="container row mx-auto">
      <div class="container col-md-5">
        <h1 class="display-5" style="padding: 1rem;">Post your job for millions of people to see</h1>
      </div>
      <div class="container col-md-7 mt-4">
        <button type="button" class="btn btn-outline-dark rounded-5 mt-1" style="padding: 0.8rem;" onclick="location.href='postjob.php'">Post a job</button>
      </div>
    </div>
  </div>

  <!-- Conditional Footer Notice -->
  <?php if (!$cvUploaded): ?>
  <div class="footer-notice">
    <p>Remember to upload your CV!</p>
    <a href="editprofile.php" class="btn btn-light border rounded-pill">Upload CV</a>
  </div>
  <?php endif; ?>

</body>

</html>
