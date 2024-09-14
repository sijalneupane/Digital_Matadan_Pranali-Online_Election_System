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
    <h1>Candidates</h1>
    <div id="candidates-list">
        <h2>Candidate 1</h2>
        <p>Biography and agenda.</p>
        <h2>Candidate 2</h2>
        <p>Biography and agenda.</p>
        <!-- You can dynamically fetch candidates using PHP from a database -->
    </div>
    </div>
  </div>
</body>
</html>
