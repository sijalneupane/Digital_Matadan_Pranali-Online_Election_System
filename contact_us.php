<!-- candidates.php -->
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
  <title>Candidates Page</title>
</head>
<body>
  <div class="container">
    <!-- Include sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Add 'active' class to Candidates link -->
    <script>
      document.querySelector('a[href="contact_us.php"]').classList.add('active');
    </script>

    <div class="content">
      <label for="">
      Feel free to give suggestion or state your problems Here
    </label>
    <textarea name="" id="" cols="30"></textarea>
    </div>
  </div>
</body>
</html>
