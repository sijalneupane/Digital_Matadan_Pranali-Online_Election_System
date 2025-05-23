<?php
session_start();
// Get the table name and id from the URL
$table = isset($_GET['table']) ? $_GET['table'] : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$photoPath=isset($_GET['photoPath']) ? $_GET['photoPath'] : '';

// Check if the table name is valid and id is greater than 0
if (($table === 'parties' || $table === 'candidates') && $id > 0) {
  // Database connection
 require_once '../register_and_login/dbconnection.php';

  // Prepare the SQL statement
  if ($table === 'parties') {
    $sql = "DELETE FROM parties WHERE partyId = $id";
  } elseif ($table === 'candidates') {
    $sql = "DELETE FROM candidates WHERE candidateId = $id";
  }
    // Get path to remove the photo from the upload directory if the deletion is successful
    if ($table === 'parties') {
      $photoPath = "../uploads/$photoPath";
    } elseif ($table === 'candidates') {
      $photoPath = "../uploads/$photoPath";
    }

  // Execute the query
  if (mysqli_query($conn, $sql)) {

    if (file_exists($photoPath)) {
      unlink($photoPath);
    }
    $_SESSION['successMsg'] = "Record deleted successfully";
  } else {
    $_SESSION['errorMsg'] = "Error: " . mysqli_error($conn);
  }

  // Close the connection
 mysqli_close($conn);
} else {
  $_SESSION['errorMsg'] = "Invalid request or invalida id and table name.";
}
// Redirect to the respective page after deletion
if ($table === 'parties') {
  header("Location: ../admin/manage_parties.php");
} elseif ($table === 'candidates') {
  header("Location: ../admin/add_candidates.php");
}
exit();
?>