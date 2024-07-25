<?php
$con = mysqli_connect("localhost", "root", "", "jobhub");
if ($con == false) {
    die("Connection Error" . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $comp_name = mysqli_real_escape_string($con, $_POST['comp_name']);
    $comp_location = mysqli_real_escape_string($con, $_POST['comp_location']);
    $comp_description = mysqli_real_escape_string($con, $_POST['comp_description']);
    $job_summary = mysqli_real_escape_string($con, $_POST['job_summary']);
    $job_type = mysqli_real_escape_string($con, $_POST['job_type']);
    $job_experience = mysqli_real_escape_string($con, $_POST['job_experience']);
    $job_employmenttype = mysqli_real_escape_string($con, $_POST['job_employmenttype']);
    
    $comp_image = $_FILES['comp_image']['name'];
    $target_dir = "imgs/";
    $target_file = $target_dir . basename($comp_image);
  
    $query = "INSERT INTO jobs (comp_name, comp_location, comp_description, job_summary, job_type, job_experience, job_employmenttype, comp_image) VALUES ('$comp_name', '$comp_location', '$comp_description', '$job_summary', '$job_type', '$job_experience', '$job_employmenttype', '$comp_image')";

    if (mysqli_query($con, $query)) {
        echo "<script>alert('Data inserted successfully')</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>JobHub: Post a job</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container p-1">
    <nav class="navbar navbar-expand-sm" style="font-weight: 500;">
      <div class="container-fluid">
        <a class="navbar-brand text-primary" href="home-user.php">JobHub</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="jobs.html">Jobs</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">User</a>
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
          <h1 class="display-5" style="font-weight:400;">Start your job post</h1> 
        </div>
        <div class="container bg-white rounded-3 p-2" style="width: 400px; padding-bottom: 20px !important;">
          <div class="mt-2">Company Name <span class="text-primary">*</span></div>
          <input name="comp_name" type="text" class="form-control">
          <div class="mt-2">Location <span class="text-primary">*</span></div>
          <input name="comp_location"  type="text" class="form-control">
          <div class="mt-2">Company Logo <span class="text-primary">*</span></div>
          <input type="file" name="comp_image" class="form-control" accept="image/*">
          <div class="mt-2">Company Description <span class="text-primary">*</span></div>
          <textarea name="comp_description" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
          <div class="mt-2">Job Summary <span class="text-primary">*</span></div>
          <textarea name="job_summary" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
          <div class="mt-2">Job Type <span class="text-primary">*</span></div>
          <select name="job_type" class="form-select">
            <option>Marketing</option>
            <option>Public Administration</option>
            <option>Healthcare</option>
            <option>Engineering</option>
            <option>IT Services</option>
            <option>Telecommunication</option>
            <option>HR Management</option>
          </select>
          <div class="mt-2">Experience level <span class="text-primary">*</span></div>
          <select name="job_experience" class="form-select">
            <option>Entry Level</option>
            <option>Intership</option>
            <option>Associate</option>
            <option>Mid-Senior level</option>
            <option>Director</option>
          </select>

          <div class="mt-2">Employment type<span class="text-primary">*</span></div>
          <select name="job_employmenttype" class="form-select">
            <option>Full-time</option>
            <option>Part-time</option>
            <option>Online</option>
          </select>

          <div class="container text-center mt-4">
            <button type="submit" name="submit" class="btn btn-outline-primary rounded-5" style="padding: 10px 40px 10px 40px;">Post Job</button>
          </div>
        </div>
    </div>
  </form>

  <div class="container row mx-auto mt-4" style="font-size: 20px;">
    <div class="col">
      Targeted job promotion: <span class="text-secondary">targeted promotion to surface your job to candidates with the right skills, both in emails and across JobHub</span>
    </div>
    <div class="col">
      Recommended Matches: <span class="text-secondary">candidate recommendations that get smarter over time, providing personalized results.</span>
    </div>
    <div class="col">
      Candidate Management: <span class="text-secondary">a simple dashboard that tracks everyone from application to hire, all in one place.</span>
    </div>
  </div>

</body>
</html>