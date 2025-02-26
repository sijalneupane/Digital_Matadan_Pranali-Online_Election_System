<?php
header('Content-Type: application/json');
require '../register_and_login/dbconnection.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Get the latest electionId and electionName
        $query = "SELECT electionId, electionName FROM electiontime ORDER BY electionId DESC LIMIT 1";
        $result = mysqli_query($conn, $query);
        if (!$result || mysqli_num_rows($result) === 0) {
            throw new Exception("No election found.");
        }
        $election = mysqli_fetch_assoc($result);
        $electionId = $election['electionId'];
        $electionName = $election['electionName'];

        // Step 1: Update result status
        $query = "UPDATE electiontime SET resultStatus = 'published' WHERE electionId = $electionId";
        if (!mysqli_query($conn, $query)) {
            throw new Exception("Failed to update result status.");
        }

        // Check if there is any data in currentResults table
        $query = "SELECT COUNT(*) as count FROM currentResults";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            throw new Exception("Failed to check currentResults table.");
        }
        $row = mysqli_fetch_assoc($result);
        if ($row['count'] == 0) {
            throw new Exception("Cannot archive empty data. Try updating current election");
        } else {
            // Step 2: Insert data from currentresults into archiveresults (without deleting from currentresults)
            $query = "INSERT INTO archiveresults (electionId, electionName, candidateName, citizenshipNumber, partyName, dId, totalVotes,candidatePhoto)
        SELECT 
            cr.electionId, 
            '$electionName', 
            c.name, 
            c.citizenship_number,
            p.partyName, 
            cr.dId, 
            cr.totalVotes,
            c.candidate_photo
        FROM currentresults cr
        INNER JOIN candidates c ON cr.candidateId = c.candidateId
        INNER JOIN parties p ON cr.partyId = p.partyId
        WHERE cr.electionId = $electionId
    ";
            if (!mysqli_query($conn, $query)) {
                throw new Exception("Failed to insert into archive results.".mysqli_error($conn));
            }
           
        }

        // Commit transaction
        mysqli_commit($conn); 
        echo json_encode(['success' => true, 'message' => 'Result status updated and data archived successfully']);

    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }

} else {
    $_SESSION['errorMsg'] = "Invalid request method";
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

mysqli_close($conn);
?>