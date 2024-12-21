<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../forgotpassword/forgot_password.php");
  exit();
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Enter OTP</title>
  <link rel="stylesheet" href="../forgotpassword/style1.css">
  <script>

    function validateForm() {
      const otp = document.getElementById('otp').value.trim();

      const otpError = document.getElementById('otpError');

      let isValid = true;

      // Email validation
      if (otp === "") {
        otpError.textContent = "OTP should not be empty";
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

  <h2>Enter OTP</h2>
  <form action="../forgotpassword/verify_otp.php" method="post" onsubmit="return validateForm();">
    <label for="otp">Enter the OTP sent to <?php echo htmlspecialchars($_SESSION['email']); ?>:</label><br>
    <input type="number" name="otp" id="otp">
    <span class="error" id="otpError"></span>
    <br><br>
    <input type="submit" value="Verify">
  </form>
</body>

</html>