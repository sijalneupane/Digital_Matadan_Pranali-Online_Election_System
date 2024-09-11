<!-- guidelines.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Voting area</title>
</head>
<body>
  <div class="container">
    <?php include 'sidebar.php'; ?>

    <script>
      document.querySelector('a[href="voting.php"]').classList.add('active');
    </script>

    <div class="content">
      <h1>Voting section</h1>
      <p>We can vote here as this is the voting page</p>
    </div>
  </div>
</body>
</html>
