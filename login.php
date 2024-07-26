<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "jobhub");
if ($conn === false) {
    die("Connection Error: " . mysqli_connect_error());
}

$loginError = '';

if (isset($_POST["submit"])) {
    $usernameemail = $_POST["usernameemail"];
    $password = $_POST["password"];

    // Fetch user details including the is_admin flag
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$usernameemail'");
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
        if ($password == $row["password"]) {
            $_SESSION["login"] = true;
            $_SESSION["id"] = $row["id"];
            $_SESSION["name"] = $row["first_name"] . ' ' . $row["last_name"];

            // Check if the user is an admin and set an admin session variable
            if ($row["is_admin"] == 1) {
                $_SESSION["admin"] = true;
            }

            header("Location: home-user.php");
            exit(); // Ensure no further code is executed
        } else {
            $loginError = 'Wrong password';
        }
    } else {
        $loginError = 'User Not Registered';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
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

<div class="container p-4" style="margin-left: 1%">
</div>
<div class="container rounded" style="margin-top: 5%; width: 400px; box-shadow: 0 1px 4px;">
    <div class="container pt-3">
        <p style="font-size: 30px;"><b>Sign in</b></p>
        <p style="font-size: 13px; margin-top: -5%;">Stay updated on your professional world</p>
    </div>
    <form method="post">
        <div class="container pt-2">
            <input type="text" style="width: 100%; height: 45px; font-size: 20px; border-radius: 5px;" placeholder="Email or Phone" name="usernameemail">
            <input type="password" style="width: 100%; height: 45px; font-size: 20px; margin-top: 20px; border-radius: 5px" placeholder="Password" name="password">
        </div>
        <div class="container mt-3" style="color: #3B71CA;">
            <b>Forgot Password?</b>
        </div>
        <div class="container">
            <input type="submit" class="btn btn-primary mt-3 pt-2 pb-2 ml-2" style="padding-left: 40%; padding-right: 40%; border-radius: 500px" name="submit" value="Sign in">
        </div>
    </form>
    <div class="container mt-2 pb-2" style="font-size: 11px;">
        By clicking Continue, you agree to LinkedIn’s User Agreement, Privacy Policy, and Cookie Policy.
    </div>
</div>
<div class="container mt-2" style="width: 220px;">
    New to JobHub? <a href="signup.php">Join now</a>
</div>
<div class="container mt-5" style="position: relative;">
    <img class="marketing-main__marketing-item__image__background-image" aria-hidden="true" src="https://static.licdn.com/aero-v1/sc/h/3nyyol6kogibn6bra7fzbag5a" alt="">
    <div style="position: absolute; top: 20%; left: 800px;">
        <h3>Uniquely qualified</h3>
        JubHob members are active on our network and engaged in their careers. 9 out of 10 of them are open to work, but most aren’t visiting job boards. So the only place to reach them is right here.
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

    <?php if ($loginError): ?>
        showPopup('<?php echo $loginError; ?>');
    <?php endif; ?>
</script>

</body>
</html>
