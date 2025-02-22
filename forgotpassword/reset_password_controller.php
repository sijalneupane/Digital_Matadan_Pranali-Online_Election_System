<?php 
session_start();
if (!isset($_SESSION['fpEmail'])) {
    if(!isset($_SESSION['email'])){
        header("Location: ../forgotpassword/forgot_password.php");
        exit();
    }
    // header("Location: ../register_and_login/user_profile.php");
    // exit();
}
$id = isset($_GET['id']) ? $_GET['id'] : null;

// $successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
// unset($_SESSION['success_message']); // Clear the message

require '../register_and_login/dbconnection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($id != null) {
        $new_password = $_POST['password'];
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE voters SET password = '$hashed_password' WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            // Notify the user of successful password reset
            $_SESSION['success_message'] = "Password has been reset successfully. '$id'";
            header("Location: ../register_and_login/user_profile.php");
        } else {
            // Handle update error
            // echo "Error updating password: " . mysqli_error($conn);
            $_session['error_message'] = "Error updating password. PLease try again $id";
            header("Location: ../register_and_login/user_profile.php");
        }
        // $id = null;
    } else {
        // Fetch new password from the form
        $new_password = $_POST['password'];
        $email = $_SESSION['fpEmail'];

        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update query with the hashed password
        $sql1 = "UPDATE pendingVoters SET password = '$hashed_password' WHERE email = '$email'";
        $sql2 = "UPDATE voters SET password = '$hashed_password' WHERE email = '$email'";

        $result = isset($_SESSION['pending']) ? mysqli_query($conn, $sql1) : mysqli_query($conn, $sql2);
        unset($_SESSION['pending']);
        if ($result) {
            // Notify the user of successful password reset
            $_SESSION['success_message'] = "Password has been reset successfully.";
            unset($_SESSION['fpEemail']); // Clear session
            header("Location: ../register_and_login/voter_login_form.php");
            exit();
        } else {
            // Handle update error
            // echo "Error updating password: " . mysqli_error($conn);
            $_session['error_message'] = "Error updating password. PLease try again";
            header("Location: ../forgotpassword/reset_password.php");
        }
    }
    // Close the connection
    mysqli_close($conn);
}