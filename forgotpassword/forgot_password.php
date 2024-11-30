<!DOCTYPE html>
<html>

<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style1.css">

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
    <!-- Logo Section -->
    <div class="logo-container">
        <img src="../images/DMP logo.png" alt="Logo">
    </div>

    <!-- Forgot Password Form -->
    <h2>Forgot Password</h2>
    <form action="send_otp.php" method="post" onsubmit="return validateForm();">
        <label for="email">Enter your email address:</label>
        <input type="text" name="email" id="email">
        <span class="error" id="emailError"></span>
        <br><br>
        <input type="submit" value="Submit">
    </form>
</body>

</html>

</html>