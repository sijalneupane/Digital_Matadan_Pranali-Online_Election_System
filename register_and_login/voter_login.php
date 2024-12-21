<?php
session_start(); // Start the session to store session variables
require '../register_and_login/dbconnection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $citizenshipNumber = $_POST['citizenshipNumber'];
    $password = $_POST['password'];

    // Query to fetch user details based on email and citizenship number from the pendingstatus table
    $sql = "SELECT * FROM pendingstatus WHERE email = '$email' AND citizenshipNumber = '$citizenshipNumber' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    // Check if user exists in pendingstatus
    if (mysqli_num_rows($result) > 0) {
        // Fetch the user's data
        $row = mysqli_fetch_assoc($result);

        // Verify the password using password_verify
        if (password_verify($password, $row['password'])) {
            $_SESSION['error_message'] = 'Your account is pending. Wait until verification! We will send an email once the verification is done.';
            header('Location: ../register_and_login/voter_login_form.php');
        } else {
            $_SESSION['error_message'] = 'Incorrect password for pending account';
            header('Location: ../register_and_login/voter_login_form.php');
        }
    } else {
        // No user found in pendingstatus, check the voters table
        $sql2 = "SELECT * FROM voters WHERE email = '$email' AND citizenshipNumber = '$citizenshipNumber' LIMIT 1";
        $result2 = mysqli_query($conn, $sql2);

        // Check if user exists in voters
        if (mysqli_num_rows($result2) > 0) {
            // Fetch the user's data
            $row2 = mysqli_fetch_assoc($result2);

            // Verify the password using password_verify
            if (password_verify($password, $row2['password'])) {
                $_SESSION['email'] = $email;
                header("Location: ../register_and_login/login_verification.php");
            } else {
                $_SESSION['error_message'] = 'Incorrect password for verified user';
                header('Location:../register_and_login/voter_login_form.php');
            }
        } else {
            // No user found with that email and voter ID in both tables
            $_SESSION['error_message'] = 'Incorrect details. No user found with entered details.';
            header('Location: ../register_and_login/voter_login_form.php');
        }
    }
}

// Close the connection
mysqli_close($conn);
