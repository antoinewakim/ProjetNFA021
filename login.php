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
        $usernameemail = $_POST["usernameemail"];
        $password = $_POST["password"];
        $result = mysqli_query($conn , "SELECT * FROM users WHERE email = '$usernameemail'");
        $row = mysqli_fetch_assoc($result);
        if(mysqli_num_rows($result) > 0){
            if($password == $row["password"]){
                $_SESSION["login"] = true;
                $_SESSION["id"] = $row["id"];
                header("Location: home-user.html");
            }
            else{
                echo
                "<script> alert('Wrong password'); </script>";
            }
        }
        else{
            echo
            "<script> alert('User Not Registered'); </script>";
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

    <div class="container p-4" style="margin-left: 1%">
    </div>
    <div class="container rounded" style="margin-top: 5%; width: 400px;box-shadow: 0 1px 4px;">
        <div class="container pt-3">
           <p style="font-size: 30px;"><b> Sign in </b></p>
           <p style="font-size: 13px ; margin-top: -5%;"> Stay updated on your professional world </p>
        </div>
        <form method="post">
            <div class="container pt-2">
                <input type="text" style="width: 100%; height: 45px; font-size: 20px; border-radius: 5px;" placeholder="Email or Phone" name="usernameemail">
                <input type="password" style="width: 100%; height: 45px; font-size: 20px; margin-top: 20px; border-radius: 5px" placeholder="Password" name="password">
            </div>
            <div class="container mt-3" style="color: #3B71CA ;">
            <b> Forgot Password? </b>
            </div>
            <div class="container">
            <input type="submit" class="btn btn-primary mt-3 pt-2 pb-2 ml-2" style="padding-left: 40%; padding-right: 40%; border-radius: 500px" name="submit"> <b> Sign in </b>
            </div>
        </form>
            <div class="container mt-2 pb-2" style="font-size: 11px;">
                By clicking Continue, you agree to LinkedIn’s User Agreement, Privacy Policy, and Cookie Policy.
            </div>
            </div>
            <div class="container mt-2" style=" width: 220px;">
                New to JobHub? <a href="signup.php"> Join now </a>
            </div>
        <div class="container mt-5" style="position: relative;">
            <img class="marketing-main__marketing-item__image__background-image" aria-hidden="true" src="https://static.licdn.com/aero-v1/sc/h/3nyyol6kogibn6bra7fzbag5a" alt="">
            <div style="position: absolute; top: 20%; left: 800px;">
                <h3>Uniquely qualified</h3>
                JubHob members are active on our network and engaged in their careers. 9 out of 10 of them are open to work, but most aren’t visiting job boards. So the only place to reach them is right here.
            </div>
   











<script>

</script>


</body>
</html>