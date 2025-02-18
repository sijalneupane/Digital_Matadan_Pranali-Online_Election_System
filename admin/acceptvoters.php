<?php
session_start();
require "../home/email_send.php";
// Database connection
require '../register_and_login/dbconnection.php';

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
    $localAddress = $row['localAddress'];
    $dId = $row['dId'];
    $citizenshipFrontPhoto = $row['citizenshipFrontPhoto'];
    $citizenshipBackPhoto = $row['citizenshipBackPhoto'];
    $userPhoto = $row['userPhoto'];

    $deleteSql = "DELETE FROM pendingstatus WHERE id='$id'";
    if (mysqli_query($conn, $deleteSql)) {
      $sql1 = "INSERT INTO voters (name,  email, password,dateOfBirth,  citizenshipNumber,gender,dId,localAddress, citizenshipFrontPhoto, citizenshipBackPhoto, userPhoto)
        VALUES ('$name',  '$email', '$password', '$dateOfBirth', '$citizenshipNumber','$gender','$dId','$localAddress', '$citizenshipFrontPhoto', '$citizenshipBackPhoto', '$userPhoto')";
      if (mysqli_query($conn, $sql1)) {
        $voterId = mysqli_insert_id($conn);
        sendMail($email, $name, "Voter registration successfull", "You account is verified and your voter id is " . $voterId);
        // echo "successfully send email";
        $_SESSION['errorMsg'] = "Successfully verified ". $name;
        header('Location: ../admin/manage_voters.php');
      } else {
        // echo mysqli_error($conn);
        $_SESSION['errorMsg'] = "Error occured while making " . $name . "verified and inserting into voters table";
        header('Location: ../admin/manage_voters.php');
      }
    } else {
      // var_dump(mysqli_query($conn, $deleteSql));
      $_SESSION['errorMsg'] = "Error occured while removing the verified data from pending status";
      header('Location: ../admin/manage_voters.php');
    }
  } else {
    $_SESSION['errorMsg'] = "No records found on pending status";
    header('Location: ../admin/manage_voters.php');
  }
} else {
  
  $_SESSION['errorMsg'] = "Id and message not sent from verify_voters file";
  header('Location: ../admin/manage_voters.php');
}
mysqli_close($conn);
?><?php
session_start();
require "../home/email_send.php";
// Database connection
require '../register_and_login/dbconnection.php';

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
        $localAddress = $row['localAddress'];
        $dId = $row['dId'];
        $citizenshipFrontPhoto = $row['citizenshipFrontPhoto'];
        $citizenshipBackPhoto = $row['citizenshipBackPhoto'];
        $userPhoto = $row['userPhoto'];

        // Start transaction
        mysqli_begin_transaction($conn);

        try {
            // Delete from pendingstatus
            $deleteSql = "DELETE FROM pendingstatus WHERE id='$id'";
            if (!mysqli_query($conn, $deleteSql)) {
                throw new Exception("Error occurred while removing the verified data from pending status");
            }

            // Insert into voters
            $sql1 = "INSERT INTO voters (name, email, password, dateOfBirth, citizenshipNumber, gender, dId, localAddress, citizenshipFrontPhoto, citizenshipBackPhoto, userPhoto)
                     VALUES ('$name', '$email', '$password', '$dateOfBirth', '$citizenshipNumber', '$gender', '$dId', '$localAddress', '$citizenshipFrontPhoto', '$citizenshipBackPhoto', '$userPhoto')";
            if (!mysqli_query($conn, $sql1)) {
                throw new Exception("Error occurred while making $name verified and inserting into voters table");
            }

            // Commit transaction
            mysqli_commit($conn);

            $voterId = mysqli_insert_id($conn);
           if(sendMail($email, $name, "Voter registration successful", "Your account is verified and your voter ID is " . $voterId)){
            $_SESSION['successMsg'] = "Successfully verified " . $name;
            header('Location: ../admin/manage_voters.php');
            exit();
           }else{
            throw new Exception("Error occurred while sending email to $name");
           }
        } catch (Exception $e) {
            // Rollback transaction
            mysqli_rollback($conn);
            $_SESSION['errorMsg'] = $e->getMessage();
            header('Location: ../admin/manage_voters.php');
            exit();
        }
    } else {
        $_SESSION['errorMsg'] = "No records found on pending status";
        header('Location: ../admin/manage_voters.php');
        exit();
    }
} else {
    $_SESSION['errorMsg'] = "ID and message not sent from verify_voters file";
    header('Location: ../admin/manage_voters.php');
    // exit();
}

mysqli_close($conn);
?>