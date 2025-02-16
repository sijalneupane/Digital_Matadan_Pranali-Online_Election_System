<?php
// This is the searchVoters.php file in the admin folder

header('Content-Type: application/json');

require_once '../register_and_login/dbconnection.php';
$searchQuery = $_GET['searchQuery'] ?? '';
$initialDistrict = $_GET['district'] ?? '';
$initialRegionNo = $_GET['regionNo'] ?? '';
$district = ($initialDistrict == 'default') ? '' : $initialDistrict;
$regionNo = ($initialRegionNo == 'default') ? '' : $initialRegionNo;
$voterType = $_GET['voterType'] ?? '';
$tableName = ($voterType == 'verified') ? 'voters' : 'pendingStatus';
$sql = "";
if (!empty($searchQuery)) {
  $sql = "SELECT *
        FROM $tableName 
        JOIN district ON $tableName.dId = district.dId
        where $tableName.name LIKE '%$searchQuery%'";
  if (!empty($district) && !empty($regionNo)) {
    $sql .= " AND district.district = '$district' AND district.regionNo = '$regionNo'";
  } else if (!empty($district) && empty($regionNo)) {
    $sql .= " AND district.district = '$district'";
  }
} else {
  $sql = "SELECT *
        FROM $tableName 
        JOIN district ON $tableName.dId = district.dId";
  if (!empty($district) && !empty($regionNo)) {
    $sql .= " WHERE district.district = '$district' AND district.regionNo = '$regionNo'";
  } else if (!empty($district) && empty($regionNo)) {
    $sql .= " WHERE district.district = '$district'";
  }
}
$result = $conn->query($sql);
$response = [];
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $response[] = [
      'id' => $row['id'],
      'name' => $row['name'],
      'email' => $row['email'],
      'dateOfBirth' => $row['dateOfBirth'],
      'citizenshipNumber' => $row['citizenshipNumber'],
      'gender' => $row['gender'],
      'district' => $row['district'],
      'regionNo' => $row['regionNo'],
      'localAddress' => $row['localAddress'],
      'citizenshipFrontPhoto' => $row['citizenshipFrontPhoto'],
      'citizenshipBackPhoto' => $row['citizenshipBackPhoto'],
      'userPhoto' => $row['userPhoto']
    ];
    if ($tableName == 'voters') {
      $response[count($response) - 1]['votingStatus'] = $row['votingStatus'];
    }
    if ($tableName == 'pendingStatus') {
      $response[count($response) - 1]['status'] = $row['status'];
    }
  }
}else{
  $response = ['error'=>true,'message' => 'No data found'];
}
echo json_encode($response);
?>