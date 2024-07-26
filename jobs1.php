<?php
session_start();
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

$name = htmlspecialchars($_SESSION["name"]);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>JobHub: Jobs</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
.popup {
  display: none;
  position: fixed;
  bottom: 20px;
  left: 20px; /* Position the popup at the bottom left corner */
  background-color: #28a745;
  color: white;
  padding: 15px;
  border-radius: 5px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  z-index: 1000;
}

    .popup .btn-close {
      background: none;
      border: none;
      color: white;
      font-size: 20px;
      cursor: pointer;
    }

    .apply-btn {
      display: flex;
      align-items: center;
    }

    .apply-btn.disabled {
      background-color: #28a745;
      border: none;
      color: white;
      pointer-events: none;
    }

    .check-icon {
      display: none;
      font-size: 20px;
      margin-left: 5px;
    }

    .apply-btn.disabled .check-icon {
      display: inline;
    }

    .fixed-popup {
  display: flex;
  align-items: center;
  justify-content: center;
  position: fixed;
  bottom: 20px;
  right: 20px; /* Adjust this value to position the + button where you want */
  background-color: #007bff;
  color: white;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  cursor: pointer;
  text-align: center;
  font-size: 24px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  z-index: 1000;
  transition: background-color 0.3s ease;
}

.fixed-popup:hover {
  background-color: #0056b3;
}
  </style>

  <script>
    $(document).ready(function() {
      function attachClickListener() {
        $('.apply-btn:not(.disabled)').click(function() {
          var $this = $(this);
          $.ajax({
            type: 'POST',
            url: 'applyjob.php',
            data: {
              jobid: $this.data('jobid'),
              userid: $this.data('userid')
            },
            dataType: 'json',
            success: function(response) {
              if (response.success) {
                // Show the popup
                $('.popup').fadeIn();

                // Hide the popup after 3 seconds
                setTimeout(function() {
                  $('.popup').fadeOut();
                }, 3000);

                // Change the button to a green check and disable it
                $this.addClass('disabled');
                $this.html('Applied <span class="check-icon">&#10003;</span>');
              } else {
                console.log("Error: " + response.error);
              }
            },
            error: function(xhr, status, error) {
              console.error('Error applying for job:', error);
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
</head>

<body>
  <div class="popup">
    <button type="button" class="btn-close"></button>
    <p>You have successfully applied for the job!</p>
  </div>
  
  <div class="container p-1" style="border-bottom: solid 1px; border-color: lightgrey;">
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
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                <?php echo $name; ?>
              </a>
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
    <!-- Job listings will be dynamically loaded here -->
  </div>

  <!-- Permanent blue popup with + sign -->
  <div class="fixed-popup" onclick="window.location.href='postjob.php'">
    +
  </div>
</body>

</html>
