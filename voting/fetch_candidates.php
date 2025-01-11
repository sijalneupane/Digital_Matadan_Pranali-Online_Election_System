<?php
session_start();
require_once "../register_and_login/dbconnection.php";

if (!isset($_SESSION['dId'])) {
  echo json_encode([]);
  exit;
}

$districtId = $_SESSION['dId'];
// $query = "SELECT candidateId,name FROM candidates WHERE dId = ?";
$query = "SELECT candidates.candidateId, candidates.name,candidates.candidate_photo, parties.partyName, parties.partyLogo 
FROM candidates 
JOIN parties 
ON candidates.partyId = parties.partyId
 WHERE candidates.dId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $districtId);
$stmt->execute();
$result = $stmt->get_result();

$candidates = [];
while ($row = $result->fetch_assoc()) {
  $candidates[] = $row;
}

echo json_encode($candidates);
?>