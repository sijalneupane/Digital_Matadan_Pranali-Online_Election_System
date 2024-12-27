<?php

header('Content-Type: application/json');

require_once '../register_and_login/dbconnection.php';

// $searchQuery =$_POST['searchQuery'];
// $district = $_POST['district'];
// $party = $_POST['party'];

$searchQuery = $_GET['searchQuery'] ?? '';
$district = $_GET['district'] ?? '';
$party = $_GET['party'] ?? '';
    $sql="";
    if (!empty($searchQuery)) {
        $sql = "SELECT *
        FROM candidates 
        JOIN district ON candidates.dId = district.dId 
        JOIN parties ON candidates.partyId = parties.partyId 
        WHERE candidates.name LIKE '%$searchQuery%'";
        // $sql = "SELECT * FROM candidates WHERE name LIKE '%$searchQuery%'";
        if (!empty($district) && !empty($party)) {
            $sql .= " AND district.district = '$district' AND parties.partyName = '$party'";
        }else if (!empty($district)) {
            $sql .= " AND district.district = '$district'";
        }else if (!empty($party)) {
            $sql .= " AND parties.partyName = '$party'";
        }
    }
    if (empty($searchQuery)) {

        // $sql = "SELECT * FROM candidates WHERE name LIKE '%$searchQuery%'";
        if(!empty($district) && !empty($party)){
            $sql = "SELECT *
            FROM candidates 
            JOIN district ON candidates.dId = district.dId 
            JOIN parties ON candidates.partyId = parties.partyId where district.district = '$district' AND parties.partyName = '$party'";
        }else if (!empty($district)) {
            $sql = "SELECT *  
            FROM candidates 
            JOIN district ON candidates.dId = district.dId 
            JOIN parties ON candidates.partyId = parties.partyId where district.district = '$district'";
        }else if (!empty($party)) {
            $sql = "SELECT *
            FROM candidates 
            JOIN district ON candidates.dId = district.dId 
            JOIN parties ON candidates.partyId = parties.partyId where parties.partyName = '$party'";
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
                "manifesto" => $row["manifesto"],
                "district" => $row["district"],
                "regionNo" => $row["regionNo"],
                "partyName" => $row["partyName"],
                "candidate_photo" => $row["candidate_photo"]
            ];
        }
    } else {
        $response["message"] = "Error: " . $conn->error;
    }

echo json_encode($response);

$conn->close();
?>