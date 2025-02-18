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
    }

    // Start transaction
    mysqli_begin_transaction($conn);
    try {
        //for updating the electionTime
        if ($electionId) {
            // Check if the election result has been published
            $checkQuery = "SELECT resultStatus FROM electiontime WHERE electionId = $electionId";
            $result = mysqli_query($conn, $checkQuery);

            if ($row = mysqli_fetch_assoc($result)) {
                if ($row['resultStatus'] === 'published') {
                    throw new Exception("Cannot update an election whose result has been published.");
                }

                // Update election details
                $sql = "UPDATE electiontime SET electionName = '$electionName', startTime = '$startTime', endTime = '$endTime', nominationStartTime = '$nominationStartTime', nominationEndTime = '$nominationEndTime' WHERE electionId = $electionId";
                mysqli_query($conn, $sql);
                $_SESSION['successMsg'] = "Election updated successfully.";
            } else {
                throw new Exception("Election not found.");
            }

            //for adding new election time
        } else {
            // Check if the latest election's result is published
            $checkQuery = "SELECT resultStatus FROM electiontime ORDER BY electionId DESC LIMIT 1";
            $result = mysqli_query($conn, $checkQuery);

            if ($row = mysqli_fetch_assoc($result)) {
                if ($row['resultStatus'] === 'notPublished') {
                    throw new Exception("Cannot set a new election as the previous election result is not published.");
                }
            }

            // Insert new election
            $sql = "INSERT INTO electiontime (electionName, startTime, endTime, nominationStartTime, nominationEndTime) VALUES ('$electionName', '$startTime', '$endTime', '$nominationStartTime', '$nominationEndTime')";
            mysqli_query($conn, $sql);

            // Clear all the data from currentResults
            $deleteSql = "DELETE FROM currentResults";
            if (!mysqli_query($conn, $deleteSql)) {
                throw new Exception("Error deleting data from currentResults: " . mysqli_error($conn));
            }
            // Reset auto-increment for currentResults
            mysqli_query($conn, "ALTER TABLE currentResults AUTO_INCREMENT = 1");

            // Clear all the data from candidates
            $deleteCandidatesSql = "DELETE FROM candidates";
            if (!mysqli_query($conn, $deleteCandidatesSql)) {
                throw new Exception("Error deleting data from candidates: " . mysqli_error($conn));
            }
            // Reset auto-increment for candidates
            mysqli_query($conn, "ALTER TABLE candidates AUTO_INCREMENT = 1");
            $_SESSION['successMsg'] = "Election added successfully.";
        }

        // Commit transaction
        mysqli_commit($conn);
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION['errorMsg'] = "Error: " . $e->getMessage();
    }
} else {
    $_SESSION['errorMsg'] = "Invalid request method.";
}

mysqli_close($conn);
header("Location: ../admin/admin_home.php");
exit();
?>