<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_otp = $_POST['otp'];

    // Check if OTP cookie exists
    if (isset($_COOKIE['otp'])) {
        $stored_otp = $_COOKIE['otp'];

        // Validate OTP
        if ($entered_otp == $stored_otp) {
            // OTP matches
            setcookie("otp", "", time() - 3600, "/"); // Clear OTP cookie
            header("Location: ../forgotpassword/reset_password.php");
            exit();
        } else {
            echo "<script>alert('Invalid OTP. Please try again.')</script>";
            header('Location: ../forgotpassword/verify_otp.php');
        }
    } else {
        echo "OTP has expired. Please request a new one.";
    }
} else {
    header("Location: ../forgotpassword/forgot_password.php");
    exit();
}
