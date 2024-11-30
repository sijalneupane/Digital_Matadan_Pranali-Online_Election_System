<?php
session_start(); // Start the session to store session variables

require '../dbconnection.php';
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $citizenshipNumber = $_POST['citizenshipNumber'];
    $password = $_POST['password'];

    // Query to fetch user details based on email 
    $sql = "SELECT * FROM pendingstatus WHERE email = '$email' and citizenshipNumber='$citizenshipNumber' LIMIT 1" ;
    $result = mysqli_query($conn, $sql);

    // Check if user exists
    if (mysqli_num_rows($result) > 0) {
        // Fetch the user's data
        $row = mysqli_fetch_assoc($result);

        // Check if the password matches (assuming passwords are not hashed)
        if ($password === $row['password']) {  // No need for password_verify here since we aren't hashing
            $_SESSION['error_message'] = 'Your account is on pending. Wait until verification ! ! !We will send email once the verification is done';
            header('Location: voter_login_form.php');
        } else {
            $_SESSION['error_message'] = 'Incorrect password for pending account';
            header('Location: voter_login_form.php');
        }
    } else {
        // No user found with that email and voter ID in pending
        $sql2 = "SELECT * FROM voters WHERE email = '$email' AND citizenshipNumber = '$citizenshipNumber' LIMIT 1";
        $result2 = mysqli_query($conn, $sql2);

        // Check if user exists
        if (mysqli_num_rows($result2) > 0) {
            //fetch password and check wiht user entered password
            $row2=mysqli_fetch_assoc($result2);
            if ($row2['password']===$password) {
                $_SESSION['email']=$email;
                $_SESSION['password']=$password;
                header("Location: login_verification.php");
            } else {
                $_SESSION['error_message'] = 'Incorrect password for verified user';
                header('Location: voter_login_form.php');
            }
        } else {
            // No user found with that email and voter ID
            $_SESSION['error_message'] = 'Incorrect details. No user found with entered detail';
            header('Location: voter_login_form.php');
        }
    }
}

// Close the connection
mysqli_close($conn);
