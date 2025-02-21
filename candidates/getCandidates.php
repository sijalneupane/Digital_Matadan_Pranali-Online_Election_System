<?php
header('Content-Type: application/json');

require_once '../register_and_login/dbconnection.php';
$searchQuery = $_GET['searchQuery'] ?? '';
$initialDistrict = $_GET['district'] ?? '';
$initialRegionNo = $_GET['regionNo'] ?? '';
$district = ($initialDistrict == 'default') ? '' : $initialDistrict;
$regionNo = ($initialRegionNo == 'default') ? '' : $initialRegionNo;
$sql = "";
if ($searchQuery == '1' ) {
    $sql = "SELECT *
        FROM candidates 
        JOIN district ON candidates.dId = district.dId 
        JOIN parties ON candidates.partyId = parties.partyId";
} else if (!empty($searchQuery)) {
    $sql = "SELECT *
        FROM candidates 
        JOIN district ON candidates.dId = district.dId 
        JOIN parties ON candidates.partyId = parties.partyId 
        WHERE candidates.name LIKE '%$searchQuery%'";
    // $sql = "SELECT * FROM candidates WHERE name LIKE '%$searchQuery%'";
    if (!empty($district) && !empty($regionNo)) {
        $sql .= " AND district.district = '$district' AND district.regionNo = '$regionNo'";
    } else if (!empty($district)) {
        $sql .= " AND district.district = '$district'";
    }
}
if (empty($searchQuery)) {

    // $sql = "SELECT * FROM candidates WHERE name LIKE '%$searchQuery%'";
    if (!empty($district) && !empty($regionNo)) {
        $sql = "SELECT *
            FROM candidates 
            JOIN district ON candidates.dId = district.dId 
            JOIN parties ON candidates.partyId = parties.partyId 
            where district.district = '$district' AND district.regionNo = '$regionNo'";
    } else if (!empty($district)) {
        $sql = "SELECT *  
            FROM candidates 
            JOIN district ON candidates.dId = district.dId 
            JOIN parties ON candidates.partyId = parties.partyId where district.district = '$district'";
    }
}
// $sql.=" LIMIT 1844674407370";
$result = $conn->query($sql);

$response = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response[] = [
            "candidateId" => $row["candidateId"],
            "name" => $row["name"],
            "dob" => $row["dob"],
            "gender" => $row["gender"],
            "citizenship_number" => $row["citizenship_number"],
            "education_level" => $row["education_level"],
            "manifesto" =>  nl2br(htmlspecialchars($row["manifesto"])),
            "district" => $row["district"],
            "regionNo" => $row["regionNo"],
            "partyName" => $row["partyName"],
            "partyLogo" => $row["partyLogo"],
            "candidate_photo" => $row["candidate_photo"]
        ];
    }
} else if (mysqli_num_rows($result) == 0) {
    $response["message"] = "No candidates found";

} else {
    $response["message"] = "Error: " . $conn->error;
}

echo json_encode($response, JSON_UNESCAPED_UNICODE); 

$conn->close();
?>