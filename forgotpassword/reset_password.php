<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: forgot_password.php");
    exit();
}
require '../dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['password'];
    $email = $_SESSION['email'];

    // Directly run the update query
    $sql = "UPDATE voters SET password = '$new_password' WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "Password has been reset successfully.";
        unset($_SESSION['email']); // Clear session
        header("Location: ../voter_login.html");
        exit();
    } else {
        echo "Error updating password: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>

    <link rel="stylesheet" href="style1.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="../togglepassword.js"></script>

    <script>
        function validateForm() {
            const password = document.getElementById('password').value.trim();
            const passwordError = document.getElementById('passwordError');
            const passwordRegex = /^.{8,}$/;

            let isValid = true;

            // Password validation
            if (password === "") {
                passwordError.textContent = "Password should not be empty";
                isValid = false;
            } else if (!passwordRegex.test(password)) {
                passwordError.textContent = "Password must be at least 8 characters";
                isValid = false;
            } else {
                passwordError.textContent = "";
            }

            // Return whether the form is valid or not
            return isValid;
        }
    </script>
    <style>.input-container {
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
        }</style>
</head>

<body>
    <!-- Logo Section -->
    <div class="logo-container">
        <img src="../DMP logo.png" alt="Logo">
    </div>

    <h2>Reset Password</h2>
    <form action="reset_password.php" method="post" onsubmit="return validateForm();">
        <label for="password">New Password:</label><br>
        <div class="input-container">
                    <input type="password" id="password" name="password" placeholder="Password">
                    <i id="togglePasswordIcon" class="fas fa-eye toggle-password" onclick="togglePasswordVisibility()"></i>
                </div>
        <span class="error" id="passwordError"></span>
        <br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>

</html>