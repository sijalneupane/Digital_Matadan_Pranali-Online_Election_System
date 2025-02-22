<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../admin/admin_login.php");
    exit();
}
if (isset($_GET['logout_key'])) {

// Proceed with logout
session_unset();
session_destroy();
unset($_SESSION['loggedin']);
    header("Location: ../admin/admin_login.php");
    exit();
} else {
    try {
    $_SESSION['errorMsg']="Failed to logout. Try again through logout button in navigation bar";
    header('Location:../admin/admin_home.php');
    exit();
    }catch (Exception $e) {
        $_SESSION['errorMsg']=$e->getMessage();
        header('Location:../admin/admin_home.php');
    }
}
?>