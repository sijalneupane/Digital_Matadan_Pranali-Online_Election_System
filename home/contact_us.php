<!-- candidates.php -->
<?php
session_start();
if (!isset($_SESSION["email"])) {
  header('Location: ../home/index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Candidates Page</title>

</html>
<style>
  :root{
    --li-color:rgb(101, 71, 182);
    --button-color:rgb(34, 103, 150);
  }

  .content p {
    font-size: 1.1em;
    line-height: 1.5;
    margin-bottom: 10px;
  }

  .content ul {
    list-style-type: circle;
    /* padding: 0; */
    margin-top: 5px;
    width: 100%;
    max-width: 400px;
  }

  .content ul li {
    color: --li-color;
    /* margin: 10px 0; */
    padding: 5px;
    border-radius: 5px;
    font-size: 1em;
    opacity: 0.8;
    font-weight: bold;
  }

  .contact-form {
    margin-top: 20px;
    width: 100%;
    max-width: 680px;
  }

  .contact-form label {
      display: block;
      font-size: 1.2em;
      margin-bottom: 10px;
      color:rgb(9, 35, 150);
    }
    
  .contact-form textarea {
    width: 100%;
    /* max-width: 600px; */
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 1em;
    resize: vertical;
    display: block;
    margin-bottom: 20px;
  }

  .contact-form button {
    background-color:var(--button-color);
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 1em;
    cursor: pointer;
    transition: all 0.3s;
    opacity: 0.8;
  }

  .contact-form button:hover {
    /* background-color: #45a049; */
    opacity: 1;
  }
</style>
</head>

<body>
  <didv class="container">
    <!-- Include sidebar -->
    <?php include '../home/sidebar.php'; ?>

    <!-- Add 'active' class to Candidates link -->
    <script>
      document.querySelector('a[href="../home/contact_us.php"]').classList.add('active');
    </script>
    <div class="content">
      <p>Our online election system, Digital Matadan Pranali, is designed to provide a seamless and secure voting
        experience. With our system, you can participate in elections from the comfort of your home, ensuring that your
        vote is counted accurately and efficiently.</p>
        
      <p style="margin:0">We aims to provides various features like</p>
      <ul>
        <li>Secured voting process with real time counting</li>
        <li>User-friendly interface</li>
        <li>Accessibility of support and assistance</li>
      </ul>
      <p>We are committed to providing a reliable and trustworthy platform for all your election needs. However, we
        understand that you may encounter issues or have suggestions for improvement. Please use the contact form below
        to reach out to our admin team. 
      </p>
      <div class="contact-form">
        <label for="">Your feedback is valuable to us and helps us enhance the system for everyone.</label>
        <textarea name="message" id="message" cols="30" rows="10"></textarea>
        <button type="submit">Submit</button>
      </div>
      <footer>
        <p>&copy; 2023 Your Company. All rights reserved.</p>
      </footer>
    </div>
  </didv>
</body>