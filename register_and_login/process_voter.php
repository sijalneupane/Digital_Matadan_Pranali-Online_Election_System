<?php
session_start(); // Start the session to store session variables

require '../dbconnection.php';
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $voterId = $_POST['voterId'];
  $email=$_SESSION['email'];
  $password=$_SESSION['password'];
  // unset($_SESSION['password']);
  // Query to fetch user details based on email 
  $sql = "SELECT * FROM voters WHERE id='$voterId' and email='$email' LIMIT 1";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    // Fetch the user's data
    $row2 = mysqli_fetch_assoc($result);
    // Check if the password matches (assuming passwords are not hashed)
    // No need for password_verify here since we aren't hashing
    $joinSql = "SELECT * 
                            FROM voters V 
                            INNER JOIN localaddress la ON V.addressId = la.lid
                            INNER JOIN district D ON D.dId = la.dId
                            WHERE V.id='$voterId'";
    $result = mysqli_query($conn, $joinSql);
    if (mysqli_num_rows($result) > 0) {
      $row1 = mysqli_fetch_assoc($result);
      // Set session variables for successful login
      $_SESSION['election_region'] = $row1['regionNo'];
      $_SESSION['voterId'] = $row1['id'];
      $_SESSION['name'] = $row1['name'];
      $_SESSION['district'] = $row1['district'];
      $_SESSION['local_address'] = $row1['local_address'];
      $_SESSION['citizenshipNumber'] = $row1['citizenshipNumber'];
      $_SESSION['birthDate'] = $row1['dateOfBirth'];
      $_SESSION['gender'] = $row1['gender'];
      $_SESSION['userPhoto'] = $row1['userPhoto'];
      $_SESSION['citizenshipFrontPhoto'] = $row1['citizenshipFrontPhoto'];
      $_SESSION['citizenshipBackPhoto'] = $row1['citizenshipBackPhoto'];

      header('Location: ../home.php');         // Redirect to the dashboard or homepage after successful login
    } else {
      $_SESSION['error_message'] = 'Unexpected error occured, please try again';
      header('Location: voter_login_form.php');
    }
  }else {
    $_SESSION['idErrorMsg']= 'Voter id didnot match';
    header('Location: login_verification.php');
    
}
}