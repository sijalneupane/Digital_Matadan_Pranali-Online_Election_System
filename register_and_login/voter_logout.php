<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../register_and_login/voter_login_form.php");
    exit();
}
if (isset($_GET['logout_key'])) {

// Proceed with logout
session_unset();
session_destroy();
    header("Location: ../register_and_login/voter_login_form.php");
    exit();
} else {
    try {
        $_SESSION['error_message']="Failed to logout. Try again through logout button in profile page";
    header('Location:../register_and_login/user_profile.php');
    exit();
    }catch (Exception $e) {
        echo ''. $e->getMessage() .'';
    }
}
?>