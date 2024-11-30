<?php
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
$sql = "SELECT voting_start, voting_end FROM voting_time ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result)>0) {
    $row = mysqli_fetch_assoc($result);
    $voting_start = $row['voting_start'];
    $voting_end = $row['voting_end'];

    // Determine the voting status
    if ($current_time < $voting_start) {
        echo "Voting is not open yet.";
    } elseif ($current_time >= $voting_start && $current_time <= $voting_end) {
        echo "Voting is open.";
    } else {
        echo "Voting has finished.";
    }
} else {
    echo "Voting times are not set.";
}

mysqli_close($conn);
?>