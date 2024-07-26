<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "jobhub");

if ($conn === false) {
    die("Connection Error: " . mysqli_connect_error());
}

$signupError = '';

if (isset($_POST["submit"])) {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $phonenumber = $_POST["phonenumber"];
    $confirmpassword = $_POST["confirmpassword"];
    $terms = isset($_POST["calendar"]); // Check if the checkbox is checked

    // Validate inputs
    if (empty($firstname) || empty($lastname) || empty($username) || empty($password) || empty($phonenumber) || empty($confirmpassword)) {
        $signupError = 'Please fill in all fields.';
    } else if ($password !== $confirmpassword) {
        $signupError = 'Password does not match.';
    } else if (!$terms) {
        $signupError = 'You must agree to the terms and conditions.';
    } else {
        $duplicate = mysqli_query($conn, "SELECT * FROM users WHERE email = '$username'");
        if (mysqli_num_rows($duplicate) > 0) {
            $signupError = 'Username or Email has already been taken.';
        } else {
            $query = "INSERT INTO users (first_name, last_name, phone_number, email, password) VALUES ('$firstname', '$lastname', '$phonenumber', '$username', '$password')";
            mysqli_query($conn, $query);
            header("Location: login.php");
            exit(); // Ensure no further code is executed
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <style>
        input {
            border: solid 1px black; 
        }

        input:focus { 
            outline: none !important;
            border-color: #3B71CA;
            box-shadow: 0 0 10px #719ECE;
        }

        .popup {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #dc3545;
            color: white;
            padding: 15px;
            border-radius: 5px;
            display: none;
            z-index: 1000;
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
            </div>    
        </nav>
    </div>
    <div class="container rounded" style="margin-top: 5%; width: 400px; box-shadow: 0 1px 4px;">
        <div class="container pt-3">
            <p style="font-size: 30px;"><b>Sign up</b></p>
            <p style="font-size: 13px; margin-top: -5%;">Stay updated on your professional world</p>
        </div>
        <form id="signupForm" method="post">
            <div class="container pt-2">
                <input type="text" id="firstname" style="width: 100%; height: 45px; font-size: 20px; border-radius: 5px; padding-left: 10px;" placeholder="First Name" name="firstname">
                <input type="text" id="lastname" style="width: 100%; height: 45px; font-size: 20px; margin-top: 20px; border-radius: 5px; padding-left: 10px" placeholder="Last Name" name="lastname">
                <input type="number" id="phonenumber" style="width: 100%; height: 45px; font-size: 20px; margin-top: 20px; border-radius: 5px; padding-left: 10px" placeholder="Phone Number" name="phonenumber">
                <input type="text" id="username" style="width: 100%; height: 45px; font-size: 20px; margin-top: 20px; border-radius: 5px; padding-left: 10px" placeholder="Email" name="username">
                <input type="password" id="password" style="width: 100%; height: 45px; font-size: 20px; margin-top: 20px; border-radius: 5px; padding-left: 10px" placeholder="Password" name="password">
                <input type="password" id="confirmpassword" style="width: 100%; height: 45px; font-size: 20px; margin-top: 20px; border-radius: 5px; padding-left: 10px" placeholder="Confirm Password" name="confirmpassword">
            </div>
            <div class="container mt-1">
                <input type="checkbox" id="calendar" name="calendar">
                <label for="calendar"><b>I agree to the terms and conditions</b></label>
            </div>
            <div class="container mt-1" style="font-size: 11px;">
                By clicking Agree & Join or Continue, you agree to the LinkedIn User Agreement, Privacy Policy, and Cookie Policy.
            </div>
            <div class="container">
                <input type="submit" class="btn btn-primary mt-3 pt-2 pb-2 col-md-12" style="border-radius: 500px" name="submit"> 
                <div class="mt-1 pb-2 col-md-12" style="text-align: center;"><b>Agree & Join</b></div>
            </div>
        </form>
        <div class="container mt-2" style="font-size: 15px;">
            Already on JobHub? <a href="login.php">Sign in</a>
        </div>
    </div>
    
    <div id="popup" class="popup"></div>

    <script>
        function showPopup(message) {
            var popup = document.getElementById('popup');
            popup.textContent = message;
            popup.style.display = 'block';
            setTimeout(function() {
                popup.style.display = 'none';
            }, 5000); // Hide popup after 5 seconds
        }

        document.getElementById('signupForm').addEventListener('submit', function(event) {
            var firstname = document.getElementById('firstname').value.trim();
            var lastname = document.getElementById('lastname').value.trim();
            var username = document.getElementById('username').value.trim();
            var password = document.getElementById('password').value.trim();
            var confirmpassword = document.getElementById('confirmpassword').value.trim();
            var terms = document.getElementById('calendar').checked;

            if (firstname === '' || lastname === '' || username === '' || password === '' || confirmpassword === '' || !terms) {
                event.preventDefault(); // Prevent form submission
                showPopup('Please fill in all fields and agree to the terms.');
            } else if (password !== confirmpassword) {
                event.preventDefault(); // Prevent form submission
                showPopup('Passwords do not match.');
            }
        });

        <?php if ($signupError): ?>
            showPopup('<?php echo $signupError; ?>');
        <?php endif; ?>
    </script>
</body>
</html>
