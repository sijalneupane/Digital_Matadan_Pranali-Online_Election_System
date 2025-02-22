<!-- candidates.php -->
<?php
session_start();
if (!isset($_SESSION["email"])) {
  header('Location: ../home/index.php');
}
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
  <title>Candidates Page</title>
  <link rel="stylesheet" href="../styles/modal1.css">
  <style>
    :root {
      --li-color: rgb(101, 71, 182);
      --button-color: rgb(34, 103, 150);
    }
.content{
  padding:0px !important;
}
.upper-part{
  padding:15px;
}
    .upper-part p {
      font-size: 1.1em;
      line-height: 1.5;
      margin-bottom: 10px;
    }

    .upper-part ul {
      list-style-type: circle;
      /* padding: 0; */
      margin-top: 5px;
      width: 100%;
      max-width: 400px;
    }

    .upper-part ul li {
      color: --li-color;
      /* margin: 10px 0; */
      padding: 5px;
      border-radius: 5px;
      font-size: 1em;
      opacity: 0.8;
      font-weight: bold;
    }

    .contact-form {
      margin-top: 20px;
      width: 100%;
      max-width: 680px;
    }

    .contact-form label {
      display: block;
      font-size: 1.2em;
      margin-bottom: 10px;
      color: rgb(9, 35, 150);
    }

    .contact-form textarea {
      width: 100%;
      /* max-width: 600px; */
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 1em;
      resize: vertical;
      display: block;
      margin-bottom: 20px;
    }

    .contact-form button {
      background-color: var(--button-color);
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      font-size: 1em;
      cursor: pointer;
      transition: all 0.3s;
      opacity: 0.8;
    }

    .contact-form button:hover {
      /* background-color: #45a049; */
      opacity: 1;
    }
  </style>
  <script src="../js/errorMessage_modal1.js"></script>
</head>

<body>
  <div class="container">
    <div id="modal1" class="modal-overlay1 all-modals">
      <div class="modal-content1">
        <p id="modalMessage1"></p>
        <button onclick="closeModal1()">Close</button>
      </div>
    </div>
    <!-- Include sidebar -->
    <?php include '../home/sidebar.php'; ?>

    <!-- Add 'active' class to Candidates link -->
    <script>
      document.querySelector('a[href="../home/contact_us.php"]').classList.add('active');
    </script>
    <div class="content">
      <div class="upper-part">
      <p>Our online election system, Digital Matadan Pranali, is designed to provide a seamless and secure voting
        experience. With our system, you can participate in elections from the comfort of your home, ensuring that your
        vote is counted accurately and efficiently.</p>

      <p style="margin:0">We aims to provides various features like</p>
      <ul>
        <li>Secured voting process with real time counting</li>
        <li>User-friendly interface</li>
        <li>Accessibility of support and assistance</li>
      </ul>
      <p>We are committed to providing a reliable and trustworthy platform for all your election needs. However, we
        understand that you may encounter issues or have suggestions for improvement. Please use the contact form below
        to reach out to our admin team.
      </p>
      <form class="contact-form" method="post" onsubmit="return validateContactForm()"
        action="../home/contact_us_controller.php">
        <label for="message">Your feedback is valuable to us and helps us enhance the system for everyone.</label>
        <textarea name="message" id="message" cols="30" rows="10"></textarea>
        <button type="submit">Submit</button>
      </form>
      </div>
      <?php include '../home/footer.php'; ?>
    </div>
  </div>
  <script>
    function validateContactForm() {
      // Validate the contact form
      const message = document.getElementById('message').value;
      if (!message) {
        showErrorModal('Please enter your message, Dont Submit Empty message');
        return false;
      }
      return true;
    }


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