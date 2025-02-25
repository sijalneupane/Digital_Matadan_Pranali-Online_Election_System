<?php
session_start(); // Start the session to store session variables
require '../register_and_login/dbconnection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $citizenshipNumber = $_POST['citizenshipNumber'];
    $password = $_POST['password'];

    // Check if the email exists in pendingVoters
    $sql = "SELECT * FROM pendingVoters WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        if ($row['citizenshipNumber'] !== $citizenshipNumber) {
            $_SESSION['error_message'] = 'Citizenship number does not match for the pending account.';
        } elseif (!password_verify($password, $row['password'])) {
            $_SESSION['error_message'] = 'Incorrect password for pending account.';
        } else {
            $_SESSION['error_message'] = 'Your account is pending. Wait until verification! We will send an email once the verification is done.';
        }
        header('Location: ../register_and_login/voter_login_form.php');
        exit();
    }

    // Check if the email exists in voters
    $sql2 = "SELECT * FROM voters WHERE email = '$email' LIMIT 1";
    $result2 = mysqli_query($conn, $sql2);

    if (mysqli_num_rows($result2) > 0) {
        $row2 = mysqli_fetch_assoc($result2);

        if ($row2['citizenshipNumber'] !== $citizenshipNumber) {
            $_SESSION['error_message'] = 'Citizenship number does not match for the verified account.';
        } elseif (!password_verify($password, $row2['password'])) {
            $_SESSION['error_message'] = 'Incorrect password for verified user.';
        } else {
            $_SESSION['email'] = $email;
            header("Location: ../register_and_login/login_verification.php");
            exit();
        }
        header('Location: ../register_and_login/voter_login_form.php');
        exit();
    }

    // If email is not found in either table
    $_SESSION['error_message'] = 'No account found with this email.';
    header('Location: ../register_and_login/voter_login_form.php');
    exit();
}

// Close the connection
mysqli_close($conn);
