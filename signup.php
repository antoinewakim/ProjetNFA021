<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <style>
        input {
            border:solid 1px black; 
        }

        input:focus { 
            outline: none !important;
            border-color: #3B71CA;
            box-shadow: 0 0 10px #719ECE;
        }
    </style>
</head>
<?php
    session_start();
    $conn = mysqli_connect("localhost","root","","jobhub");
    if ($conn == false) {
        die("Connection Error" . mysqli_connect_error());
    }
    if(isset($_POST["submit"])){
        $firstname = $_POST["firstname"];
        $lastname = $_POST["lastname"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $phonenumber = $_POST["phonenumber"];
        $confirmpassword = $_POST["confirmpassword"];
        $duplicate = mysqli_query($conn, "SELECT * FROM users WHERE email = '$username'");
        if(mysqli_num_rows($duplicate) > 0){
            echo
            "<script> alert('Username or Email Has Already Taken');</script>";
        }
        else if($password == $confirmpassword){
            $query = "INSERT INTO users (first_name, last_name, phone_number, email, password) VALUES ('$firstname', '$lastname', '$phonenumber', '$username', '$password')";
            mysqli_query($conn,$query);
            echo
            header("Location: login.php");
        }
        else{
            echo
            "<script> alert('Password Does Not Match'); </script>";
        }
    }

?>
<body>
    <div class="container p-1">
        <nav class="navbar navbar-expand-sm" style="font-weight: 500;">
        <div class="container-fluid">
            <a class="navbar-brand text-primary" href="home-guest.html">JobHub</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
            </button>
        </div>    
        </nav>
    </div>
    <div class="container rounded" style="margin-top: 5%; width: 400px;box-shadow: 0 1px 4px;">
        <div class="container pt-3">
           <p style="font-size: 30px;"><b> Sign up </b></p>
           <p style="font-size: 13px ; margin-top: -5%;"> Stay updated on your professional world </p>
        </div>
        <form method="post">
            <div class="container pt-2">
                <input type="text" style="width: 100%; height: 45px; font-size: 20px; border-radius: 5px; padding-left: 10px;" placeholder="First Name" name="firstname">
                <input type="text" style="width: 100%; height: 45px; font-size: 20px; margin-top: 20px; border-radius: 5px; padding-left: 10px" placeholder="Last Name" name="lastname">
                <input type="number" style="width: 100%; height: 45px; font-size: 20px; margin-top: 20px; border-radius: 5px; padding-left: 10px" placeholder="Phone Number" name="phonenumber">
                <input type="text" style="width: 100%; height: 45px; font-size: 20px; margin-top: 20px; border-radius: 5px;; padding-left: 10px" placeholder="Email" name="username">
                <input type="password" style="width: 100%; height: 45px; font-size: 20px; margin-top: 20px; border-radius: 5px; padding-left: 10px" placeholder="Password" name="password">
                <input type="password" style="width: 100%; height: 45px; font-size: 20px; margin-top: 20px; border-radius: 5px; padding-left: 10px" placeholder="Confirm Password" name="confirmpassword">
            </div>
            <div class="container mt-1 ">
                <input type="checkbox" id="calendar" name="calendar">
                <label for="calendar"><b> I agree to the terms and conditions </b></label>
            </div>
            <div class="container mt-1" style="font-size: 11px;">
                By clicking Agree & Join or Continue, you agree to the LinkedIn User Agreement, Privacy Policy, and Cookie Policy.Agree & Join
            </div>
            <div class="container">
            <input type="submit" class="btn btn-primary mt-3 pt-2 pb-2 col-md-12" style="border-radius: 500px" name="submit"> 
            <div class="mt-1 pb-2 col-md-12" style="text-align: center;"><b> Agree & Join </b></div>
        </form>
        </div>
        <div class="container mt-2" style="font-size: 15px;">
            Already on JobHub? <a href="login.php"> Sign in </a>
        </div>
    </br>
    </div>
    
    </div>

   











<script>

</script>
</body>
</html>