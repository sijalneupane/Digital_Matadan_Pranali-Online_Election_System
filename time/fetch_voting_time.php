<?php
header("Content-Type: application/json");
date_default_timezone_set('Asia/Kathmandu');

// Connect to the database
require_once '../register_and_login/dbconnection.php';

// Get current time
$current_time = date('Y-m-d H:i:s');

// Fetch the most recent row from electiontime
$sql = "SELECT * FROM electiontime ORDER BY electionId DESC LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo json_encode($row);
} else {
    echo json_encode(array('error' => 'No election scheduled'));
}

mysqli_close($conn);
?>