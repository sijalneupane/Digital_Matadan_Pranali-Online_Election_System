<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  require_once '../register_and_login/dbconnection.php';

  // Get data from POST method
  $partyId = isset($_POST['partyId']) ? $_POST['partyId'] : '';
  $partyName = isset($_POST['partyName']) ? $_POST['partyName'] : '';
  $partyLeader = isset($_POST['partyLeader']) ? $_POST['partyLeader'] : '';

  // Get image information
  $image = isset($_FILES['partyLogo']) ? $_FILES['partyLogo'] : '';
  $targetDir = '../uploads/';
  $fileExtension = pathinfo($image['name'], PATHINFO_EXTENSION);

  // Set new name for the image
  $newImageName = $partyName . 'logo.' . $fileExtension;
  $targetFile = $targetDir . $newImageName;

  if ($partyName == '' || $partyLeader == '' || $image == '') {
    $_SESSION['errorMsg'] = "All fields are required.";
    header('Location: ../admin/manage_parties.php');
  } else {
    //  else {
    if ($partyId == '') {
      // Check if the same record already exists
      $check_query = "SELECT * FROM parties WHERE partyName='$partyName' or partyLeader='$partyLeader'";
      $check_result = mysqli_query($conn, $check_query);

      if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['errorMsg'] = "A party with the same name or leader already exists.";
        header('Location: ../admin/manage_parties.php');
      } else {
        // Insert data and new image name into the database
        $sql = "INSERT INTO parties (partyName, partyLeader, partyLogo) VALUES ('$partyName', '$partyLeader', '$newImageName')";
      }
    } else {
      // Get the current image path
      $image_query = "SELECT partyLogo FROM parties WHERE partyId = $partyId";
      $image_result = mysqli_query($conn, $image_query);
      $image_row = mysqli_fetch_assoc($image_result);
      $current_image_path = '../uploads/' . $image_row['partyLogo'];

      // Delete the current image
      if (file_exists($current_image_path)) {
        unlink($current_image_path);
      }
      // Check if the same record already exists
      $check_query = "SELECT * FROM parties WHERE (partyName='$partyName' or partyLeader='$partyLeader') AND partyId != $partyId";
      $check_result = mysqli_query($conn, $check_query);

      if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['errorMsg'] = "A party with the same name or leader already exists.";
        header('Location: ../admin/manage_parties.php');
      } else {
        // Update existing record
        $sql = "UPDATE parties SET partyName='$partyName', partyLeader='$partyLeader', partyLogo='$newImageName' WHERE partyId='$partyId'";
      }
    }
    if (mysqli_query($conn, $sql)) {
      // If data inserted/updated successfully, move the image to the target directory
      if (move_uploaded_file($image['tmp_name'], $targetFile)) {
        $_SESSION['errorMsg'] = "Party " . ($partyId == '' ? "added" : "updated") . " successfully with logo.";
        header('Location: ../admin/manage_parties.php');
        unset($_POST); // Clear the form data
      } else {
        $_SESSION['errorMsg'] = "Error moving the uploaded file.";
        header('Location: ../admin/manage_parties.php');
      }
    } else {
      $_SESSION['errorMsg'] = "Error inserting/updating data: " . mysqli_error($conn);
      header('Location: ../admin/manage_parties.php');
    }
    // Close the connection
    mysqli_close($conn);
  }
} else {
  $_SESSION['errorMsg'] = "Invalid request method.";
  header('Location: ../admin/manage_parties.php');
}
?>