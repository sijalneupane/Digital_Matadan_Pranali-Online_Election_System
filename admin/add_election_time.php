<?php
session_start();

// Database connection
require_once '../register_and_login/dbconnection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $electionId = isset($_POST['electionId']) ? intval($_POST['electionId']) : null;
    $electionName = isset($_POST['electionName']) ? $_POST['electionName'] : "";
    $startTime = isset($_POST['startTime']) ? $_POST['startTime'] : "";
    $endTime = isset($_POST['endTime']) ? $_POST['endTime'] : "";
    $nominationStartTime = isset($_POST['nominationStartTime']) ? $_POST['nominationStartTime'] : "";
    $nominationEndTime = isset($_POST['nominationEndTime']) ? $_POST['nominationEndTime'] : "";

    if (empty($electionName) || empty($startTime) || empty($endTime) || empty($nominationStartTime) || empty($nominationEndTime)) {
        $_SESSION['errorMsg'] = "All fields except Election ID are required.";
        header("Location: ../admin/admin_home.php");
        exit();
    } else {
        if ($electionId) {
            // Update existing record
            $sql = "UPDATE electiontime SET electionName = '$electionName', startTime = '$startTime', endTime = '$endTime', nominationStartTime = '$nominationStartTime', nominationEndTime = '$nominationEndTime' WHERE electionId = $electionId";

            if (mysqli_query($conn, $sql)) {
                $_SESSION['errorMsg'] = "Election updated successfully.";
            } else {
                $_SESSION['errorMsg'] = "Error updating election: " . mysqli_error($conn);
            }
        } else {
            // Insert new record
            $sql = "INSERT INTO electiontime (electionName, startTime, endTime, nominationStartTime, nominationEndTime) VALUES ('$electionName', '$startTime', '$endTime', '$nominationStartTime', '$nominationEndTime')";

            if (mysqli_query($conn, $sql)) {
                $_SESSION['errorMsg'] = "Election added successfully.";
            } else {
                $_SESSION['errorMsg'] = "Error adding election: " . mysqli_error($conn);
            }
        }
    }
} else {
    $_SESSION['errorMsg'] = "Invalid request method.";
}

mysqli_close($conn);
header("Location: ../admin/admin_home.php");
exit();
?>