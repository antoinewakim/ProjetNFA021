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
        $first = isset($row["first_name"]) ? htmlspecialchars($row["first_name"], ENT_QUOTES, 'UTF-8') : '';
        $last = isset($row["last_name"]) ? htmlspecialchars($row["last_name"], ENT_QUOTES, 'UTF-8') : '';
        $phonenumber = isset($row["phone_number"]) ? htmlspecialchars($row["phone_number"], ENT_QUOTES, 'UTF-8') : '';
        $email = isset($row["email"]) ? htmlspecialchars($row["email"], ENT_QUOTES, 'UTF-8') : '';
        $cvPath = isset($row["cv_path"]) ? htmlspecialchars($row["cv_path"], ENT_QUOTES, 'UTF-8') : '';
    } else {
        echo "<script>alert('No user found with the given session ID');</script>";
        $first = $last = $phonenumber = $email = $cvPath = '';
    }
} else {
    echo "<script>alert('No session found. Please log in.'); window.location.href='login.php';</script>";
    exit;
}

$updateSuccess = false;
$uploadError = false;
if (isset($_POST["update"])) {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $phonenumber = $_POST["phonenumber"];
    
    // Check if a CV is uploaded
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'cv/';
        $cvFileName = basename($_FILES['cv']['name']);
        $uploadFile = $uploadDir . $cvFileName;

        if (move_uploaded_file($_FILES['cv']['tmp_name'], $uploadFile)) {
            // File uploaded successfully
            $cvFileName = $cvFileName;
            $updateSuccess = true;
        } else {
            $uploadError = true;
            $cvFileName = $cvPath; // Preserve existing file name if upload failed
        }
    } else {
        $cvFileName = $cvPath; // Use existing CV file path if no new file is uploaded
    }

    $query = "UPDATE users SET first_name=?, last_name=?, phone_number=?, cv_path=? WHERE id=?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("MySQL Error: " . $conn->error);
    }
    $stmt->bind_param("ssssi", $firstname, $lastname, $phonenumber, $cvFileName, $ID);
    if ($stmt->execute() && !$uploadError) {
        $_SESSION['name'] = $firstname . ' ' . $lastname;
        $updateSuccess = true;
    } else {
        $updateSuccess = false;
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
        .grayed-out {
            background-color: #f0f0f0;
        }
        .drop-zone {
            width: 100%;
            height: 100px;
            border: 2px dashed #007bff;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #007bff;
            font-size: 18px;
            cursor: pointer;
            text-align: center;
        }
        .drop-zone.dragover {
            border-color: #0056b3;
            background-color: #e9ecef;
        }
        .drop-zone p {
            margin: 0;
        }
        .popup {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #28a745;
            color: white;
            padding: 15px;
            border-radius: 5px;
            display: none;
            z-index: 1000;
        }
        .popup.error {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
<div class="container p-1">
    <nav class="navbar navbar-expand-sm">
        <a class="navbar-brand" href="home-user.php">
            <img src="imgs/JobHubLogo.png" alt="JobHub Logo" style="height: 80px;">
        </a>
    </nav>
</div>

<div class="container rounded" style="margin-top: 5%; width: 400px; box-shadow: 0 1px 4px;">
    <div class="container pt-3">
        <p style="font-size: 30px;"><b>Edit Profile</b></p>
    </div>
    <form method="post" enctype="multipart/form-data">
        <div class="container pt-2">
            <input value="<?php echo htmlspecialchars($first, ENT_QUOTES, 'UTF-8'); ?>" type="text" style="width: 100%; height: 45px; font-size: 20px; border-radius: 5px; padding-left: 10px;" placeholder="First Name" name="firstname">
            <input value="<?php echo htmlspecialchars($last, ENT_QUOTES, 'UTF-8'); ?>" type="text" style="width: 100%; height: 45px; font-size: 20px; margin-top: 20px; border-radius: 5px; padding-left: 10px;" placeholder="Last Name" name="lastname">
            <input value="<?php echo htmlspecialchars($phonenumber, ENT_QUOTES, 'UTF-8'); ?>" type="number" style="width: 100%; height: 45px; font-size: 20px; margin-top: 20px; border-radius: 5px; padding-left: 10px;" placeholder="Phone Number" name="phonenumber">
            <input value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" type="text" style="width: 100%; height: 45px; font-size: 20px; margin-top: 20px; border-radius: 5px; padding-left: 10px;" class="grayed-out" placeholder="Email" readonly>
            
            <div class="drop-zone" id="drop-zone" style="margin-top: 20px;">
                <p>Drag & drop your CV here or click to upload</p>
                <input type="file" name="cv" id="file-input" style="display: none;">
            </div>
            <?php if ($cvPath): ?>
                <p>Current CV: <a href="cv/<?php echo htmlspecialchars($cvPath, ENT_QUOTES, 'UTF-8'); ?>" target="_blank"><?php echo htmlspecialchars($cvPath, ENT_QUOTES, 'UTF-8'); ?></a></p>
            <?php endif; ?>
        </div>
        <div class="container mt-3">
            <input type="submit" class="btn btn-primary mt-3 pt-2 pb-2 col-md-12" style="border-radius: 500px; margin-bottom: 20px;" name="update" value="Update Profile">
        </div>
    </form>
</div>

<div id="popup" class="popup"></div>

<script>
    document.getElementById('drop-zone').addEventListener('click', function() {
        document.getElementById('file-input').click();
    });

    document.getElementById('file-input').addEventListener('change', function() {
        document.getElementById('drop-zone').querySelector('p').textContent = this.files[0].name;
    });

    document.getElementById('drop-zone').addEventListener('dragover', function(event) {
        event.preventDefault();
        this.classList.add('dragover');
    });

    document.getElementById('drop-zone').addEventListener('dragleave', function() {
        this.classList.remove('dragover');
    });

    document.getElementById('drop-zone').addEventListener('drop', function(event) {
        event.preventDefault();
        this.classList.remove('dragover');
        var file = event.dataTransfer.files[0];
        document.getElementById('file-input').files = event.dataTransfer.files;
        this.querySelector('p').textContent = file.name;
    });

    function showPopup(message, isError = false) {
        var popup = document.getElementById('popup');
        popup.textContent = message;
        popup.className = 'popup ' + (isError ? 'error' : '');
        popup.style.display = 'block';
        setTimeout(function() {
            popup.style.display = 'none';
        }, 5000); // Hide popup after 5 seconds
    }

    // Check if the PHP variables are set to show the correct popup
    var updateSuccess = <?php echo json_encode($updateSuccess); ?>;
    var uploadError = <?php echo json_encode($uploadError); ?>;

    if (updateSuccess) {
        showPopup('Profile updated successfully!');
    } else if (uploadError) {
        showPopup('Error uploading file. Please try again.', true);
    }
</script>
</body>
</html>
