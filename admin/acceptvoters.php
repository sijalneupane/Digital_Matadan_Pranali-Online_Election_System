<?php
session_start();
require "../email_send.php";
// Database connection
$conn = mysqli_connect("localhost", "root", "", "online_election");

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Check if ID and message are provided
if (isset($_GET['id'])) {
  $id = intval($_GET['id']);

  $sql = "SELECT * FROM pendingstatus WHERE id = '$id' LIMIT 1";
  $result = mysqli_query($conn, $sql);

  // Check if user exists
  if (mysqli_num_rows($result) > 0) {
    // Fetch the user's data
    $row = mysqli_fetch_assoc($result);
    $name = $row['name'];
    $email = $row['email'];
    $password = $row['password']; // Consider hashing in production
    $dateOfBirth = $row['dateOfBirth'];
    $citizenshipNumber = $row['citizenshipNumber'];
    $gender = $row['gender'];
    $addressId = $row['addressId'];
    $citizenshipFrontPhoto = $row['citizenshipFrontPhoto'];
    $citizenshipBackPhoto = $row['citizenshipBackPhoto'];
    $userPhoto = $row['userPhoto'];

    $deleteSql = "DELETE FROM pendingstatus WHERE id='$id'";
    if (mysqli_query($conn, $deleteSql)) {
      $sql1 = "INSERT INTO voters (name,  email, password,dateOfBirth,  citizenshipNumber,gender,addressid, citizenshipFrontPhoto, citizenshipBackPhoto, userPhoto)
        VALUES ('$name',  '$email', '$password', '$dateOfBirth', '$citizenshipNumber','$gender','$addressId', '$citizenshipFrontPhoto', '$citizenshipBackPhoto', '$userPhoto')";
      if (mysqli_query($conn, $sql1)) {
        $voterId = mysqli_insert_id($conn);
        sendMail($email, $name, "Voter registration successfull", "You account is verified and your voter id is " . $voterId);
        // echo "successfully send email";
        $_SESSION['errorMsg'] = "Successfully verified ". $name;
        header('Location: verify_voters.php');
      } else {
        // echo mysqli_error($conn);
        $_SESSION['errorMsg'] = "Error occured while making " . $name . "verified and inserting into voters table";
        header('Location: verify_voters.php');
      }
    } else {
      // var_dump(mysqli_query($conn, $deleteSql));
      $_SESSION['errorMsg'] = "Error occured while removing the verified data from pending status";
      header('Location: verify_voters.php');
    }
  } else {
    $_SESSION['errorMsg'] = "No records found on pending status";
    header('Location: verify_voters.php');
  }
} else {
  
  $_SESSION['errorMsg'] = "Id and message not sent from verify_voters file";
  header('Location: verify_voters.php');
}
mysqli_close($conn);
