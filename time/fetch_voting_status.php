<?php
// header("Content-Type:application/json");
date_default_timezone_set('Asia/Kathmandu');

// Connect to the database
$conn = mysqli_connect('localhost', 'root', '', 'online_election');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set the correct time zone

// Get current time
$current_time = date('Y-m-d H:i:s');

// Fetch the most recent voting times
$sql = "SELECT startTime, endTime FROM electiontime ORDER BY electionId DESC LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result)>0) {
    $row = mysqli_fetch_assoc($result);
    $startTime = $row['startTime'];
    $endTime = $row['endTime'];
    // echo json_encode($row);
    // Determine the voting status
    if ($current_time < $startTime) {
        echo "Voting is not open yet.";
    } elseif ($current_time >= $startTime && $current_time <= $endTime) {
        echo "Voting is open.";
    } else {
        echo "Voting has finished.";
    }
} else {
    // echo json_encode(array('error'=> 'Error'.mysqli_error($conn).''));
    echo "No election scheduled";
}

mysqli_close($conn);
?>