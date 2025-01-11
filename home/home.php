<!-- home.php -->
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
  <title>Home Page</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    /* General styling */
    .container {
      /* background: linear-gradient(to left, rgb(45, 35, 105), rgb(85, 54, 141), rgb(143, 120, 180)); */

      display: flex;
    }

    .content {
      flex-grow: 1;
      padding: 20px;
    }

    .boxes {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .box {
      flex: 1 1 calc(33.333% - 20px);
      min-width: 150px;
      max-width: 250px;
      padding: 20px;
      border-radius: 8px;
      color: white;
      text-align: center;
      font-size: 1.2em;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      transition: transform 0.3s ease;
    }

    .box i {
      font-size: 2em;
      margin-bottom: 10px;
    }

    .box:hover {
      transform: scale(1.05);
    }

    /* .box.home {
      background-color: #4CAF50;
    } */

    .box.candidates {
      background-color: #2196F3;
    }

    .box.voting {
      background-color: #FF9800;
    }

    .box.results {
      background-color: #9C27B0;
    }

    .box.contact {
      background-color: #F44336;
    }

    .description-container {
      background:whitesmoke;
      /* background-color:#475670; */
/* background-image: linear-gradient(180deg rgb(129, 143, 165) 0%, #475670 30%,rgb(38, 49, 68) 100%);  */

      display: flex;
      color: wheat;
      align-items: start;
      justify-content: space-between;
      margin-top: 40px;
      padding: 25px;
      border-top-right-radius:50px;
      border-bottom-left-radius: 50px;
    }

    .description {
      flex: 1;
      padding-right: 20px;
    }

    .title h2 {
      margin-bottom: 20px;
    }

    .description p {
      font-size: 1.1em;
      line-height: 1.5;
    }

    .gif {
      flex: 1;
      max-width: 300px;
      display: flex;
      justify-content: center;
    }

    .gif img {
      background: transparent;
      max-width: 200px;
      height: auto;
      border-radius: 8px;
    }
  </style>
</head>

<body>
  <div class="container">
    <?php include '../home/sidebar.php'; ?>
    <script>
      document.querySelector('a[href="../home/home.php"]').classList.add('active');
    </script>

    <div class="content">
      <div class="title">
        
      <h2>Welcome to Digital Matadan Pranali</h2>
      </div>
      <div class="boxes">
        <!-- <div class="box home">
          <i class="fas fa-home"></i>
          <span>Home</span>
        </div> -->
        <div class="box candidates">
          <i class="fas fa-users"></i>
          <span>Candidates</span>
        </div>
        <div class="box voting">
          <i class="fas fa-vote-yea"></i>
          <span>Voting</span>
        </div>
        <div class="box results">
          <i class="fas fa-chart-pie"></i>
          <span>Results</span>
        </div>
        <div class="box contact">
          <i class="fas fa-message"></i>
          <span>Contact Us</span>
        </div>
      </div>

      <div class="description-container">
        <div class="description">
          <p>
            Our Online Election System streamlines the voting process with a user-friendly platform.
            From nominating candidates to casting your vote, we ensure transparency, efficiency, and security.
            Experience the future of elections with Digital Matadan Pranali.
          </p>
        </div>
        <div class="gif">
          <img src="../images/voting.gif" alt="Election GIF">
        </div>
      </div>

    </div>
  </div>
  <script>
    // Global variable to store the voting status
    let votingTime = {};

    // Function to fetch voting status from the server
    function fetchVotingTime() {
      const xhr = new XMLHttpRequest();
      xhr.open('GET', '../time/fetch_voting_time.php', true);
      xhr.onload = function () {
        if (this.status === 200) {
          votingTime = JSON.parse(this.responseText);
        }
      };
      xhr.send();
    }
  </script>
</body>

</html>