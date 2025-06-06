<?php
session_start(); // Start the session to store session variables

require '../register_and_login/dbconnection.php';
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $voterId = $_POST['voterId'];
  $email = $_SESSION['email'];
  // unset($_SESSION['password']);
  // Query to fetch user details based on email 
  $sql = "SELECT * FROM voters WHERE id='$voterId' and email='$email' LIMIT 1";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    // Fetch the user's data
    $row2 = mysqli_fetch_assoc($result);
    $joinSql = "SELECT * 
                FROM voters V 
                INNER JOIN district D ON v.dId = D.dId
                WHERE V.id='$voterId'";
    $result = mysqli_query($conn, $joinSql);
    if (mysqli_num_rows($result) > 0) {
      $row1 = mysqli_fetch_assoc($result);
      // Set session variables for successful login
      $_SESSION['dId'] = $row1['dId'];
      $_SESSION['election_region'] = $row1['regionNo'];
      $_SESSION['voterId'] = $row1['id'];
      $_SESSION['name'] = $row1['name'];
      $_SESSION['district'] = $row1['district'];
      $_SESSION['localAddress'] = $row1['localAddress'];
      $_SESSION['citizenshipNumber'] = $row1['citizenshipNumber'];
      $_SESSION['birthDate'] = $row1['dateOfBirth'];
      $_SESSION['gender'] = $row1['gender'];
      $_SESSION['userPhoto'] = $row1['userPhoto'];
      $_SESSION['citizenshipFrontPhoto'] = $row1['citizenshipFrontPhoto'];
      $_SESSION['citizenshipBackPhoto'] = $row1['citizenshipBackPhoto'];
      $_SESSION['votingStatus'] = $row1['votingStatus'];
      header('Location: ../home/home.php');         // Redirect to the dashboard or homepage after successful login
    } else {
      $_SESSION['error_message'] = 'Unexpected error occured, please try again';
      header('Location: ../register_and_login/voter_login_form.php');
    }
  } else {
    $_SESSION['idErrorMsg'] = 'Voter id didnot match';
    header('Location: ../register_and_login/login_verification.php');

  }
}