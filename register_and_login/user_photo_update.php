<?php
require_once '../register_and_login/dbconnection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the voter ID and image and type through post and url
  $oldImageName = $_GET['oldImageName'];
  $id = $_POST['voterId'];
  $image = $_FILES['newImage']['name'];
  $columnType = $_GET['type'];
  $imageType = $_FILES['newImage']['type'];

  // Extract the extension of the new image
  $imageExtension = pathinfo($image, PATHINFO_EXTENSION);

  // Check if the image extension is valid
  $validExtensions = ['jpeg', 'jpg', 'png'];
  if (!in_array(strtolower($imageExtension), $validExtensions)) {
    $_SESSION['error_message'] = 'Not a valid image type';
    header('Location: ../register_and_login/user_profile.php');
    exit();
  }

  // Set the new image name as the old image name but with the new extension
  $newImageName = pathinfo($oldImageName, PATHINFO_FILENAME) . '.' . $imageExtension;

  // Remove the old image from ../uploads folder
  $oldImagePath = '../uploads/' . $oldImageName;
  if (file_exists($oldImagePath)) {
    $oldFileDeleted = unlink($oldImagePath);
  }

  if ($oldFileDeleted) {
    // Move the new image to the ../uploads folder
    $newImagePath = '../uploads/' . $newImageName;
    $imageMoved = move_uploaded_file($_FILES['newImage']['tmp_name'], $newImagePath);
    if ($imageMoved) {
      // Update the image name in the database
      $sql = "UPDATE voters SET $columnType = '$newImageName' WHERE id = '$id'";
      $result = $conn->query($sql);
      if ($result) {
        // $_SESSION['success_message'] = 'Image updated successfully';
        $_SESSION['error_message'] = 'Image updated successfully';
        $_SESSION[$columnType] = $newImageName;
        header('Location: ../register_and_login/user_profile.php');
        exit();
      } else {
        // Rollback: Move the new image back to the old image path
        move_uploaded_file($_FILES['newImage']['tmp_name'], $oldImagePath);
        $_SESSION['error_message'] = 'Failed to update image in database';
        header('Location: ../register_and_login/user_profile.php');
        exit();
      }
    } else {
      // Rollback: Restore the old image
      move_uploaded_file($_FILES['newImage']['tmp_name'], $oldImagePath);
      $_SESSION['error_message'] = 'Failed to move new image';
      header('Location: ../register_and_login/user_profile.php');
      exit();
    }
  } else {
    $_SESSION['error_message'] = 'Failed to delete old image';
    header('Location: ../register_and_login/user_profile.php');
    exit();
  }
} else {
  $_SESSION['error_message'] = 'Wrong method';
  header('Location: ../register_and_login/user_profile.php');
  exit();
}
?>