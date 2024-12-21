<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Hardcoded credentials
  $admin_username = "admin@DMP";
  $admin_password = "passworD@DMP123";

  // Retrieve POST data
  $username = $_POST['username'];
  $password = $_POST['password'];

  if ($username === $admin_username && $password === $admin_password) {
    $_SESSION['fromLogIn'] = true;
    $_SESSION['loggedin'] = true;
    header('Location: ../admin/admin_home.php');
  } else {
    $_SESSION['errorMsg'] = "Incorrect password or username";
    header('Location: ../admin/admin_login.php');
  }
}
