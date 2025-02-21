<?php
require "../home/email_send.php";
session_start();
// Database connection
require '../register_and_login/dbconnection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from form
    // Capture POST fields
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $district = isset($_POST['district']) ? trim($_POST['district']) : '';
    $electionRegion = isset($_POST['regionNo']) ? trim($_POST['regionNo']) : '';
    $localAddress = isset($_POST['local_address']) ? trim($_POST['local_address']) : '';
    $citizenshipNumber = isset($_POST['citizenshipNumber']) ? trim($_POST['citizenshipNumber']) : '';
    $dateOfBirth = isset($_POST['dateOfBirth']) ? trim($_POST['dateOfBirth']) : '';

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    //get the name only from the email to uniquely identify images
    $emailParts = explode('@', $email);
    $username = $emailParts[0];

    // Image uploads handling
    $citizenshipFrontPhoto = isset($_FILES['citizenshipFrontPhoto']['name']) ? $_FILES['citizenshipFrontPhoto']['name'] : '';
    $citizenshipBackPhoto = isset($_FILES['citizenshipBackPhoto']['name']) ? $_FILES['citizenshipBackPhoto']['name'] : '';
    $userPhoto = isset($_FILES['userPhoto']['name']) ? $_FILES['userPhoto']['name'] : '';

    // Directory where images will be stored
    $target_dir = "../uploads/";
    // Extract file extensions
    $citizenshipFrontExt = pathinfo($citizenshipFrontPhoto, PATHINFO_EXTENSION);
    $citizenshipBackExt = pathinfo($citizenshipBackPhoto, PATHINFO_EXTENSION);
    $userPhotoExt = pathinfo($userPhoto, PATHINFO_EXTENSION);

    // Rename files with the provided username
    $newCitizenshipFrontPhoto = $username . "CitizenshipFrontPhoto." . $citizenshipFrontExt;
    $newCitizenshipBackPhoto = $username . "CitizenshipBackPhoto." . $citizenshipBackExt;
    $newUserPhoto = $username . "UserPhoto." . $userPhotoExt;

// Initialize a flag to check for empty values
$isEmpty = false;

// Check POST values
$postFields = [
    'Name' => $name,
    'Gender' => $gender,
    'Email' => $email,
    'Password' => $password,
    'District' => $district,
    'Election Region' => $electionRegion,
    'Local Address' => $localAddress,
    'Citizenship Number' => $citizenshipNumber,
    'Date of Birth' => $dateOfBirth
];

foreach ($postFields as $key => $value) {
    if (empty($value)) {
        $isEmpty = true;
    }
}

// Check FILES values
$fileFields = [
    'Citizenship Front Photo' => $citizenshipFrontPhoto,
    'Citizenship Back Photo' => $citizenshipBackPhoto,
    'User Photo' => $userPhoto
];

foreach ($fileFields as $key => $value) {
    if (empty($value)) {
        $isEmpty = true;
    }
}

