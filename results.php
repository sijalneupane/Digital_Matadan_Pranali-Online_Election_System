<!-- guidelines.php -->
<?php
session_start();
if (!isset($_SESSION["email"])) {
  header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Results</title>
</head>
<body>
  <div class="container">
    <?php include 'sidebar.php'; ?>

    <script>
      document.querySelector('a[href="results.php"]').classList.add('active');
    </script>

    <div class="content">
    <h1>Election Results</h1>
    <div id="results">
        <h2>Live Results</h2>
        <p>Candidate 1: 55%</p>
        <p>Candidate 2: 45%</p>
        <!-- Dynamic content: Fetch live results using PHP and database -->
    </div>
    </div>
  </div>
</body>
</html>
