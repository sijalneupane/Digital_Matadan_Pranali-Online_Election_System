<?php
session_start();
require_once "../register_and_login/dbconnection.php"; // Your database connection file

if (!isset($_SESSION['voterId']) || !isset($_POST['candidateId']) || !isset($_POST['password'])) {
  echo json_encode(['success' => false]);
  exit;
}

$userId = $_SESSION['voterId'];
$candidateId = $_POST['candidateId'];
$password = $_POST['password'];

// Verify voter's password
$query = "SELECT password FROM voters WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Begin a transaction to ensure consistency
$conn->begin_transaction();

try {

if ($row = $result->fetch_assoc()) {
  if (!password_verify($password, $row['password'])) {
    // echo json_encode(['success' => false, 'error' => 'password_mismatch']);
    throw new Exception('password_mismatch');
    // exit;
  }
} else {
  // echo json_encode(['success' => false, 'error' => 'voter_not_found']);
  throw new Exception('voter_not_found');
}


  // // Record the vote
  // $voteQuery = "INSERT INTO votes (voterId, candidateId) VALUES (?, ?)";
  // $voteStmt = $conn->prepare($voteQuery);
  // $voteStmt->bind_param("ii", $userId, $candidateId);
  // $voteStmt->execute();

  // Increment totalVotes for the selected candidate
  // Get the citizenshipNumber of the candidate according to the id
  $candidateQuery = "SELECT citizenship_number FROM candidates WHERE candidateId = ?";
  $candidateStmt = $conn->prepare($candidateQuery);
  $candidateStmt->bind_param("i", $candidateId);
  $candidateStmt->execute();
  $candidateResult = $candidateStmt->get_result();

$citizenshipNumber='';
  if ($candidateRow = $candidateResult->fetch_assoc()) {
    $citizenshipNumber = $candidateRow['citizenship_number'];
  } else {
    throw new Exception('candidate_not_found');
  }

  $updateVotesQuery = "UPDATE currentresults SET totalVotes = totalVotes + 1 WHERE citizenshipNumber = ?";
  $updateVotesStmt = $conn->prepare($updateVotesQuery);
  $updateVotesStmt->bind_param("s", $citizenshipNumber);
  $updateVotesStmt->execute();

  // Update the votingStatus of the voter to 'voted'
  $updateVoterStatusQuery = "UPDATE voters SET votingStatus = 'voted' WHERE id = ?";
  $updateVoterStatusStmt = $conn->prepare($updateVoterStatusQuery);
  $updateVoterStatusStmt->bind_param("i", $userId);
  $updateVoterStatusStmt->execute();
  $_SESSION['votingStatus'] ="voted";
  // Commit the transaction
  $conn->commit();
  echo json_encode(['success' => true]);
} catch (Exception $e) {
  // Roll back the transaction in case of an error
  $conn->rollback();
  echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>