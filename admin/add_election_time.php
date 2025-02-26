<?php
session_start();

// Database connection
require_once '../register_and_login/dbconnection.php';
require_once '../fpdf186/fpdf.php'; // Ensure you have FPDF installed

// Function to generate election notice PDF
function generateElectionNotice($subject, $body, $electionId,$updatedOrAdded, $nominationStart, $nominationEnd, $votingStart, $votingEnd)
{
    try {
        // Set memory and execution time limits to prevent crashes
        ini_set('memory_limit', '128M');
        set_time_limit(30);

        // Create PDF object
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        // Set margins
        $pdf->SetLeftMargin( 25.4); // 1 inch to mm
        $pdf->SetRightMargin(25.4); // 1 inch to mm
        $pdf->SetTopMargin(25.4); // 1 inch to mm
        $pdf->SetAutoPageBreak(true, 25.4); // 1 inch to mm for bottom margin

        // Header
        $pdf->Cell(0, 10, "Election Commission of Nepal", 0,  1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, "Digital Matadan Pranali", 0, 1, 'C');
        $pdf->Image('../images/DMP logo.png', 25.4, 10, 30); // Ensure logo path is correct
        $pdf->Ln(10);

        // Subject
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, "Subject: $subject", 0, 1, 'C');
        $pdf->Ln(5);

        // Notice body
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 8, $body);

        // Nomination details
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, "Nomination Period:", 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 8, "Start: $nominationStart, End: $nominationEnd");
        $pdf->Ln(3);

        // Voting details
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, "Voting Period:", 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 8, "Start: $votingStart, End: $votingEnd");
        $pdf->Ln(3);

        // Appeal to citizens
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 5, "Important Notice:", 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 8, "
- All eligible citizens are requested to register if they haven't yet.
- Candidates who wish to participate must visit the election office with necessary documents.
- Any new parties must be registered officially before the election date.
- Registered voters should log in to the website and vote during the voting period.
- The results will be published on the website's result page.",
        0, 'J'
    );

        // Footer
        $pdf->Ln(80);
        // $pdf->Cell(0, 8, "Signature: _______________", 0, 1, 'L');
        $pdf->Cell(0, 8, "Election Commission of Nepal", 0, 1, align: 'L');

        $filename = "../notices/Election_Notice_" . $electionId . $updatedOrAdded."_" . time() . ".pdf";
        // Save the PDF file
        $pdf->Output($filename, 'F');

        // Check if file was created successfully
        if (!file_exists($filename)) {
            throw new Exception("Failed to create PDF file.");
        }

        $_SESSION['newNoticeName']=$filename;
        return true; // Return true since file was successfully downloaded
       
    } catch (Exception $e) {
        // Log error and return false
        error_log("Error generating election notice: " . $e->getMessage(), 3, "../notices/error_log.txt");
        return false;
    }
}
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
    // Convert to required format for inserting in pdf
    $formattedStartTime = date("Y-m-d h:i:s A", strtotime($startTime));
    $formattedEndTime = date("Y-m-d h:i:s A", strtotime($endTime));
    $formattedNominationStartTime = date("Y-m-d h:i:s A", strtotime($nominationStartTime));
    $formattedNominationEndTime = date("Y-m-d h:i:s A", strtotime($nominationEndTime));

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
                $subject = "$electionName Election Updated";
                $body = "We hereby inform the citizens that a election ($electionName) has been updated. Below are the important details:";
                if (!generateElectionNotice($subject, $body, $electionId, "(updated)",$formattedNominationStartTime, $formattedNominationEndTime, $formattedStartTime, $formattedEndTime)) {
                    throw new Exception("Failed to generate election notice PDF.");
                }
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
            $newElectionId = mysqli_insert_id($conn);

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
            $subject = "New Election Scheduled";
            $body = "We hereby inform the citizens that a new election ($electionName) has been scheduled. Below are the important details:";
            if (!generateElectionNotice($subject, $body, $newElectionId, "(added)",$formattedNominationStartTime, $formattedNominationEndTime, $formattedStartTime, $formattedEndTime)) {
                throw new Exception("Failed to generate election notice PDF.");
            }
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