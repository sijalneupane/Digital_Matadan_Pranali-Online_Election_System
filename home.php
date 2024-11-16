<!-- home.php -->
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
  <title>Home Page</title>
</head>
<body>
  <div class="container">
    <?php include 'sidebar.php'; ?>
    <script>
      document.querySelector('a[href="home.php"]').classList.add('active');
    </script>

    <div class="content">
   
    <h1>Welcome to the Election System</h1>
    <p>Stay updated with the current election phases, important dates, and announcements.</p>
    <h2>Important Dates</h2>
    <ul>
        <li>Registration Deadline: October 1</li>
        <li>Voting Period: October 10 - October 20</li>
        <li>Results Announcement: October 25</li>
    </ul>
    <p>
      Lorem ipsum, dolor sit amet consectetur adipisicing elit. Temporibus illo amet consequatur ratione autem explicabo eius dolores iusto dolorem recusandae, rem sapiente et numquam vero, qui cupiditate! Autem, non explicabo.
    </p>
    <p>
      Lorem ipsum, dolor sit amet consectetur adipisicing elit. Temporibus illo amet consequatur ratione autem explicabo eius dolores iusto dolorem recusandae, rem sapiente et numquam vero, qui cupiditate! Autem, non explicabo.
    </p>
    <p>
      Lorem ipsum, dolor sit amet consectetur adipisicing elit. Temporibus illo amet consequatur ratione autem explicabo eius dolores iusto dolorem recusandae, rem sapiente et numquam vero, qui cupiditate! Autem, non explicabo.
    </p>
    <p>
      Lorem ipsum, dolor sit amet consectetur adipisicing elit. Temporibus illo amet consequatur ratione autem explicabo eius dolores iusto dolorem recusandae, rem sapiente et numquam vero, qui cupiditate! Autem, non explicabo.
    </p>
    <p>
      Lorem ipsum, dolor sit amet consectetur adipisicing elit. Temporibus illo amet consequatur ratione autem explicabo eius dolores iusto dolorem recusandae, rem sapiente et numquam vero, qui cupiditate! Autem, non explicabo.
    </p>
    <p>
      Lorem ipsum, dolor sit amet consectetur adipisicing elit. Temporibus illo amet consequatur ratione autem explicabo eius dolores iusto dolorem recusandae, rem sapiente et numquam vero, qui cupiditate! Autem, non explicabo.
    </p>
    <p>
      Lorem ipsum, dolor sit amet consectetur adipisicing elit. Temporibus illo amet consequatur ratione autem explicabo eius dolores iusto dolorem recusandae, rem sapiente et numquam vero, qui cupiditate! Autem, non explicabo.
    </p>
    <p>
      Lorem ipsum, dolor sit amet consectetur adipisicing elit. Temporibus illo amet consequatur ratione autem explicabo eius dolores iusto dolorem recusandae, rem sapiente et numquam vero, qui cupiditate! Autem, non explicabo.
    </p>
    <p>
      Lorem ipsum, dolor sit amet consectetur adipisicing elit. Temporibus illo amet consequatur ratione autem explicabo eius dolores iusto dolorem recusandae, rem sapiente et numquam vero, qui cupiditate! Autem, non explicabo.
    </p>
    
    </div>
  </div>
</body>
</html>
