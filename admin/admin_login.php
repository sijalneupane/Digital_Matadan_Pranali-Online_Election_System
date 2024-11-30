<?php
session_start();
$errorMessage = isset($_SESSION['errorMessage']) ? $_SESSION['errorMessage'] : '';
unset($_SESSION['errorMessage']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../register_and_login/modal.css">
  <style>
    /* Reset Styles */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    /* Page styles */
    body {
      font-family: Arial, sans-serif;
      background-image: url('https://i.pinimg.com/originals/e9/03/07/e90307be4c8d3ec19ddf805ab7a59507.jpg');
      /* Replace with your image URL */
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      color: #333;
    }

    .container {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      text-align: center;
      padding: 20px;
      gap: 4%;
    }

    .logo-container {
      position: absolute;
      top: 20px;
      left: 20px;
      display: flex;
      align-items: center;

    }

    .logo-container img {
      height: 50px;
      margin-right: 10px;
    }

    .logo-text {
      font-size: 24px;
      font-weight: bold;
      color: red;
      text-align: center;

    }

    .login-box {
      background: linear-gradient(135deg, #d16d6d, #486e85);
      background-color: rgba(0, 0, 0, 0.3);
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
      max-width: 400px;
      width: 100%;
    }

    .greeting {
      font-size: 20px;
      font-weight: bold;
      margin-bottom: 20px;
      color: #333;
    }

    .form-group {
      margin-bottom: 20px;
      text-align: left;
      position: relative;
    }
    .input-container {
  position: relative;
  /* width: 100%; */
  /* margin-bottom: 5px; */
}
.toggle-password {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
}
    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-size: 14px;
      font-weight: bold;
      color: #333;
    }

    .form-group input {
      width: 100%;
      padding: 12px 15px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 6px;
      transition: all 0.3s ease-in-out;
    }

    .form-group input:focus {
      border-color: #007BFF;
      box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
      outline: none;
    }

    .error-message {
      color: red;
      font-size: 12px;
      margin-top: 5px;
      position: absolute;
    }

    .btn {
      width: 100%;
      padding: 12px;
      background-color: #007BFF;
      color: #fff;
      font-size: 16px;
      font-weight: bold;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: all 0.3s ease-in-out;
    }

    .btn:hover {
      background-color: #0056b3;
      box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
    }
  </style>
  <script>
    function validateForm() {
      let isValid = true;

      // Get field values
      const username = document.getElementById("username").value.trim();
      const password = document.getElementById("password").value.trim();

      // Get error message spans
      const usernameError = document.getElementById("usernameError");
      const passwordError = document.getElementById("passwordError");

      // Clear previous error messages
      usernameError.textContent = "";
      passwordError.textContent = "";

      // Validate username
      if (username === "") {
        usernameError.textContent = "Username is required.";
        isValid = false;
      }

      // Validate password
      if (password === "") {
        passwordError.textContent = "Password is required.";
        isValid = false;
      } else if (password.length < 8) {
        passwordError.textContent = "Password should be atleast 8 characters";
        isValid = false;
      }

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
  <div class="logo-container">
    <img src="../images/DMP logo.png" alt="Logo">
  </div>
  <div class="container">
    <div class="logo-text">Admin Panel</div>
    <div class="login-box">
      <div class="greeting">Welcome, Admin! Please log in to continue.</div>
      <form method="POST" action="admin_login_controller.php" onsubmit="return validateForm();">

        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text" id="username" name="username"><br>
          <span id="usernameError" class="error-message"></span>
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <div class="input-container">
          <input type="password" id="password" name="password"><i id="togglePasswordIcon" class="fas fa-eye toggle-password"
          onclick="togglePasswordVisibility()"></i>
          </div>
          <span id="passwordError" class="error-message"></span><br>
        </div>
        <input type="submit" class="btn" value="Login">
      </form>
    </div>
  </div>


</body>
<script>
  // PHP Message passed to JavaScript
  const errorMessage = <?= json_encode($errorMessage); ?>;

  // Show modal if there is a message
  if (errorMessage) {
    const modal = document.getElementById('modal1');
    const modalMessage = document.getElementById('modalMessage1');
    modalMessage.textContent = errorMessage;
    modal.style.display = 'flex';
  }

  // Function to close the modal
  function closeModal1() {
    document.getElementById('modal1').style.display = 'none';
  }
</script>
<script src="../togglepassword.js"></script>
</html>