<?php
session_start();
require_once '../home/email_send.php';  // Include your email_send.php file
require_once '../register_and_login/dbconnection.php'; // Include your database connection

// Get URL parameters
$id = $_GET['id'];
$message = urldecode($_GET['message']);
$email = urldecode($_GET['email']);
$name = urldecode($_GET['name']);
$action = $_GET['action'];

if ($action == 'message') {
    // Send an email
    $subject = "Notification From Digital Matadan Pranali";
    $body = $message;
    $result = sendMail($email, $name, $subject, $body);  // Assuming send_mail is defined in email_send.php

    if ($result) {
        $_SESSION['successMsg'] = "Message sent successfully.";
    } else {
        $_SESSION['errorMsg'] = "Failed to send message.";
    }
} elseif ($action == 'delete') {
    // Start a transaction
    mysqli_begin_transaction($conn);

    try {
        // Select citizenship photos and user photo
        $query = "SELECT citizenshipFrontPhoto, citizenshipBackPhoto, userPhoto FROM voters WHERE id = '$id'";
        $result = mysqli_query($conn, $query);
        if ($result) {
            $row = mysqli_fetch_assoc($result);

            // Delete the photos from the server (if needed)
            $citizenshipFrontPhoto ="../uploads/". $row['citizenshipFrontPhoto'];
            $citizenshipBackPhoto = "../uploads/". $row['citizenshipBackPhoto'];
            $userPhoto ="../uploads/".  $row['userPhoto'];

            if (file_exists($citizenshipFrontPhoto)) {
                unlink($citizenshipFrontPhoto);
            }
            if (file_exists($citizenshipBackPhoto)) {
                unlink($citizenshipBackPhoto);
            }
            if (file_exists($userPhoto)) {
                unlink($userPhoto);
            }

            // Delete the voter data from the database
            $deleteQuery = "DELETE FROM voters WHERE id = '$id'";
            $deleteResult = mysqli_query($conn, $deleteQuery);

            if ($deleteResult) {
                // Commit transaction
                mysqli_commit($conn);
                $_SESSION['successMsg'] = "Voter deleted successfully and photos removed.";
                
                // Send success email
                $subject = "Voter Deletion Notification";
                $body = "your voter data has been successfully deleted due to following reason:<br> <h2>$message<h2>";
                sendMail($email, $name, $subject, $body);
            } else {
                // Rollback transaction
                mysqli_rollback($conn);
                $_SESSION['errorMsg'] = "Failed to delete voter data.";
            }
        } else {
            $_SESSION['errorMsg'] = "Voter not found.";
        }
    } catch (Exception $e) {
        // Rollback transaction in case of an error
        mysqli_rollback($conn);
        $_SESSION['errorMsg'] = "Error occurred: " . $e->getMessage();
    }
}

// Redirect back to the previous page or a specific page
mysqli_close($conn);
header('Location: ../admin/manage_voters.php');  // Modify to redirect as needed
?>
