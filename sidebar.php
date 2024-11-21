<?php
// session_start();
?>
<!DOCTYPE html>
<!-- Heloo nepal-->
<html lang="en">

<head>
  <!-- Link to the CSS file and Font Awesome for icons -->
  <link rel="stylesheet" href="styles/sidebar.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
  <button class="toggle-sidebar-btn" onclick="toggleSidebar()">☰ Menu</button>
  <div class="sidebar">
    <div class="logo">
      <img src="DMP logo.png" alt="Logo" />
    </div>
<!-- -->
    <div class="left-lower-box">
      <ul>
        <li><a href="home.php"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="candidates.php"><i class="fas fa-users"></i>Candidates</a></li>
        <!-- <li><a href="guidelines.php"><i class="fas fa-file-alt"></i> Election Guidelines</a></li> -->
        <li><a href="voting.php"><i class="fas fa-vote-yea"></i> Voting</a></li>
        <li><a href="results.php"><i class="fas fa-chart-pie"></i> Results</a></li>
      </ul>
        <a href="user_profile.php" class="profile-section">
          <button class="profile-btn">
          <img src="vote2.jpg" alt="">
          <p>
            <?php
            echo $_SESSION["name"];
            ?>
          </p>
          </button>
        </a>
      <!-- <a href="voter_logout.php"><button class="logout-btn">Log Out</button></a> -->
    </div>
  </div>
  <script>
    function toggleSidebar() {
      document.querySelector('.sidebar').classList.toggle('active');
    }
  </script>
</body>

</html>