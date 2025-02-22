<!-- home.php -->
<?php
session_start();
if (!isset($_SESSION["email"])) {
  header('Location: ../home/index.php');
}
require_once '../register_and_login/dbconnection.php';
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
.content{
  padding:0px !important;
}
    .upper-part {
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

      transition: transform 0.3s ease;
      border-radius: 8px;
    }

    .box a {
      text-decoration: none;
      color: white;
      padding: 20px;
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
      background: #bdbaba;
      /* background-color: #475670; */
      display: flex;
      color: #123c44;
      align-items: start;
      justify-content: space-between;
      margin-top: 40px;
      padding: 25px;
      border-top-right-radius: 50px;
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
    /* previous election selection */

/* .winner-container {
    text-align: center;
    margin-top: 20px;
    padding: 20px;
    background: rgba(255, 255, 255, 0.1); 
    backdrop-filter: blur(10px);
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    max-width: 500px; 
    margin-left: auto;
    margin-right: auto;
}


.winner-container h3 {
    font-size: 22px;
    font-weight: bold;
    color: #fff;
    margin-bottom: 15px;
}
*/

.winner-card {
    display: flex;
     
    align-items: center;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    padding:10px 45px;
    gap:20px;
    margin-bottom: 15px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}
/*
.winner-card img {
    width: 100px; 
    height: 100px;
    border-radius: 50%;
    border: 3px solid white;
    object-fit: cover;
    margin-bottom: 10px;
}

.winner-info {
    text-align: center;
}

.winner-info h4 {
    font-size: 18px;
    font-weight: bold;
    margin: 5px 0;
    color: #fff;
}

.winner-info p {
    font-size: 14px;
    color: #ddd;
    margin: 2px 0;
}


.no-winner {
    text-align: center;
    font-size: 16px;
    color: red;
    margin-top: 10px;
} */

  </style>
</head>

<body>
  <div class="container">
    <?php include '../home/sidebar.php'; ?>
    <script>
      document.querySelector('a[href="../home/home.php"]').classList.add('active');
    </script>

    <div class="content">
      <div class="upper-part">
      <div class="title">

<h2>Welcome to Digital Matadan Pranali</h2>
</div>
<div class="boxes">
<!-- <div class="box home">
  <i class="fas fa-home"></i>
  <span>Home</span>
</div> -->
<div class="box candidates">
  <a href="../candidates/candidates.php">
    <i class="fas fa-users"></i>
    <span>Candidates</span>
  </a>
</div>
<div class="box voting">
  <a href="../voting/voting.php">
    <i class="fas fa-vote-yea"></i>
    <span>Voting</span>
  </a>
</div>
<div class="box results">
  <a href="../results/results.php">
    <i class="fas fa-chart-pie"></i>
    <span>Results</span>
  </a>
</div>
<div class="box contact">
  <a href="../home/contact_us.php">
    <i class="fas fa-message"></i>
    <span>Contact Us</span>
  </a>
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
<div>
<?php
$dId = $_SESSION['dId']; // Get the user's district ID from session
// Fetch the most recent election winner
$query = "SELECT ar.electionId, ar.electionName, ar.candidateName, ar.partyName, ar.candidatePhoto, ar.totalVotes
          FROM archiveresults ar
          WHERE ar.dId = 19 
          AND ar.totalVotes = (
              SELECT MAX(totalVotes) 
              FROM archiveresults 
              WHERE dId = ar.dId AND electionId = ar.electionId
          )
          GROUP BY ar.electionId";
          $result = mysqli_query($conn, $query);

          if (mysqli_num_rows($result) > 0) {
            ?>
                <div class="winner-container">
                    <h3>Previous Election Winners</h3>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <div class="winner-card">
                            <img src="../uploads/<?php echo htmlspecialchars($row['candidatePhoto']); ?>" alt="Winner Photo">
                            <div class="winner-info">
                                <h4><?php echo htmlspecialchars($row['candidateName']); ?></h4>
                                <p>Party: <?php echo htmlspecialchars($row['partyName'] ?: 'Independent'); ?></p>
                                <p>Election: <?php echo htmlspecialchars($row['electionName']); ?></p>
                                <p>Total Votes: <?php echo number_format($row['totalVotes']); ?></p>
                            </div>
                        </div>
                        <hr>
                    <?php } ?>
                </div>
            <?php 
            } else {
                echo "<p class='no-winner'>No previous election winners found for your district.</p>";
            }
            ?>
</div>
      </div>
      <?php include '../home/footer.php'; ?>
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