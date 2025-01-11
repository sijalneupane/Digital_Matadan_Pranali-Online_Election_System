<?php
require_once "../register_and_login/dbconnection.php";
// Check the connection
if (!$conn) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . mysqli_connect_error()]));
}
$searchQuery = $_GET['searchQuery'] ?? '';
$initialDistrict = $_GET['district'] ?? '';
$initialRegionNo = $_GET['regionNo'] ?? '';

$district = ($initialDistrict == 'default') ? '' : $initialDistrict;
$regionNo = ($initialRegionNo == 'default') ? '' : $initialRegionNo;
$query='';
if (empty($searchQuery) && empty($district) && empty($regionNo)) {
  // Case 1: All are empty
  $query = "SELECT 
              cr.electionId, cr.electionName, cr.candidateName, cr.citizenshipNumber, 
              cr.partyName, cr.dId, cr.totalVotes, 
              d.district, d.regionNo 
          FROM currentresults cr 
          JOIN district d ON cr.dId = d.dId";
} elseif (empty($searchQuery) && !empty($district) && !empty($regionNo)) {
  // Case 2: searchQuery is empty, but district and regionNo are not empty
  $query = "SELECT 
              cr.electionId, cr.electionName, cr.candidateName, cr.citizenshipNumber, 
              cr.partyName, cr.dId, cr.totalVotes, 
              d.district, d.regionNo 
          FROM currentresults cr 
          JOIN district d ON cr.dId = d.dId 
          WHERE d.district = '$district' AND d.regionNo = '$regionNo'
          ORDER BY cr.totalVotes DESC";
} elseif (!empty($searchQuery) && empty($district) && empty($regionNo)) {
  // Case 3: searchQuery is not empty, but district and regionNo are empty
  $query = "SELECT 
              cr.electionId, cr.electionName, cr.candidateName, cr.citizenshipNumber, 
              cr.partyName, cr.dId, cr.totalVotes, 
              d.district, d.regionNo 
          FROM currentresults cr 
          JOIN district d ON cr.dId = d.dId 
          WHERE cr.candidateName LIKE '%$searchQuery%'";
} elseif (!empty($searchQuery) && !empty($district) && empty($regionNo)) {
  // Case 4: searchQuery and district are not empty, regionNo is empty
  $query = "SELECT 
              cr.electionId, cr.electionName, cr.candidateName, cr.citizenshipNumber, 
              cr.partyName, cr.dId, cr.totalVotes, 
              d.district, d.regionNo 
          FROM currentresults cr 
          JOIN district d ON cr.dId = d.dId 
          WHERE cr.candidateName LIKE '%$searchQuery%' 
            AND d.district = '$district'";
// } elseif (!empty($searchQuery) && empty($district) && !empty($regionNo)) {
//   // Case 5: searchQuery and regionNo are not empty, district is empty
//   $query = "SELECT 
//               cr.electionId, cr.electionName, cr.candidateName, cr.citizenshipNumber, 
//               cr.partyName, cr.dId, cr.totalVotes, 
//               d.district, d.regionNo 
//           FROM currentresults cr 
//           JOIN district d ON cr.dId = d.dId 
//           WHERE  cr.candidateName LIKE '%$searchQuery%' 
//             AND d.regionNo = '$regionNo'";
} elseif (empty($searchQuery) && !empty($district) && empty($regionNo)) {
  // Case 6: searchQuery is empty, district is not empty, regionNo is empty
  $query = "SELECT 
              cr.electionId, cr.electionName, cr.candidateName, cr.citizenshipNumber, 
              cr.partyName, cr.dId, cr.totalVotes, 
              d.district, d.regionNo 
          FROM currentresults cr 
          JOIN district d ON cr.dId = d.dId 
          WHERE d.district = '$district'";
// } elseif (empty($searchQuery) && empty($district) && !empty($regionNo)) {
//   // Case 7: searchQuery is empty, district is empty, regionNo is not empty
//   $query = "SELECT 
//               cr.electionId, cr.electionName, cr.candidateName, cr.citizenshipNumber, 
//               cr.partyName, cr.dId, cr.totalVotes, 
//               d.district, d.regionNo 
//           FROM currentresults cr 
//           JOIN district d ON cr.dId = d.dId 
//           WHERE d.regionNo = '$regionNo'";
} else if(!empty($searchQuery) && !empty($district) && !empty($regionNo)) {
  // Case 8: All are not empty
  $query = "SELECT 
              cr.electionId, cr.electionName, cr.candidateName, cr.citizenshipNumber, 
              cr.partyName, cr.dId, cr.totalVotes, 
              d.district, d.regionNo 
          FROM currentresults cr 
          JOIN district d ON cr.dId = d.dId 
          WHERE cr.candidateName LIKE '%$searchQuery%'
            AND d.district = '$district' 
            AND d.regionNo = '$regionNo'
            ORDER BY cr.totalVotes DESC";
}else{
  $query = "SELECT 
   cr.electionId, cr.electionName, cr.candidateName, cr.citizenshipNumber, 
   cr.partyName, cr.dId, cr.totalVotes, 
   d.district, d.regionNo 
FROM currentresults cr 
JOIN district d ON cr.dId = d.dId";
}

try {
  $result = mysqli_query($conn, $query);

} catch (\Throwable $th) {

  echo json_encode(["status" => "error", "message" => $th->getMessage()]);
}
// Check if query was successful
if ($result) {
    $data = [];

    // Fetch all rows as an associative array
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    // Return the data as JSON
    echo json_encode(["status" => "success", "data" => $data]);
} else {
    echo json_encode(["status" => "error", "message" => "$district $regionNo Failed to fetch data: " . mysqli_error($conn)]);
}

// Close the connection
mysqli_close($conn);
?>
