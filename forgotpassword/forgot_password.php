<?php
session_start();
$errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['error_message']); // Clear the message
?>
<!DOCTYPE html>
<html>

<head>
    <title>Forgot Password</title>
    <link rel="icon" href="../images/DMP logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../forgotpassword/style1.css">
    <link rel="stylesheet" href="../styles/modal1.css">
    <script>
        function validateForm() {
            const email = document.getElementById('email').value.trim();

            const emailError = document.getElementById('emailError');

            const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

            let isValid = true;

            // Email validation
            if (email === "") {
                emailError.textContent = "Email should not be empty";
                isValid = false;
            } else if (!emailRegex.test(email)) {
                emailError.textContent = "Invalid email format";
                isValid = false;
            } else {
                emailError.textContent = "";
            }

            // Return whether the form is valid or not
            return isValid;
        }
    </script>
    
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

    <!-- Forgot Password Form -->
    <h2>Forgot Password</h2>
    <form action="../forgotpassword/send_otp.php" method="post" onsubmit="return validateForm();">
        <label for="email">Enter your email address:</label>
        <input type="text" name="email" id="email">
        <span class="error" id="emailError"></span>

        <input type="submit" value="Submit">
        <a href="../register_and_login/voter_login_form.php">Back to Login</a>
    </form>
    <script src="../js/errorMessage_modal1.js"></script>
    <script>
        const errorMessage = <?= json_encode($errorMessage); ?>;
        showErrorModal(errorMessage); // Pass PHP error to JS function
    </script>
</body>

</html>