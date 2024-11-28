<?php
session_start();
require '../email_send.php';
// Database connection
$conn = mysqli_connect("localhost", "root", "", "online_election");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if ID and message are provided
if (isset($_GET['id']) && isset($_GET['message']) && isset($_GET['email'])&& isset($_GET['name'])) {
    $id = intval($_GET['id']);
    $message = mysqli_real_escape_string($conn, $_GET['message']);
    $email = mysqli_real_escape_string($conn, $_GET['email']);
    $name = mysqli_real_escape_string($conn, $_GET['name']);

    // Update the status to 'declined' and save the decline message
    $query = "DELETE from pendingstatus WHERE id = $id";
    if (mysqli_query($conn, $query)) {
      sendMail($email, $name, "Accound verification failed", "Your account has failed to ber verified by us. <br>The reason is" . $message);

      $_SESSION['errorMsg'] = "Declined the application and rejection email sent successfully";
      header('Location: verify_voters.php');
    } else {
        $_SESSION['errorMsg'] = mysqli_error($conn);
        header('Location: verify_voters.php');
    }
} else {

  $_SESSION['errorMsg'] = "Invalid request";
  header('Location: verify_voters.php');
}

mysqli_close($conn);
?>