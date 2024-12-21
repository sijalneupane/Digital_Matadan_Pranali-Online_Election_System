<?php 
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: admin_login.php');
    exit;
}
$_SESSION['pageName'] = "Add Candidates";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Online Voting System</title>
    <link rel="stylesheet" href="admin_home.css">
</head>
<body>
    <?php require 'admin_navbar.php'; ?>
</body>
</html>