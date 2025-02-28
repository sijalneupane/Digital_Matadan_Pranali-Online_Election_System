<?php
session_start();
// Database connection
require '../register_and_login/dbconnection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get data from form
  $voterId = $_SESSION['voterId'];
  // $email = $_SESSION['email'];
  // $name = $_POST['name'];
  // $gender = $_POST['gender'];
  // $email = $_POST['email'];
  $email = $_POST['email'];
  // $district = $_POST['district'];
  // $election_region = $_POST['regionNo'];
  // $localAddress = $_POST['local_address'];
  // $citizenshipNumber = $_POST['citizenshipNumber'];
  $citizenshipNumber = $_SESSION['citizenshipNumber'];
  // $dateOfBirth = date('Y-m-d', strtotime($_POST['dateOfBirth']));

  // Check if email or citizenship number already exists
  $sql1 = "SELECT email FROM voters WHERE (email = '$email' OR citizenshipNumber = '$citizenshipNumber') and id!='$voterId' ";
  $result1 = mysqli_query($conn, $sql1);

  $sql2 = "SELECT email FROM pendingVoters WHERE (email = '$email' OR citizenshipNumber = '$citizenshipNumber') and id!='$voterId'";
  $result2 = mysqli_query($conn, $sql2);

  if (mysqli_num_rows($result1) > 0) {
    $row1 = mysqli_fetch_assoc($result1);
    // if ($email == $row1['email'] && $citizenshipNumber == $row1['citizenshipNumber']) {
    //   $_SESSION['error_message'] = 'Email and Citizenship already used';
    //   header('Location:  ../register_and_login/user_profile.php');
    // } else
    if ($email == $row1['email']) {
      $_SESSION['error_message'] = 'This email is already registered.';
      header('Location:  ../register_and_login/user_profile.php');
      // } else if ($citizenshipNumber == $row1['citizenshipNumber']) {
      //   $_SESSION['error_message'] = 'This citizenship number is already registered.';
      //   header('Location:  ../register_and_login/user_profile.php');
    }
  } else if (mysqli_num_rows($result2) > 0) {
    $row2 = mysqli_fetch_assoc($result2);
    // if ($email == $row2['email'] && $citizenshipNumber == $row2['citizenshipNumber']) {
    //   $_SESSION['error_message'] = 'Email and Citizenship already used and is in pending status';
    //   header('Location:  ../register_and_login/user_profile.php');
    // } else 
    if ($email == $row2['email']) {
      $_SESSION['error_message'] = 'Email already used and is in pending status';
      header('Location:  ../register_and_login/user_profile.php');
      // } elseif ($citizenshipNumber == $row2['citizenshipNumber']) {
      //   $_SESSION['error_message'] = 'Citizenship already used and is in pending status';
      //   header('Location:  ../register_and_login/user_profile.php');
    }
  } else {
    // $d_query = "SELECT dId FROM district WHERE district = '$district' AND regionNo = '$election_region'";
    // $d_result = mysqli_query($conn, $d_query);

    // if (mysqli_num_rows($d_result) > 0) {
    //   $row = mysqli_fetch_assoc($d_result);
    //   $dId = $row['dId'];
      // Step 4: Update voter information in the voters table
      $update_query = "UPDATE voters 
         SET email='$email'
         WHERE id = '$voterId'";

      if (mysqli_query($conn, $update_query)) {
        $_SESSION["email"] = $email;
        $_SESSION['success_message'] = "Profile updated successfully!";
          header('Location: ../register_and_login/user_profile.php');         // Redirect to the dashboard or homepage after successful login
        // $joinSql = "SELECT * 
        // FROM voters V
        // INNER JOIN district D ON v.dId = D.dId
        // WHERE V.id='$voterId'";

        // $result = mysqli_query($conn, $joinSql);
        // if (mysqli_num_rows($result) > 0) {
        //   $row1 = mysqli_fetch_assoc($result);
        //   // Set session variables for successful login
        //   $_SESSION['dId'] = $row1['dId'];
        //   $_SESSION['email']=$row1["email"];
        //   $_SESSION['election_region'] = $row1['regionNo'];
        //   $_SESSION['voterId'] = $row1['id'];
        //   $_SESSION['name'] = $row1['name'];
        //   $_SESSION['district'] = $row1['district'];
        //   $_SESSION['localAddress'] = $row1['localAddress'];
        //   $_SESSION['citizenshipNumber'] = $row1['citizenshipNumber'];
        //   $_SESSION['birthDate'] = $row1['dateOfBirth'];
        //   $_SESSION['gender'] = $row1['gender'];

        //   $_SESSION['success_message'] = "Profile updated successfully!";
        //   header('Location: ../register_and_login/user_profile.php');         // Redirect to the dashboard or homepage after successful update
        // } else {
        //   $_SESSION['error_message'] = 'Unexpected error occured, please try again';
        //   header('Location: ../register_and_login/user_profile.php');
        // }

      } else {
        $_SESSION['error_message'] = "Error updating profile: " . mysqli_error($conn);
        header('Location: ../register_and_login/user_profile.php');
      }
    // } else {
    //   $_SESSION['error_message'] = 'District or region not found! Please re-fill the form carefully.';
    //   header('Location:  ../register_and_login/user_profile.php');
    // }
  }
}else{
  $_SESSION['error_message'] = 'Please fill the form first';
  header('Location:  ../register_and_login/user_profile.php');
}
