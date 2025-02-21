<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../forgotpassword/forgot_password.php");
    exit();
}
$errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['error_message']); // Clear the message

// $successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
// unset($_SESSION['success_message']); // Clear the message

require '../register_and_login/dbconnection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch new password from the form
    $new_password = $_POST['password'];
    $email = $_SESSION['email'];

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
        unset($_SESSION['email']); // Clear session
        header("Location: ../register_and_login/voter_login_form.php");
        exit();
    } else {
        // Handle update error
        // echo "Error updating password: " . mysqli_error($conn);
        $_session['error_message'] = "Error updating password. PLease try again";
        header("Location: ../forgotpassword/reset_password.php");
    }

    // Close the connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>
    <link rel="icon" href="../images/DMP logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../forgotpassword/style1.css">
    <link rel="stylesheet" href="../styles/modal1.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="../js/togglepassword.js"></script>

    <script>
        function validateForm() {
            const password = document.getElementById('password').value.trim();
            const passwordError = document.getElementById('passwordError');
            let passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/;

            let isValid = true;

            // Password validation
            if (password === "") {
                passwordError.textContent = "Password should not be empty";
                isValid = false;
            } else if (!passwordRegex.test(password)) {
                passwordError.textContent = "Password must include 6-20 characters, uppercase, lowercase, digit, and special character";
                isValid = false;
            } else {
                passwordError.textContent = "";
            }

            // Return whether the form is valid or not
            return isValid;
        }
    </script>
    <style>
        .input-container {
            position: relative;
            width: 100%;
            margin-bottom: 5px;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
    <script src="../js/errorMessage_modal1.js"></script>
</head>

<body>
<div id="modal1" class="modal-overlay1">
        <div class="modal-content1">
            <p id="modalMessage1"></p>
            <button onclick="closeModal1()">Close</button>
        </div>
    </div>
    <!-- Logo Section -->
    <div class="logo-container">
        <img src="../images/DMP logo.png" alt="Logo">
    </div>

    <h2>Reset Password</h2>
    <form action="../forgotpassword/reset_password.php" method="post" onsubmit="return validateForm();">
        <label for="password">New Password:</label><br>
        <div class="input-container">
            <input type="password" id="password" name="password" placeholder="Password">
            <i id="togglePasswordIcon" class="fas fa-eye toggle-password" onclick="togglePasswordVisibility()"></i>
        </div>
        <span class="error" id="passwordError"></span>
        <br><br>
        <input type="submit" value="Reset Password">
    </form>
    <script>
        
        // Show error modal if there's an error message
        const errorMessage = <?= json_encode($errorMessage); ?>;
        const successMessage = <?= json_encode($successMessage); ?>;
        if (errorMessage) {
            showErrorModal(errorMessage);
        }else if(successMessage){
            showErrorModal(successMessage,true);
        }
    </script>
</body>

</html>