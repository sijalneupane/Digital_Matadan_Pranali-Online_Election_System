<?php
require "../email_send.php";
session_start();
// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'online_election');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from form
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Consider hashing in production
    $district = $_POST['district'];
    $election_region = $_POST['regionNo'];
    $local_address = $_POST['local_address'];
    $citizenshipNumber = $_POST['citizenshipNumber'];   
    $dateOfBirth = date('Y-m-d', strtotime($_POST['dateOfBirth']));

    $emailParts = explode('@', $email);
    $username = $emailParts[0];
    // Image uploads handling
    $citizenshipFrontPhoto = $_FILES['citizenshipFrontPhoto']['name'];
    $citizenshipBackPhoto = $_FILES['citizenshipBackPhoto']['name'];
    $userPhoto = $_FILES['userPhoto']['name'];

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


    // Check if email or citizenship number already exists
    $sql1 = "SELECT email, citizenshipNumber FROM voters WHERE email = '$email' OR citizenshipNumber = '$citizenshipNumber'";
    $result1 = mysqli_query($conn, $sql1);

    $sql2 = "SELECT email, citizenshipNumber FROM pendingstatus WHERE email = '$email' OR citizenshipNumber = '$citizenshipNumber'";
    $result2 = mysqli_query($conn, $sql2);

    if (mysqli_num_rows($result1) > 0) {
        $row1 = mysqli_fetch_assoc($result1);
        if ($email == $row1['email'] && $citizenshipNumber==$row1['citizenshipNumber']) {
            // echo "This email is already registered.";
            $_SESSION['error_message'] = 'Email and Citizenship already used';
            header('Location: voter_register_form.php');
        }else if ($email == $row1['email']) {
            // echo "This email is already registered.";
            $_SESSION['error_message'] = 'This email is already registered.';
            header('Location: voter_register_form.php');
        } elseif ($citizenshipNumber == $row1['citizenshipNumber']) {
            // echo "This citizenship number is already registered.";
            $_SESSION['error_message'] = 'This citizenship number is already registered.';
            header('Location: voter_register_form.php');
        }
    } else if(mysqli_num_rows($result2) > 0){
        $row2 = mysqli_fetch_assoc($result2);
        if ($email == $row2['email'] && $citizenshipNumber==$row2['citizenshipNumber']) {
            // echo "This email is already registered.";
            $_SESSION['error_message'] = 'Email and Citizenship already used and is in pending status';
            header('Location: voter_register_form.php');
        }else if ($email == $row2['email']) {
            // echo "This email is already registered.";
            $_SESSION['error_message'] = 'Email already used and is in pending status';
            header('Location: voter_register_form.php');
        } elseif ($citizenshipNumber == $row2['citizenshipNumber']) {
            // echo "This citizenship number is already registered.";
            $_SESSION['error_message'] = 'Citizenship already used and is in pending status';
            header('Location: voter_register_form.php');
        }
    }else {
        // Step 1: Retrieve the dId from the district table
        $d_query = "SELECT dId FROM district WHERE district = '$district' AND regionNo = '$election_region'";
        $d_result = mysqli_query($conn, $d_query);

        if (mysqli_num_rows($d_result) > 0) {
            $row = mysqli_fetch_assoc($d_result);
            $dId = $row['dId'];
            // Step 2: Check if the local_address already exists
            $check_query = "SELECT lid FROM localaddress WHERE  dId = '$dId' AND local_address = '$local_address'";
            $check_result = mysqli_query($conn, $check_query);

            // var_dump(mysqli_fetch_assoc($check_result));
            if (mysqli_num_rows($check_result) > 0) {
                $row = mysqli_fetch_assoc($check_result);

                $addressID = $row['lid'];
            } else {
                // Address doesn't exist, insert it
                $local_query = "INSERT INTO localaddress (dId, local_address) VALUES ('$dId', '$local_address')";
                if (mysqli_query($conn, $local_query)) {
                    $addressID = mysqli_insert_id($conn);
                } else {
                    $_SESSION['error_message'] = "Error inserting local address: " . mysqli_error($conn);
                    // $_SESSION['error_message'] = "Local address is not valid for the District and RegionNo combination ! ! !";
                    header('Location: voter_register_form.php');
                    exit();  // Stop execution if there's an error
                }
            }

            // Step 3: Insert voter information into voters table
            $voter_query = "INSERT INTO pendingstatus (name,  email, password,dateOfBirth,  citizenshipNumber,gender,addressid, citizenshipFrontPhoto, citizenshipBackPhoto, userPhoto)
                            VALUES ('$name',  '$email', '$password', '$dateOfBirth', '$citizenshipNumber','$gender','$addressID', '$newCitizenshipFrontPhoto', '$newCitizenshipBackPhoto', '$newUserPhoto')";

            if (mysqli_query($conn, $voter_query)) {
                move_uploaded_file($_FILES['citizenshipFrontPhoto']['tmp_name'], $target_dir . $newCitizenshipFrontPhoto);
                move_uploaded_file($_FILES['citizenshipBackPhoto']['tmp_name'], $target_dir . $newCitizenshipBackPhoto);
                move_uploaded_file($_FILES['userPhoto']['tmp_name'], $target_dir . $newUserPhoto);
                // echo "Voter registered successfully!";
                sendMail($email, $name, "Voter registration successfull", "Wait for the id and account verification");
                $_SESSION['error_message'] = 'Aplication successfully recieved !!! Wait for the veriification';
                header('Location: voter_login_form.php');
            } else {
                // echo "Error inserting voter data: " . mysqli_error($conn);
                $_SESSION['error_message'] = "Error inserting voter data: " . mysqli_error($conn);
                header('Location: voter_register_form.php');
            }
        } else {
            // echo "District or region not found!";
            $_SESSION['error_message'] = 'District or region not found! PLease re-fill the form carefully';
            header('Location: voter_register_form.php');
        }
    }
}

// Close connection
mysqli_close($conn);
?>
