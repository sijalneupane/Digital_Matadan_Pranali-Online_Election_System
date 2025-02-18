<?php
session_start();
require '../home/email_send.php';
require '../register_and_login/dbconnection.php';

if (isset($_GET['id']) && isset($_GET['message']) && isset($_GET['email']) && isset($_GET['name'])) {
    $id = intval($_GET['id']);
    $message = mysqli_real_escape_string($conn, $_GET['message']);
    $email = mysqli_real_escape_string($conn, $_GET['email']);
    $name = mysqli_real_escape_string($conn, $_GET['name']);

    // Start transaction
    mysqli_begin_transaction($conn);

    // Step 1: Fetch image paths before deleting the record
    $sql1 = "SELECT citizenshipFrontPhoto, citizenshipBackPhoto, userPhoto FROM pendingstatus WHERE id = '$id'";
    $result1 = mysqli_query($conn, $sql1);

    if ($result1 && mysqli_num_rows($result1) > 0) {
        $row1 = mysqli_fetch_assoc($result1);

        $imagePaths = [
            "../uploads/" . $row1['citizenshipFrontPhoto'],
            "../uploads/" . $row1['citizenshipBackPhoto'],
            "../uploads/" . $row1['userPhoto'],
        ];

        // Step 2: Delete the record from the database
        $query = "DELETE FROM pendingstatus WHERE id = '$id'";
        if (mysqli_query($conn, $query)) {

            // Step 3: Send rejection email
            $emailSent = sendMail($email, $name, "Account verification failed", 
                "Your account has failed to be verified by us. <br>The reason is: " . $message);

            if ($emailSent) {
                // Step 4: Delete images from the server
                $allDeleted = true;
                foreach ($imagePaths as $path) {
                    if (!empty($path) && file_exists($path) && !unlink($path)) {
                        $allDeleted = false;
                    }
                }

                if ($allDeleted) {
                    mysqli_commit($conn);
                    $_SESSION['successMsg'] = "Application declined, email sent, and images deleted successfully";
                } else {
                    // If image deletion fails, rollback
                    sendMail($email, $name, "Account deletion failed", 
                        "Your account was declined, but we encountered an issue deleting your images. Please contact support.");
                    mysqli_rollback($conn);
                    $_SESSION['errorMsg'] = "Image deletion failed, rollback performed";
                }
            } else {
                // If email fails, rollback
                mysqli_rollback($conn);
                $_SESSION['errorMsg'] = "Failed to send rejection email";
            }
        } else {
            // If delete query fails, rollback
            mysqli_rollback($conn);
            $_SESSION['errorMsg'] = "Failed to delete record: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['errorMsg'] = "No images found";
    }
} else {
    $_SESSION['errorMsg'] = "Invalid request";
}

// echo $_SESSION['errorMsg'];
mysqli_close($conn);
header('Location: ../admin/manage_voters.php');
exit();
?>
