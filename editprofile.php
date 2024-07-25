<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "jobhub");

if ($conn === false) {
    die("Connection Error: " . mysqli_connect_error());
}

$ID = isset($_SESSION['id']) ? $_SESSION['id'] : null;

if ($ID !== null) {
    $query = "SELECT * FROM users WHERE id=?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("MySQL Error: " . $conn->error);
    }
    $stmt->bind_param("i", $ID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $first = htmlspecialchars($row["first_name"], ENT_QUOTES, 'UTF-8');
        $last = htmlspecialchars($row["last_name"], ENT_QUOTES, 'UTF-8');
        $phonenumber = htmlspecialchars($row["phone_number"], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($row["email"], ENT_QUOTES, 'UTF-8');
    } else {
        echo "<script>alert('No user found with the given session ID');</script>";
        $first = $last = $phonenumber = $email = '';
    }
} else {
    echo "<script>alert('No session found. Please log in.'); window.location.href='login.php';</script>";
    exit;
}

$updateSuccess = false;
if (isset($_POST["update"])) {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $phonenumber = $_POST["phonenumber"];

    $query = "UPDATE users SET first_name=?, last_name=?, phone_number=? WHERE id=?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("MySQL Error: " . $conn->error);
    }
    $stmt->bind_param("sssi", $firstname, $lastname, $phonenumber, $ID);
    if ($stmt->execute()) {
        // Update session variables
        $_SESSION['name'] = $firstname . ' ' . $lastname;
        $updateSuccess = true;
    } else {
        echo "<script>alert('Update failed');</script>";
    }
}

// Check if the success flag is set in the query string
$successMessage = isset($_GET['success']) && $_GET['success'] == 1;
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
        .grayed-out {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
<div class="container p-1">
    <nav class="navbar navbar-expand-sm">
        <a class="navbar-brand text-primary" href="home-user.php">JobHub</a>
    </nav>
</div>

<?php if ($successMessage): ?>
    <div class="alert alert-success" role="alert">
        Profile updated successfully!
    </div>
<?php endif; ?>

<div class="container rounded" style="margin-top: 5%; width: 400px; box-shadow: 0 1px 4px;">
    <div class="container pt-3">
        <p style="font-size: 30px;"><b>Edit Profile</b></p>
    </div>
    <form method="post">
        <div class="container pt-2">
            <input value="<?php echo $first; ?>" type="text" style="width: 100%; height: 45px; font-size: 20px; border-radius: 5px; padding-left: 10px;" placeholder="First Name" name="firstname">
            <input value="<?php echo $last; ?>" type="text" style="width: 100%; height: 45px; font-size: 20px; margin-top: 20px; border-radius: 5px; padding-left: 10px;" placeholder="Last Name" name="lastname">
            <input value="<?php echo $phonenumber; ?>" type="number" style="width: 100%; height: 45px; font-size: 20px; margin-top: 20px; border-radius: 5px; padding-left: 10px;" placeholder="Phone Number" name="phonenumber">
            <input value="<?php echo $email; ?>" type="text" style="width: 100%; height: 45px; font-size: 20px; margin-top: 20px; border-radius: 5px; padding-left: 10px;" class="grayed-out" placeholder="Email" readonly>
        </div>
        <div class="container mt-3">
            <input type="submit" class="btn btn-primary mt-3 pt-2 pb-2 col-md-12" style="border-radius: 500px;" name="update" value="Update Profile">
        </div>
    </form>
</div>
</body>
</html>

