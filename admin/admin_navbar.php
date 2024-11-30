
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin navbar</title>
  <link rel="stylesheet" href="admin_navbar.css">
</head>
<body>
<nav class="navbar">
  <div class="logo">
    <img src="../images/DMP logo.png" alt="Election Logo">
  </div>
  <div class="admin-panel">
    Admin Panel <span class="arrow">▼</span>
    <ul class="dropdown-menu">
      <li><a href="verify_voters.php">verify_voters</a></li>
      <li><a href="add_candidates.php">Add Candidates</a></li>
      <li><a href="#">Option 3</a></li>
      <li><a href="#">Option 4</a></li>
    </ul>
  </div>
  <div class="page-title"><?php echo $_SESSION['pageName'] ?? '' ?></div>
  <div class="nav-links">
    <a href="#" class="admin-logout">Logout</a>
  </div>
</nav>

</body>
</html>