// Final output
if (!$isEmpty) {
        // Check if email or citizenship number already exists for registered voters
        $sql1 = "SELECT email, citizenshipNumber FROM voters WHERE email = '$email' OR citizenshipNumber = '$citizenshipNumber'";
        $result1 = mysqli_query($conn, $sql1);
    
        // Check if email or citizenship number already exists for pending account
        $sql2 = "SELECT email, citizenshipNumber FROM pendingVoters WHERE email = '$email' OR citizenshipNumber = '$citizenshipNumber'";
        $result2 = mysqli_query($conn, $sql2);
    
        if (mysqli_num_rows($result1) > 0) {
            $row1 = mysqli_fetch_assoc($result1);
            if ($email == $row1['email'] && $citizenshipNumber == $row1['citizenshipNumber']) {
                $_SESSION['error_message'] = 'Email and Citizenship already used';
                header('Location: ../register_and_login/voter_register_form.php');
            } else if ($email == $row1['email']) {
                $_SESSION['error_message'] = 'This email is already registered.';
                header('Location: ../register_and_login/voter_register_form.php');
            } elseif ($citizenshipNumber == $row1['citizenshipNumber']) {
                $_SESSION['error_message'] = 'This citizenship number is already registered.';
                header('Location: ../register_and_login/voter_register_form.php');
            }
        } else if (mysqli_num_rows($result2) > 0) {
            $row2 = mysqli_fetch_assoc($result2);
            if ($email == $row2['email'] && $citizenshipNumber == $row2['citizenshipNumber']) {
                $_SESSION['error_message'] = 'Email and Citizenship already used and is in pending status';
                header('Location: ../register_and_login/voter_register_form.php');
            } else if ($email == $row2['email']) {
                $_SESSION['error_message'] = 'Email already used and is in pending status';
                header('Location: ../register_and_login/voter_register_form.php');
            } elseif ($citizenshipNumber == $row2['citizenshipNumber']) {
                $_SESSION['error_message'] = 'Citizenship already used and is in pending status';
                header('Location: ../register_and_login/voter_register_form.php');
            }
        } else {
            // Start transaction
            mysqli_begin_transaction($conn);

            // Step 1: Retrieve the dId from the district table
            $d_query = "SELECT dId FROM district WHERE district = '$district' AND regionNo = '$electionRegion'";
            $d_result = mysqli_query($conn, $d_query);
    
            if (mysqli_num_rows($d_result) > 0) {
                $row = mysqli_fetch_assoc($d_result);
                $dId = $row['dId'];
                $voter_query = "INSERT INTO pendingVoters (name, email, password, dateOfBirth, citizenshipNumber, gender, dId,localAddress, citizenshipFrontPhoto, citizenshipBackPhoto, userPhoto)
                                VALUES ('$name', '$email', '$hashed_password', '$dateOfBirth', '$citizenshipNumber', '$gender','$dId','$localAddress',  '$newCitizenshipFrontPhoto', '$newCitizenshipBackPhoto', '$newUserPhoto')";
    
                if (mysqli_query($conn, $voter_query)) {
                    if(sendMail($email, $name, "Voter registration successful", "Wait for the ID and account verification")){
                        if (move_uploaded_file($_FILES['citizenshipFrontPhoto']['tmp_name'], $target_dir . $newCitizenshipFrontPhoto) &&
                            move_uploaded_file($_FILES['citizenshipBackPhoto']['tmp_name'], $target_dir . $newCitizenshipBackPhoto) &&
                            move_uploaded_file($_FILES['userPhoto']['tmp_name'], $target_dir . $newUserPhoto)) {
                            
                            // Commit transaction
                            mysqli_commit($conn);
                            $_SESSION['success_message'] = 'Application successfully received! Wait for verification.';
                            header('Location: ../register_and_login/voter_login_form.php');
                        } else {
                            // Rollback transaction
                            mysqli_rollback($conn);
                            $_SESSION['error_message'] = 'Image insertion failed.';
                            header('Location: ../register_and_login/voter_register_form.php');
                        }
                    }else{
                        // Rollback transaction
                        mysqli_rollback($conn);
                        $_SESSION['error_message'] = 'Email sending failed.';
                        header('Location: ../register_and_login/voter_register_form.php');}
                } else {
                    // Rollback transaction
                    mysqli_rollback($conn);
                    $_SESSION['error_message'] = "Error inserting voter data: " . mysqli_error($conn);
                    header('Location: ../register_and_login/voter_register_form.php');
                }
            } else {
                $_SESSION['error_message'] = 'District or region not found! Please re-fill the form carefully.';
                header('Location: ../register_and_login/voter_register_form.php');
            }
        }
}else{
    $_SESSION['error_message'] = 'All form fields are not filled, please check the fields';
    header('Location: ../register_and_login/voter_register_form.php');
}
}
// Close connection
mysqli_close($conn);
?>
