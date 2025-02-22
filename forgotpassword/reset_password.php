<?php
session_start();
$errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['error_message']); // Clear the message
$id = isset($_GET['id']) ? $_GET['id'] : null;

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
    <div id="modal1" class="modal-overlay1 all-modals">
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
    <form action="../forgotpassword/reset_password_controller.php?id=<?= $id ?>" method="post" onsubmit="return validateForm();">
        <label for="password">New Password:</label><br>
        <div class="input-container">
            <input type="password" id="password" name="password" placeholder="Password">
            <i id="togglePasswordIcon" class="fas fa-eye toggle-password" onclick="togglePasswordVisibility()"></i>
        </div>
        <span class="error" id="passwordError"></span>
        <br><br>
        <input type="submit" value="Reset Password">
        <?php if ($id!=null): ?>
            <a href="../register_and_login/user_profile.php">Back to Profile</a>
        <?php endif; ?>
    </form>
    <script>

        // Show error modal if there's an error message
        const errorMessage = <?= json_encode($errorMessage); ?>;
        const successMessage = <?= json_encode($successMessage); ?>;
        if (errorMessage) {
            showErrorModal(errorMessage);
        } else if (successMessage) {
            showErrorModal(successMessage, true);
        }

        // Close the modal when clicking outside of the modal content
        window.onclick = function (event) {
        var modals = document.getElementsByClassName('all-modals');
        for (var i = 0; i < modals.length; i++) {
            if (event.target == modals[i]) {
                modals[i].style.display = 'none';
            }
        }
    }

    </script>
</body>

</html>