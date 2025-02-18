<?php
session_start();
require_once "../register_and_login/dbconnection.php"; // Your database connection file

if (!isset($_SESSION['voterId']) || !isset($_POST['candidateId']) || !isset($_POST['password'])) {
    echo json_encode(['success' => false]);
    exit;
}

$userId = intval($_SESSION['voterId']);
$candidateId = intval($_POST['candidateId']);
$password = $_POST['password'];

// Begin a transaction to ensure consistency
mysqli_begin_transaction($conn);

try {
    // Verify voter's password
    $query = "SELECT password FROM voters WHERE id = $userId";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        if (!password_verify($password, $row['password'])) {
            throw new Exception('password_mismatch');
        }
    } else {
        throw new Exception('voter_not_found');
    }

    // Get the citizenshipNumber of the candidate according to the id
    // $candidateQuery = "SELECT citizenship_number FROM candidates WHERE candidateId = $candidateId";
    // $candidateResult = mysqli_query($conn, $candidateQuery);

    // if ($candidateRow = mysqli_fetch_assoc($candidateResult)) {
    //     $citizenshipNumber = $candidateRow['citizenship_number'];
    // } else {
    //     throw new Exception('candidate_not_found');
    // }

    // Increment totalVotes for the selected candidate
    $updateVotesQuery = "UPDATE currentresults SET totalVotes = totalVotes + 1 WHERE candidateId = '$candidateId'";
    if (!mysqli_query($conn, $updateVotesQuery)) {
        throw new Exception('vote_update_failed');
    }

    // Update the votingStatus of the voter to 'voted'
    $updateVoterStatusQuery = "UPDATE voters SET votingStatus = 'voted' WHERE id = $userId";
    if (!mysqli_query($conn, $updateVoterStatusQuery)) {
        throw new Exception('voter_status_update_failed');
    }

    $_SESSION['votingStatus'] = "voted";

    // Commit the transaction
    mysqli_commit($conn);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Roll back the transaction in case of an error
    mysqli_rollback($conn);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
