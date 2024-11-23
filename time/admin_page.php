<?php
// Connect to the database
$conn = mysqli_connect('localhost', 'root', '', 'online_election');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission to set the voting period
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_time = $_POST['voting_start'];
    $end_time = $_POST['voting_end'];

    // Insert or update voting time
    $sql = "INSERT INTO voting_time (voting_start, voting_end) VALUES ('$start_time', '$end_time')
            ON DUPLICATE KEY UPDATE voting_start='$start_time', voting_end='$end_time'";

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
        <label for="voting_start">Voting Start Time:</label>
        <input type="datetime-local" name="voting_start" required>
        <br><br>
        <label for="voting_end">Voting End Time:</label>
        <input type="datetime-local" name="voting_end" required>
        <br><br>
        <button type="submit">Set Voting Time</button>
    </form>
</body>
</html>
