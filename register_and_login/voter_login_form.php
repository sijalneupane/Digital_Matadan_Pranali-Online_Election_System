<?php
session_start();
$errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['error_message']); // Clear the message
$successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
unset($_SESSION['success_message']); // Clear the message
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/modal1.css">
    <title>Glassmorphism Login</title>

    <script>
        function validateForm() {
            const email = document.getElementById('email').value.trim();
            const citizenshipNumber = document.getElementById('citizenshipNumber').value.trim();
            const password = document.getElementById('password').value.trim();

            const emailError = document.getElementById('emailError');
            const citizenshipError = document.getElementById('citizenshipError');
            const passwordError = document.getElementById('passwordError');

            const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            let passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/;

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



            if (citizenshipNumber === "") {
                citizenshipError.textContent = "Voter Id number should not be empty";
                isValid = false;
            } else {
                citizenshipError.textContent = "";
            }

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
    <script src="../js/togglepassword.js"></script>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('https://i.pinimg.com/originals/e9/03/07/e90307be4c8d3ec19ddf805ab7a59507.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .glass-container {
            margin: 0px 4%;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.5);
            box-shadow: 0px 0px 30px 7px rgba(0, 0, 0, 0.5);
            border-radius: 6px;
            width: 37%;
            min-width: 380px;
            text-align: center;
        }

        .left-box {
            padding: 40px;
            width: 100%;
        }

        .glass-container img {
            width: 150px;
            margin-bottom: 20px;
            filter: brightness(0.5);
        }

        .input-container {
            position: relative;
            width: 100%;
            margin-bottom: 5px;
        }

        input {
            border: 1px solid burlywood;
            border-radius: 5px;
            padding: 10px;
            color: rgb(0, 0, 0);
            width: 100%;
            font-size: 1.2em;
        }

        input:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }

        input:not(input[type="submit"])::placeholder {
            color: rgb(65, 63, 63);
            font-size: 0.8em;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        input[type="submit"] {
            background: #6e8efb;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background: #a777e3;
        }

        .register-direction {
            font-size: 1.1em;
            margin: 8px 0px;
            text-align: center;
        }

        .register-direction a {
            text-decoration: none;
            margin-left: 1%;
        }

        .register a:hover {
            text-decoration: underline;
            color: rebeccapurple;
        }

        .error {
            color: red;
            font-size: 12px;
            display: block;
            height: 20px;
            text-align: start;
        }

        @media (max-width: 768px) {
            .glass-container {
                min-width: 320px;
            }

            input[type="text"],
            input[type="number"],
            input[type="password"] {
                font-size: 0.9em;
            }
        }
    </style>
</head>

<body>
    <div id="modal1" class="modal-overlay1 all-modals">
        <div class="modal-content1">
            <p id="modalMessage1"></p>
            <button onclick="closeModal1()">Close</button>
        </div>
    </div>
    <div class="glass-container">
        <div class="left-box">
            <a href="../home/index.php"><img src="../images/DMP logo.png" alt="Logo"></a>
            <h2>Voter Login ! ! !</h2>
            <form id="loginForm" method="POST" action="voter_login.php" onsubmit="return validateForm()">
                <input type="text" id="email" name="email" placeholder="Email">
                <span id="emailError" class="error"></span>

                <!-- <input type="text" id="voterId" name="voterId" placeholder="Voter Id Number">
                <span id="voterIdError" class="error"></span> -->
                <input type="text" id="citizenshipNumber" name="citizenshipNumber" placeholder="CitizenshipNumber">
                <span id="citizenshipError" class="error"></span>

                <div class="input-container">
                    <input type="password" id="password" name="password" placeholder="Password">
                    <i id="togglePasswordIcon" class="fas fa-eye toggle-password" onclick="togglePasswordVisibility()"></i>
                </div>
                <span id="passwordError" class="error"></span>

                <a href="../forgotpassword/forgot_password.php" style="display:block;text-align: start;margin: 8px 0px;">Forgot Password?</a>
                <input type="submit" value="Login">
                <div class="register-direction">
                    <span>Haven't Registered till now? </span><a href="../register_and_login/voter_register_form.php">Register Here</a>
                </div>
            </form>
        </div>
    </div>
    <script src="../js/errorMessage_modal1.js"></script>
    <script>
        // Show error modal if there's an error message
        const errorMessage = <?= json_encode($errorMessage); ?>;
        const successMessage = <?= json_encode($successMessage); ?>;
        if (errorMessage) {
            showErrorModal(errorMessage);
        }else if(successMessage){
            showErrorModal(successMessage,true);
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