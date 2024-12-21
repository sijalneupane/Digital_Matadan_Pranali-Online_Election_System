<?php
// Database connection parameters
require '../register_and_login/dbconnection.php';

if (isset($_GET['district'])) {
    $district = mysqli_real_escape_string($conn, $_GET['district']);

    // Fetch the region count for the given district
    $sql = "SELECT COUNT(regionNo) AS region_count FROM district WHERE district = '$district'";
    $result = mysqli_query($conn, $sql);
    $totalRegion=[];
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $totalRegion= $row;
    } 
    echo json_encode($totalRegion);
} else {
    echo json_encode(['error' => 'Invalid request']); // Invalid request
}

// Close connection
mysqli_close($conn);