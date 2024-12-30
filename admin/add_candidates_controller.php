<?php
session_start();
require_once '../register_and_login/dbconnection.php';

function getPartyId($partyName, $conn) {
  $query = "SELECT partyId FROM parties WHERE partyName = '$partyName'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  return $row['partyId'];
}

function getDistrictId($district, $regionNo, $conn) {
  $query = "SELECT dId FROM district WHERE district = '$district' AND regionNo = $regionNo";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  return $row['dId'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $candidateId = isset($_POST['candidateId']) ? mysqli_real_escape_string($conn, trim($_POST['candidateId'])) : '';
  $name = isset($_POST['name']) ? mysqli_real_escape_string($conn, trim($_POST['name'])) : '';
  $dob = isset($_POST['dob']) ? mysqli_real_escape_string($conn, trim($_POST['dob'])) : '';
  $gender = isset($_POST['gender']) ? mysqli_real_escape_string($conn, trim($_POST['gender'])) : '';
  $citizenship_number = isset($_POST['citizenship_number']) ? mysqli_real_escape_string($conn, trim($_POST['citizenship_number'])) : '';
  $education_level = isset($_POST['education_level']) ? mysqli_real_escape_string($conn, trim($_POST['education_level'])) : '';
  $manifesto = isset($_POST['manifesto']) ? mysqli_real_escape_string($conn, trim($_POST['manifesto'])) : '';
  $partyName = isset($_POST['partyName']) ? mysqli_real_escape_string($conn, trim($_POST['partyName'])) : '';
  $district = isset($_POST['district']) ? mysqli_real_escape_string($conn, trim($_POST['district'])) : '';
  $regionNo = isset($_POST['regionNo']) ? mysqli_real_escape_string($conn, trim($_POST['regionNo'])) : 0;
  $candidate_photo = isset($_FILES['candidate_photo']) ? $_FILES['candidate_photo'] : '';

  $partyId =($partyName=="INDEPENDENT") ? 1 : getPartyId($partyName, $conn);
  $dId = getDistrictId($district, $regionNo, $conn);
  
  $extension = pathinfo($candidate_photo['name'], PATHINFO_EXTENSION);
  $new_filename = $partyId . '_' . $dId . '_' . $name . '.' . $extension;
  $upload_path = '../uploads/'.$new_filename;

if($partyId==1){
  if (empty($candidateId)) {
    // Check if the same record already exists
    $check_query = "SELECT * FROM candidates WHERE citizenship_number=$citizenship_number";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
      $_SESSION['errorMsg'] = "Acandidate with the same citizenship number already exists.";
      header('Location: ../admin/add_candidates.php');
      exit();
    } else {
      // Insert new candidate
      $query = "INSERT INTO candidates (name, dob, gender, citizenship_number, education_level, manifesto, partyId, dId, candidate_photo) VALUES ('$name', '$dob', '$gender', '$citizenship_number', '$education_level', '$manifesto', $partyId, $dId, '$new_filename')";
      
      if (mysqli_query($conn, $query)) {
        if (move_uploaded_file($candidate_photo['tmp_name'], $upload_path)) {
          $_SESSION['errorMsg'] = "Candidate details and photo successfully inserted.";
        } else {
          $_SESSION['errorMsg'] = " Candidate details Candidate added, but failed to upload photo.";
        }
      } else {
        $_SESSION['errorMsg'] = "Error: " . mysqli_error($conn);
      }
      
      header('Location: ../admin/add_candidates.php');
      exit();
    }
  } else {
    // Get the current image path
    $image_query = "SELECT candidate_photo FROM candidates WHERE candidateId = $candidateId";
    $image_result = mysqli_query($conn, $image_query);
    $image_row = mysqli_fetch_assoc($image_result);
    $current_image_path = '../uploads/' . $image_row['candidate_photo'];

    // Delete the current image
    if (file_exists($current_image_path)) {
      unlink($current_image_path);
    }

    // Check if the same record already exists
    $check_query = "SELECT * FROM candidates WHERE citizenship_number=$citizenship_number AND  candidateId != $candidateId";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
      $_SESSION['errorMsg'] = "candidate with the same citizenship number already exists.";
      header('Location: ../admin/add_candidates.php');
      exit();
    } else {
      // Update existing candidate
      $query = "UPDATE candidates SET name = '$name', dob = '$dob', gender = '$gender', citizenship_number = '$citizenship_number', education_level = '$education_level', manifesto = '$manifesto', partyId = $partyId, dId = $dId, candidate_photo = '$new_filename' WHERE candidateId = $candidateId";
      
      if (mysqli_query($conn, $query)) {
        if (move_uploaded_file($candidate_photo['tmp_name'], $upload_path)) {
          $_SESSION['errorMsg'] = "Candidate details and photo successfully updated.";
        } else {
          $_SESSION['errorMsg'] = "Candidate details updated, but failed to upload new photo.";
        }
      } else {
        $_SESSION['errorMsg'] = "Error: " . mysqli_error($conn);
      }
      
      header('Location: ../admin/add_candidates.php');
      exit();
    }
  }
  // mysqli_close($conn);
}else{
  if (empty($candidateId)) {
    // Check if the same record already exists
    $check_query = "SELECT * FROM candidates WHERE (citizenship_number=$citizenship_number) OR (partyId=$partyId AND dId=$dId)";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
      $_SESSION['errorMsg'] = "A candidate with the same citizenship number or the same party in that district constituency already exists.";
      header('Location: ../admin/add_candidates.php');
      exit();
    } else {
      // Insert new candidate
      $query = "INSERT INTO candidates (name, dob, gender, citizenship_number, education_level, manifesto, partyId, dId, candidate_photo) VALUES ('$name', '$dob', '$gender', '$citizenship_number', '$education_level', '$manifesto', $partyId, $dId, '$new_filename')";
      
      if (mysqli_query($conn, $query)) {
        if (move_uploaded_file($candidate_photo['tmp_name'], $upload_path)) {
          $_SESSION['errorMsg'] = "Candidate details and photo successfully inserted.";
        } else {
          $_SESSION['errorMsg'] = "Candidate setails added, but failed to upload photo.";
        }
      } else {
        $_SESSION['errorMsg'] = "Error: " . mysqli_error($conn);
      }
      
      header('Location: ../admin/add_candidates.php');
      exit();
    }
  } else {
    // Get the current image path
    $image_query = "SELECT candidate_photo FROM candidates WHERE candidateId = $candidateId";
    $image_result = mysqli_query($conn, $image_query);
    $image_row = mysqli_fetch_assoc($image_result);
    $current_image_path = '../uploads/' . $image_row['candidate_photo'];

    // Delete the current image
    if (file_exists($current_image_path)) {
      unlink($current_image_path);
    }

    // Check if the same record already exists
    $check_query = "SELECT * FROM candidates WHERE ((citizenship_number=$citizenship_number) OR (partyId=$partyId AND dId=$dId))and candidateId != $candidateId";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
      $_SESSION['errorMsg'] = "A candidate with the same name, date of birth, party, and district already exists.";
      header('Location: ../admin/add_candidates.php');
      exit();
    } else {
      // Update existing candidate
      $query = "UPDATE candidates SET name = '$name', dob = '$dob', gender = '$gender', citizenship_number = '$citizenship_number', education_level = '$education_level', manifesto = '$manifesto', partyId = $partyId, dId = $dId, candidate_photo = '$new_filename' WHERE candidateId = $candidateId";
      
      if (mysqli_query($conn, $query)) {
        if (move_uploaded_file($candidate_photo['tmp_name'], $upload_path)) {
          $_SESSION['errorMsg'] = "Candidate and photo successfully updated.";
        } else {
          $_SESSION['errorMsg'] = "Candidate updated, but failed to upload new photo.";
        }
      } else {
        $_SESSION['errorMsg'] = "Error: " . mysqli_error($conn);
      }
      
      header('Location: ../admin/add_candidates.php');
      exit();
    }
  }
}
}
?>