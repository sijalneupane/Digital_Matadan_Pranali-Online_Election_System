<?php
// Connect to the database
$conn = mysqli_connect('localhost', 'root', '', 'online_election');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission to set the voting period
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_time = $_POST['startTime'];
    $end_time = $_POST['endTime'];

    // Insert or update voting time
    $sql = "INSERT INTO electiontime (startTime, endTime) VALUES ('$start_time', '$end_time')
            ON DUPLICATE KEY UPDATE startTime='$start_time', endTime='$end_time'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Voting time allocated succesffully')</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Set Voting Time</title>
</head>
<body>
    <h2>Set Voting Time</h2>
    <form method="post" action="">
        <label for="startTime">Voting Start Time:</label>
        <input type="datetime-local" name="startTime" required>
        <br><br>
        <label for="endTime">Voting End Time:</label>
        <input type="datetime-local" name="endTime" required>
        <br><br>
        <button type="submit">Set Voting Time</button>
    </form>
</body>
</html>
