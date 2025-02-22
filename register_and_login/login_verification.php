<?php
session_start();
if (!isset($_SESSION['email'])) {
  header('Location: voter_login_form.php');
  exit();
}
$errorMessage = isset($_SESSION['idErrorMsg']) ? $_SESSION['idErrorMsg'] : '';
unset($_SESSION['idErrorMsg']); // Clear the message
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../styles/modal1.css">
  <title>Enter Voter ID</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background: url('https://i.pinimg.com/originals/e9/03/07/e90307be4c8d3ec19ddf805ab7a59507.jpg') no-repeat center center/cover;
      backdrop-filter: blur(5px);
    }

    .container {
      background: rgba(255, 255, 255, 0.2);
      padding: 50px 30px;
      border-radius: 15px;
      box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
      text-align: center;
      width: 380px;
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.18);
    }

    .logo {
      width: 120px;
      filter: brightness(0.5);
      margin-bottom: 10px;
    }

    h2 {
      margin-bottom: 20px;
      color: black;
      font-size: 28px;
    }

    input[type="number"] {
      width: 100%;
      margin-bottom: 5px;
      padding: 12px;
      border: none;
      border-radius: 10px;
      outline: none;
      font-size: 16px;
      backdrop-filter: blur(10px);
    }

    .button {
      width: 100%;
      padding: 12px;
      background-color: rgba(51, 40, 101, 0.9);
      ;
      color: white;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-size: 16px;
    }

    .button:hover {
      background-color: rgba(51, 40, 101, 1);
    }

    .error {
      color: #b79191;
      font-size: 12px;
      display: block;
      height: 20px;
      text-align: start;
    }
  </style>
  <script>
    function validateForm() {
      const voterId = document.getElementById('voterId').value.trim();
      const voterIdError = document.getElementById('voterIdError');
      // voterId validation
      var isValid = true;
      if (voterId === "") {
        voterIdError.textContent = "Voter Id number should not be empty";
        isValid = false;
      } else {
        voterIdError.textContent = "";
      }
      // Return whether the form is valid or not
      return isValid;
    }
  </script>
</head>

<body>
  <div id="modal1" class="modal-overlay1 all-modals">
    <div class="modal-content1">
      <p id="modalMessage1"></p>
      <button onclick="closeModal1()">Close</button>
    </div>
  </div>
  <div class="container">
    <img src="../images/DMP logo.png" alt="Logo" class="logo">
    <h2>Enter Voter ID to Proceed</h2>
    <form action="process_voter.php" method="POST" onsubmit="return validateForm();">
      <input type="hidden" name="email" value="<?= $_SESSION['email'] ?>">
      <input type="number" name="voterId" id="voterId" placeholder="Enter Voter ID" min="1">
      <span id="voterIdError" class="error"></span><br>
      <input type="submit" value="Proceed" class="button">
    </form>
  </div>
  <script src="../js/errorMessage_modal1.js"></script>
  <script>
    const errorMessage = <?= json_encode($errorMessage); ?>;
    showErrorModal(errorMessage); // Pass PHP error to JS function
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