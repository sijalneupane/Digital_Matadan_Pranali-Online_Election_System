<?php
header('Content-Type: application/json');

// Database connection
require '../register_and_login/dbconnection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update the result status in the electiontime table
    $query = "UPDATE electiontime SET resultStatus = 'published' ORDER BY electionId DESC LIMIT 1";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true, 'message' => 'Result published successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update result status']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

mysqli_close($conn);
?>
