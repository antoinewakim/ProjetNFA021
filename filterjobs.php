<?php

$servername = "localhost";
$username = "root";
$password = ""; 
$database = "jobhub"; 

$conn = new mysqli($servername, $username, $password, $database);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['job_type'])) {
    $job_type = $_POST['job_type'];
    session_start();
    $user_id = $_SESSION["id"];
   
    if (strtolower($job_type) === 'showall') {
        $query = "SELECT id, comp_description, comp_location, job_type, comp_name, job_summary, job_experience, job_employmenttype, comp_image FROM jobs";
    } else {
        $query = "SELECT id, comp_description, comp_location, job_type, comp_name, job_summary, job_experience, job_employmenttype, comp_image FROM jobs WHERE LOWER(job_type) = LOWER(?)";
    }

    $stmt = $conn->prepare($query);
    
    if (strtolower($job_type) !== 'showall') {
        $stmt->bind_param("s", $job_type);
    }

    if ($stmt->execute()) {
        $stmt->bind_result($ID, $comp_description, $comp_location, $job_type, $comp_name, $job_summary, $job_experience, $job_employmenttype, $comp_image);

        while ($stmt->fetch()) {
            echo "
            <div class='container mt-3' style='border-bottom: solid 1px; border-color: lightgrey;'>
                <div class='container row mx-auto'>
                    <!-- Start Left Card -->
                    <div class='container col-md-5'>
                        <div class='row clickable' style='margin-top:20px; margin-bottom: 20px; padding-top: 20px; border-radius: 10px;'>
                            <div class='container col-md-2'>
                                <img class='img-fluid' src='imgs/$comp_image' alt='$comp_name'>
                            </div>
                            <div class='container col-md-10'>
                                <p style='font-size: 19px; font-weight:600;'> $comp_name </p>
                                <p style='font-size: 16px; font-weight:500;'> $comp_location </p>
                                <p style='font-size: 16px; font-weight:100;'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-geo-alt' viewBox='0 0 16 16'>
                                    <path d='M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10'/>
                                    <path d='M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6'/>
                                </svg> $comp_location </p>
                            </div>
                        </div>
                    <!-- End Left Card --> 
                    </div>
                    
                    <div class='container col-md-7 mt-2' style='margin-bottom: 1rem;'>
                        <div style='border: solid 1px lightgrey; border-radius: 20px; padding: 1rem;'>
                            <p name='comp_name' style='font-size:x-large; font-weight: bold;'> $comp_name </p>
                            <p name='job_type' style='font-size: 16px; font-weight:500;'> $job_type </p>
                            <p name='comp_location' style='font-size: 16px; font-weight:100;'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-geo-alt' viewBox='0 0 16 16'>
                                <path d='M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10'/>
                                <path d='M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6'/>
                            </svg> $comp_location </p>
                            <button type='button' class=' apply-btn btn btn-primary rounded-5' style='padding: 0.6rem; padding-left: 1rem; padding-right: 1rem;' data-jobid='$ID' data-userid='$user_id'>Apply</button>
                        </div>

                        <div>
                            <p style='font-weight: bold;' class='mt-2'>About us</p>
                            <p name='comp_description'> $comp_description </p>
                            <p style='font-weight: bold;' class='mt-2'>Job Summary</p>
                            <p name='job_summary'> $job_summary </p>
                        </div>

                        <div class='container row mt-5'>
                            <div class='container col-md-6'>
                                <div style='font-size: 18px;'>Seniority level</div>
                                <div name='job_experience' style='font-weight: bold;'> $job_experience </div>
                            </div>
                            <div class='container col-md-6'>
                                <div style='font-size: 18px;'>Employment type</div>
                                <div name='job_employmenttype' style='font-weight: bold;'> $job_employmenttype </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            ";
        }
    } else {
        echo "Error executing query: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No job type specified.";
}

$conn->close();
?>
