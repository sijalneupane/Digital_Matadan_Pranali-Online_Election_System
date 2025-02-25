<?php
// session_start();
$errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['error_message']); // Clear the message
$successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
unset($_SESSION['success_message']); // Clear the messages
?>
<!DOCTYPE html>
<!-- Heloo nepal-->
<html lang="en">

<head>
  <!-- Link to the CSS file and Font Awesome for icons -->
  <link rel="stylesheet" href="../styles/sidebar.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../styles/modal1.css">
  <link rel="icon" href="../images/DMP logo.png" type="image/x-icon">
</head>

<body>
  <button class="toggle-sidebar-btn" onclick="toggleSidebar()">☰ Menu</button>
  <div class="sidebar">
    <div class="logo">
      <img src="../images/DMP logo.png" alt="Logo" />
    </div>
    <!-- -->
    <div class="left-lower-box">
      <ul>
        <li><a href="../home/home.php"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="../candidates/candidates.php"><i class="fas fa-users"></i>Candidates</a></li>
        <!-- <li><a href="guidelines.php"><i class="fas fa-file-alt"></i> Election Guidelines</a></li> -->
        <li><a href="../voting/voting.php"><i class="fas fa-vote-yea"></i> Voting</a></li>
        <li><a href="../results/results.php"><i class="fas fa-chart-pie"></i> Results</a></li>
        <li><a href="../home/contact_us.php"><i class="fas fa-message"></i> Contact Us</a></li>
      </ul>
      <a href="../register_and_login/user_profile.php" class="profile-section">
        <button class="profile-btn">
          <img src=<?php echo "../uploads/" . $_SESSION['userPhoto'] ?> alt="">
          <p>
            <?php
            echo $_SESSION["name"];
            ?>
          </p>
        </button>
      </a>
      <!-- Logout Button -->
      <div class="logout-button-container">
        <button class="logout-button" id="logoutBtn" onclick="openLogoutModal();"><i class="fas fa-sign-out-alt"></i>
          Logout</button>
      </div>
      <!-- <a href="voter_logout.php"><button class="logout-btn">Log Out</button></a> -->
    </div>
  </div>
  <?php require_once '../home/logout_modals_html.php';
  logoutModalPhp("voter"); ?>
  <div id="modal1" class="modal-overlay1 all-modals">
    <div class="modal-content1">
      <p id="modalMessage1"></p>
      <button onclick="closeModal1()">Close</button>
    </div>
  </div>
  <script src="../js/errorMessage_modal1.js"></script>

  <script>
    function toggleSidebar() {
      document.querySelector('.sidebar').classList.toggle('active');
    } // Close sidebar when clicking outside the sidebar on small screens
    document.addEventListener('click', function (event) {
      const sidebar = document.querySelector('.sidebar');
      const toggleButton = document.querySelector('.toggle-sidebar-btn');
      const content = document.querySelector('.content');

      if (window.innerWidth <= 768) {  // Only apply on small screens
        if (content.contains(event.target)) {
          sidebar.classList.remove('active');
        }
      }
    });
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