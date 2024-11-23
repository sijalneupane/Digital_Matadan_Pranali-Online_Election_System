<?php
session_start();
if (!isset($_SESSION["email"])) {
  header('Location: index.html');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

</head>

<body>
  <div class="container">
    <?php include 'sidebar.php'; ?>

    <div class="content">
      <!-- <h2>User Profile</h2> -->
      <div class="profile-container">
        <div class="profile-card">
          <h3><i class="fas fa-user-circle"></i> <?php echo $_SESSION['name']; ?></h3>
          <p><i class="fas fa-envelope"></i> <strong>Email:</strong> <?php echo $_SESSION['email']; ?></p>
          <p><i class="fas fa-id-card"></i> <strong>Voter ID:</strong> <?php echo $_SESSION['voterId']; ?></p>
          <p><i class="fas fa-map-marker-alt"></i> <strong>District:</strong> <?php echo $_SESSION['district']; ?></p>
          <p><i class="fas fa-map-signs"></i> <strong>Election Region:</strong> <?php echo $_SESSION['election_region']; ?></p>
          <p><i class="fas fa-home"></i> <strong>Local Address:</strong> <?php echo $_SESSION['local_address']; ?></p>
          <p><i class="fas fa-phone"></i> <strong>Contact Number:</strong> <?php echo $_SESSION['contactNo']; ?></p>
          <p><i class="fas fa-birthday-cake"></i> <strong>Birth Date:</strong> <?php echo $_SESSION['birthDate']; ?></p>
          <p><i class="fas fa-passport"></i> <strong>Citizenship Number:</strong> <?php echo $_SESSION['citizenshipNumber']; ?></p>
          <a href="voter_logout.php" class="logout-button"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
      </div>
    </div>
  </div>
</body>

</html>

<style>
  /* General Styles */
  body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
  }
  /* Profile Container */
  .profile-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
  }

  .profile-card {
    width: 50%;
    padding: 30px;
    background: linear-gradient(135deg, #6e3bcb, #a066ff);
    border-radius: 15px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    text-align: left;
    color: white;
  }

  .profile-card h3 {
    font-size: 24px;
    margin-bottom: 20px;
    text-align: center;
  }

  .profile-card p {
    font-size: 16px;
    line-height: 1.8;
    margin: 10px 0;
  }

  .profile-card i {
    margin-right: 8px;
    color: #ffd700;
  }

  /* Logout Button */
  .logout-button {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    color: #fff;
    background-color: #e63946;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
    transition: background-color 0.3s ease;
  }

  .logout-button:hover {
    background-color: #d62828;
  }
</style>