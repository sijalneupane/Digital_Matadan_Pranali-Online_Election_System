<?php
session_start();
require '../email_send.php';
// Database connection
require '../dbconnection.php';

// Check if ID and message are provided
if (isset($_GET['id']) && isset($_GET['message']) && isset($_GET['email']) && isset($_GET['name'])) {
  $id = intval($_GET['id']);
  $message = mysqli_real_escape_string($conn, $_GET['message']);
  $email = mysqli_real_escape_string($conn, $_GET['email']);
  $name = mysqli_real_escape_string($conn, $_GET['name']);

  $sql1 = "SELECT citizenshipFrontPhoto, citizenshipBackPhoto, userPhoto FROM pendingstatus WHERE id = '$id'";
  $result1 = mysqli_query($conn, $sql1);

  if ($result1 && mysqli_num_rows($result1) > 0) {
    $row1 = mysqli_fetch_assoc($result1);

    // Step 2: Adjust paths for deletion (relative to the script location)
    $imagePaths = [
      "../uploads/" . basename($row1['citizenshipFrontPhoto']),
      "../uploads/" . basename($row1['citizenshipBackPhoto']),
      "../uploads/" . basename($row1['userPhoto']),
    ];

    // Delete each file if it exists
    foreach ($imagePaths as $path) {
      if (!empty($path) && file_exists($path)) {
        unlink($path); // Deletes the file
      }
    }
    // Update the status to 'declined' and save the decline message
    $query = "DELETE from pendingstatus WHERE id = '$id'";
    if (mysqli_query($conn, $query)) {
      sendMail($email, $name, "Accound verification failed", "Your account has failed to ber verified by us. <br>The reason is" . $message);

      $_SESSION['errorMsg'] = "Declined the application and rejection email sent successfully";
      header('Location: verify_voters.php');
    } else {
      $_SESSION['errorMsg'] = mysqli_error($conn);
      header('Location: verify_voters.php');
    }
  } else {
    $_SESSION['errorMsg'] = "No images are founds";
    header('Location: verify_voters.php');
  }
} else {

  $_SESSION['errorMsg'] = "Invalid request";
  header('Location: verify_voters.php');
}
mysqli_close($conn);
