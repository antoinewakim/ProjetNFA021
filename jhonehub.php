<?php

$servername = "localhost";
$username = "root";
$password = ""; 
$database = "jobhub"; 
// config xamp 

$conn = new mysqli($servername, $username, $password, $database);
//mnol2at dtabase

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//die bto2ta3 l connection w betkeb error 

$stmt = $conn->prepare("INSERT INTO contactus (Name,Email, Message, PhoneNumber) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $message, $phoneNumber);
// hon badna naamol insert lal database bi query insert aal contact us aam nestaamel bind la noleat l parameters men l post request li aamelneha bel html 

$name = $_POST['Name'];
$email = $_POST['Email'];
$message = $_POST['Message'];
$phoneNumber = $_POST['PhoneNumber'];
// variables aam noleaton la nfaweton aal database 

if ($stmt->execute()) {//execute lal query mn php 
    
    header('Location:contactus3.html');
    exit();//redirect aal contact us page hek shefet aa google
} else {
    echo "Error: " . $stmt->error;// la nshuf eza meshe aw la2 
}


$stmt->close();
$conn->close();// aam nsakker l connection wel binding(rakkabna variables aal parameter bel statement) li aamelneha. 
?>
