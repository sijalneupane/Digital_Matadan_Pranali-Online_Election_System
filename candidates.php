<!-- candidates.php -->
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
      document.querySelector('a[href="candidates.php"]').classList.add('active');
    </script>

    <div class="content">
      <h1>Candidates Information</h1>
      <p>Here you will find information about the candidates running for office.</p>
    </div>
  </div>
</body>
</html>
