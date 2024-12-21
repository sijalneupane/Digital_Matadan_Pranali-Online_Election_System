<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin navbar</title>
  <link rel="stylesheet" href="../admin/admin_navbar.css">
  <link rel="stylesheet" href="../styles/confirm_modal.css">
  <script src="../js/confirm_modal.js"></script>
</head>

<body>
  <nav class="navbar">
    <div class="logo">
      <img src="../images/DMP logo.png" alt="Election Logo">
      <h5>Admin</h5>
    </div>
    <div class="admin-panel">
      <span id="dropdown-title"><?php echo $_SESSION['pageName'] ?? 'Admin Panel' ?></span>
      <span class="arrow">▼</span>
      <ul class="dropdown-menu">
        <li><a href="../admin/admin_home.php">Admin Dashboard</a></li>
        <li><a href="../admin/verify_voters.php">Verify Voters</a></li>
        <li><a href="../admin/add_candidates.php">Add Candidates</a></li>
        <li><a href="#">Option 4</a></li>
      </ul>
    </div>
    <div class="nav-links">
      <button class="admin-logout" id="logoutBtn" onclick="openConfirmModal();">Logout</button>
    </div>
  </nav>
  <!-- Confirm Modal -->
  <!-- Modal -->
  <?php
  function confirmModalPhp()
  { ?>
    <div id="confirmModal">
      <div>
        <p>Confirm Logout?</p>
        <button onclick="confirmLogout()">Yes</button>
        <button onclick="closeConfirmModal()">No</button>
      </div>
    </div>
  <?php } ?>

  <script>
    // Handle confirmation and redirect
    function confirmLogout() {
      var uniqueKey = 'key_' + Math.random().toString(36).substring(2, 12) + '_' + new Date().getMilliseconds();
      window.location.href = '../admin/admin_logout.php?logout_key=' + uniqueKey;
    }
  </script>
</body>

</html>