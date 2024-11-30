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
      header('Location: admin_home.php');
      $_SESSION['loggedin']=true;
    } else {
      $_SESSION['errorMessage'] = "Incorrect passwor or username";
      header('Location: admin_login.php');
    }
  }
  ?>