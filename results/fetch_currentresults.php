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
$query = '';
if (empty($searchQuery) && empty($district) && empty($regionNo)) {
  // Case 1: All are empty
  $query = "SELECT 
            cr.currentResultId, cr.electionId, e.electionName, c.name, c.citizenship_number, 
           c.candidate_photo,p.partyLogo,p.partyThemeColor, p.partyName, cr.dId, cr.totalVotes, 
            d.district, d.regionNo 
          FROM currentresults cr 
          JOIN district d ON cr.dId = d.dId
        JOIN candidates c ON cr.candidateId = c.candidateId
        JOIN parties p ON cr.partyId = p.partyId
        JOIN electiontime e ON cr.electionId = e.electionId";
} elseif (empty($searchQuery) && !empty($district) && !empty($regionNo)) {
  // Case 2: searchQuery is empty, but district and regionNo are not empty
  $query = "SELECT 
            cr.currentResultId, cr.electionId, e.electionName, c.name, c.citizenship_number, 
            c.candidate_photo,p.partyLogo,p.partyThemeColor,p.partyName, cr.dId, cr.totalVotes, 
            d.district, d.regionNo
          FROM currentresults cr 
          JOIN district d ON cr.dId = d.dId
        JOIN candidates c ON cr.candidateId = c.candidateId
        JOIN parties p ON cr.partyId = p.partyId
        JOIN electiontime e ON cr.electionId = e.electionId
          WHERE d.district = '$district'
          AND d.regionNo = '$regionNo'
          ORDER BY cr.totalVotes DESC";
} elseif (!empty($searchQuery) && empty($district) && empty($regionNo)) {
  // Case 3: searchQuery is not empty, but district and regionNo are empty
  $query = "SELECT 
              cr.currentResultId, cr.electionId, e.electionName, c.name, c.citizenship_number, 
            c.candidate_photo,p.partyLogo,p.partyThemeColor,p.partyName, cr.dId, cr.totalVotes, 
            d.district, d.regionNo
          FROM currentresults cr 
          JOIN district d ON cr.dId = d.dId
        JOIN candidates c ON cr.candidateId = c.candidateId
        JOIN parties p ON cr.partyId = p.partyId
        JOIN electiontime e ON cr.electionId = e.electionId
          WHERE c.name LIKE '%$searchQuery%'";
} elseif (!empty($searchQuery) && !empty($district) && empty($regionNo)) {
  // Case 4: searchQuery and district are not empty, regionNo is empty
  $query = "SELECT 
              cr.currentResultId, cr.electionId, e.electionName, c.name, c.citizenship_number, 
           c.candidate_photo,p.partyLogo,p.partyThemeColor, p.partyName, cr.dId, cr.totalVotes, 
            d.district, d.regionNo
          FROM currentresults cr 
          JOIN district d ON cr.dId = d.dId
        JOIN candidates c ON cr.candidateId = c.candidateId
        JOIN parties p ON cr.partyId = p.partyId
        JOIN electiontime e ON cr.electionId = e.electionId
          WHERE c.name LIKE '%$searchQuery%' 
            AND d.district = '$district'";

} elseif (empty($searchQuery) && !empty($district) && empty($regionNo)) {
  // Case 6: searchQuery is empty, district is not empty, regionNo is empty
  $query = "SELECT 
              cr.currentResultId, cr.electionId, e.electionName, c.name, c.citizenship_number, 
            c.candidate_photo,p.partyLogo,p.partyThemeColor,p.partyName, cr.dId, cr.totalVotes, 
            d.district, d.regionNo
            FROM currentresults cr 
            JOIN district d ON cr.dId = d.dId
        JOIN candidates c ON cr.candidateId = c.candidateId
        JOIN parties p ON cr.partyId = p.partyId
        JOIN electiontime e ON cr.electionId = e.electionId
            WHERE d.district = '$district'";

} else if (!empty($searchQuery) && !empty($district) && !empty($regionNo)) {
  // Case 8: All are not empty
  $query = "SELECT 
            cr.currentResultId, cr.electionId, e.electionName, c.name, c.citizenship_number, 
            c.candidate_photo,p.partyLogo,p.partyThemeColor,p.partyName, cr.dId, cr.totalVotes, 
            d.district, d.regionNo
            FROM currentresults cr 
            JOIN district d ON cr.dId = d.dId
        JOIN candidates c ON cr.candidateId = c.candidateId
        JOIN parties p ON cr.partyId = p.partyId
        JOIN electiontime e ON cr.electionId = e.electionId
            WHERE c.name LIKE '%$searchQuery%'
            AND d.district = '$district' 
            AND d.regionNo = '$regionNo'
            ORDER BY cr.totalVotes DESC";
} else {
  $query = "SELECT 
    cr.currentResultId, cr.electionId, e.electionName, c.name, c.citizenship_number, 
    c.candidate_photo,p.partyLogo,p.partyThemeColor,p.partyName, cr.dId, cr.totalVotes, 
    d.district, d.regionNo
    FROM currentresults cr 
   JOIN district d ON cr.dId = d.dId
        JOIN candidates c ON cr.candidateId = c.candidateId
        JOIN parties p ON cr.partyId = p.partyId
        JOIN electiontime e ON cr.electionId = e.electionId";
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