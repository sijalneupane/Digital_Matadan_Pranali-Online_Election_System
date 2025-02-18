<?php 
session_start();
require_once '../register_and_login/dbconnection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $voterId=$_SESSION['voterId'];
  $message=$_POST['message'];
  $sql="INSERT INTO votersMessages (voterId,messages) VALUES ('$voterId','$message')";
  if(mysqli_query($conn,$sql)){
    $_SESSION['success_message'] = 'Message sent successfully';
    header('Location: ../home/contact_us.php');
  }else{
    $_SESSION['error_message'] = 'Failed to send message';
    header('Location: ../home/contact_us.php');
  }
}else{
$_SESSION['error_message'] = 'INVALID REQUEST';
header('Location: ../home/contact_us.php');
}
?>