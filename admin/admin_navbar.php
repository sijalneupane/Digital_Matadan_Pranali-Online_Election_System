<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin navbar</title>
  <link rel="stylesheet" href="../admin/admin_navbar.css">
  <!-- <script src="../js/confirm_modal.js"></script> -->
</head>

<body>
  <nav class="navbar">
  <a href="../admin/admin_home.php" class="logo">
      <img src="../images/DMP logo.png" alt="Election Logo">
      <h5>Admin</h5>
    </a>
    <div class="admin-panel">
      <span id="dropdown-title"><?php echo $_SESSION['pageName'] ?? 'Admin Panel' ?></span>
      <span class="arrow">▼</span>
      <ul class="dropdown-menu">
        <li><a href="../admin/admin_home.php">Admin Dashboard</a></li>
        <li><a href="../admin/verify_voters.php">Verify Voters</a></li>
        <li><a href="../admin/add_candidates.php">Add Candidates</a></li>
        <li><a href="../admin/manage_parties.php">Manage Parties</a></li>
        <li><a href="#">Option 4</a></li>
      </ul>
    </div>
    <div class="nav-links">
      <button class="admin-logout" id="logoutBtn" onclick="openLogoutModal();">Logout</button>
    </div>
  </nav>

</body>

</html>