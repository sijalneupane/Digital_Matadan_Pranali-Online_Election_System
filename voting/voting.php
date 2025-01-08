<!-- guidelines.php -->
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
  <title>Voting area</title>
  <style>
    /* .not-scheduled {
      background: #fbe9e7; */
    /* Light coral background */
    /* border: 1px solid #ffccbc; */
    /* Coral border */
    /* border-radius: 10px; */
    /* padding: 20px;
      margin-top: 20px;
      box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    } */

    .not-scheduled h3 {
      color: #d32f2f;
      /* Dark red for heading */
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .not-scheduled p {
      color: #333;
      /* Dark gray for text */
      font-size: 16px;
      margin-bottom: 15px;
      line-height: 1.5;
    }

    .not-scheduled ul.election-process {
      padding-left: 25px;
      /* Indent for the list */
      margin-bottom: 15px;
    }

    .not-scheduled ul.election-process li {
      margin-bottom: 10px;
      font-size: 16px;
      list-style-type: decimal;
      color: #444;
      /* Medium gray for list items */
    }

    .not-scheduled ul.election-process li strong {
      color: #d32f2f;
      /* Highlight strong text in dark red */
    }

    .not-scheduled h4 {
      color: #1976d2;
      /* Blue for subheadings */
      font-size: 20px;
      margin-top: 30px;
      margin-bottom: 10px;
    }

    .not-scheduled ul.admin-responsibilities li {
      margin-bottom: 10px;
      font-size: 16px;
      color: #444;
      /* Medium gray for list items */
    }

    .not-scheduled ul.admin-responsibilities li strong {
      color: #1976d2;
    }
  </style>
</head>

<body>
  <div class="container">
    <?php include '../home/sidebar.php'; ?>

    <script>
      document.querySelector('a[href="../voting/voting.php"]').classList.add('active');
    </script>

    <div class="content" id="content">

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
          checkVotingTime();
        }
      };
      xhr.send();
    }

    window.onload = function () {
      fetchVotingTime();
    };

    // Automatically fetch the voting status every 1 seconds
    setInterval(fetchVotingTime, 1000); // Fetch every 1 seconds (1000 milliseconds)

    let contentDiv = document.getElementById("content");
    let electionNotScheduled = false;
    let votingNotStarted = false;
    let votingStarted = false;
    let votingEnded = false;

    function checkVotingTime() {
      // let votingStartTime=votingT
      let currentTime = new Date().getTime();
      let votingStartTime = new Date(votingTime.startTime).getTime();
      let votingEndTime = new Date(votingTime.endTime).getTime();
      // console.log(votingStartTime + " + "+ votingEndTime);
      if (!electionNotScheduled && votingTime.error) {
        electionNotScheduled = true;
        votingNotStarted = votingStarted = votingEnded = false;
        showElectionNotScheduled();

      } else if (!votingNotStarted && currentTime < votingStartTime) {
        votingNotStarted = true;
        electionNotScheduled = votingStarted = votingEnded = false
        // contentDiv.innerHTML = "<h3>Election is not started</h3>";
        showVotingNotStarted();

      } else if (!votingStarted && (currentTime > votingStartTime && currentTime < votingEndTime)) {
        votingStarted = true;
        electionNotScheduled = votingNotStarted = votingEnded = false;
        showElectionStarted();
      }
      else if (!votingEnded && currentTime > votingEndTime) {
        votingEnded = true;
        electionNotScheduled = votingNotStarted = votingStarted = false;
        showVotingEnded();
      }
    }

    // Function to display "Election Not Scheduled" UI
    function showElectionNotScheduled() {
      contentDiv.innerHTML = `
        <div class="not-scheduled">
          <h3>Election Not Scheduled</h3>
          <p>Currently there is no election scheduled.As you have logged in to our system before any election is scheduled, you can look upto some features of our voting system below:</p>
          <ul class="election-process">
            <li><strong>View Candidates:</strong> Explore the home page and all other pages</li>
            <li><strong>Vote:</strong> Cast your vote during the voting period to the candidate of your choice.</li>
            <li><strong>Results:</strong> After voting ends,you can view the results in results page.</li>
            <li><strong>Profile Management:</strong>You can view and edit your profile details as needed.</li>
          </ul>
          <h4>PLease </h4>
        </div>`;
    }
    function showVotingNotStarted() {
      contentDiv.innerHTML = `
        <div class="not-scheduled">
          <h3>Election Scheduled</h3>
          <p>Election is currently scheduled. W:</p>
         
        </div>`;
    } function showElectionStarted() {
      contentDiv.innerHTML = `
        <div class="not-scheduled">
          <h3>Election Started</h3>
          <p>Election iss. W:</p>
        </div>`;
    } function showVotingEnded() {
      contentDiv.innerHTML = `
        <div class="not-scheduled">
          <h3>Election Ended</h3>
          <p>Election is currently scheduled. W:</p>
         
        </div>`;
    }
  </script>
</body>

</html>