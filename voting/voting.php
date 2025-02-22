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
  <link rel="stylesheet" href="../styles/modal1.css">
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
a{
  text-decoration: none;
}
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
      color: #354b61;
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
/* not-started css starts here */

.title {
    font-size: 2rem;
    font-weight: bold;
    color: #ff4d4d;
    margin-bottom: 15px;
}

.description {
    font-size: 1.2rem;
    margin: 0 auto 20px;
    line-height: 1.5;
}

.info-section {
    background: rgba(0, 0, 0, 0.3);
    padding: 20px;
    border-radius: 12px;
    margin-top: 20px;
    text-align: left;
}

.info-title {
    font-size: 1.5rem;
    color:rgb(35, 80, 163);
    margin-bottom: 10px;
    text-align: center;
}

.info-list {
    list-style: none;
    padding: 0;
}

.info-item {
    font-size: 1.1rem;
    margin: 10px 0;
    padding-left: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-item strong {
    color:rgb(31, 83, 161);
}

.contact-link {
    color:rgb(126, 39, 153);
    text-decoration: underline;
}

.button-group {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 25px;
    flex-wrap: wrap;
}

.btn {
    padding: 12px 20px;
    font-size: 1rem;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
    text-transform: uppercase;
}

.review-btn, .results-btn {
    background: #4caf50;
    color: #fff;
}

.profile-btn {
    background: #1e88e5;
    color: #fff;
}

.contact-btn {
    background: #ff9800;
    color: #fff;
}

.btn:hover {
    opacity: 0.85;
}

/* Responsive Design */
@media (max-width: 600px) {
    .not-started {
        padding: 20px;
    }

    .title {
        font-size: 1.8rem;
    }

    .description {
        font-size: 1.1rem;
    }

    .info-title {
        font-size: 1.3rem;
    }

    .info-item {
        font-size: 1rem;
    }

    .btn {
        padding: 10px 15px;
        font-size: 0.9rem;
    }
}

/* not-started css ends here */

    /* Candidate grid container */
    .candidate-grid {
      display: grid;
      gap: 20px;
      /* Space between grid items */
      padding: 0;
      list-style: none;
      /* Remove default list styling */
    }

    /* Default: 1 candidate per row (mobile) */
    .candidate-grid {
      grid-template-columns: 1fr;
      justify-content: center;
      /* Center the grid items */
    }

    /* Tablets: 2 candidates per row */
    @media (min-width: 768px) {
      .candidate-grid {
        grid-template-columns: repeat(2, auto);
        justify-content: start;
        /* Center the grid items */
      }
    }

    /* Large screens: 3 candidates per row */
    @media (min-width: 1024px) {
      .candidate-grid {
        grid-template-columns: repeat(3, auto);
        justify-content: start;
        /* Center the grid items */
      }
    }

    /* Candidate item styling */
    .candidate-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      border: 1px solid #ccc;
      /* Border for visual separation */
      border-radius: 10px;
      background-color: #f9f9f9;
      transition: box-shadow 0.3s, border-color 0.3s;
    }

    .candidate-item:hover {
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Candidate label styling */
    .candidate-label {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      padding: 10px;
      border: 2px solid transparent;
      border-radius: 5px;
      transition: border-color 0.3s, background-color 0.3s;
    }

    /* Hover effect */
    .candidate-label:hover {
      border-color: #007bff;
      /* Blue border on hover */
    }

    /* Selected state */
    .candidate-label.selected {
      border-color: #28a745;
      /* Green border for selected state */
      background-color: #f0f9f4;
    }

    .candidate-details {
      display: flex;
      align-items: center;
      justify-content: space-evenly;
      min-width: 300px;
    }

    /* Candidate photo */
    .candidate-photo {
      width: 80px;
      height: 80px;
      object-fit: contain;
      border-radius: 50%;
      margin-bottom: 10px;
    }

    /* Candidate info */
    .candidate-info {
      /* text-align: center; */
    }

    .candidate-info p {
      margin: 0;
    }

    /* Party photo */
    .party-photo {
      width: 40px;
      height: 40px;
      margin-top: 5px;
    }

    /* Hide radio buttons */
    .candidate-radio {
      display: none;
    }


    /* voting status voted style */
    .heading-1 {
      font-size: 26px;
      color: #444;
      font-weight: 700;
      margin-bottom: 15px;
    }

    .voted-paragraph {
      font-size: 16px;
      color: #555;
      line-height: 1.8;
      margin: 10px 0;
    }

    .button-container {
      margin-top: 25px;
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      justify-content: center;
    }
.button{
  align-items: center;
  appearance: none;
  background-image: radial-gradient(100% 100% at 100% 0, #5adaff 0, #5468ff 100%);
  border: 0;
  border-radius: 6px;
  box-shadow: rgba(45, 35, 66, .4) 0 2px 4px,rgba(45, 35, 66, .3) 0 7px 13px -3px,rgba(58, 65, 111, .5) 0 -3px 0 inset;
  box-sizing: border-box;
  color: #fff;
  cursor: pointer;
  display: inline-flex;
  font-family: "JetBrains Mono",monospace;
  height: 48px;
  justify-content: center;
  line-height: 1;
  list-style: none;
  overflow: hidden;
  padding-left: 16px;
  padding-right: 16px;
  position: relative;
  text-align: left;
  text-decoration: none;
  transition: box-shadow .15s,transform .15s;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
  white-space: nowrap;
  will-change: box-shadow,transform;
  font-size: 18px;
}

.button:focus {
  box-shadow: #3c4fe0 0 0 0 1.5px inset, rgba(45, 35, 66, .4) 0 2px 4px, rgba(45, 35, 66, .3) 0 7px 13px -3px, #3c4fe0 0 -3px 0 inset;
}

.button:hover {
  box-shadow: rgba(45, 35, 66, .4) 0 4px 8px, rgba(45, 35, 66, .3) 0 7px 13px -3px, #3c4fe0 0 -3px 0 inset;
  transform: translateY(-2px);
}

.button:active {
  box-shadow: #3c4fe0 0 3px 7px inset;
  transform: translateY(2px);
}
.vote-button {
    background: linear-gradient(135deg, #00c9a7, #6a00f4); /* Teal to Purple */
    color: white;
    font-size: 18px;
    font-weight: bold;
    padding: 12px 24px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    box-shadow: 0 4px 10px 5px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease-in-out;
    text-transform: uppercase;
}

.vote-button:hover {
    background: linear-gradient(135deg, #6a00f4, #00c9a7);
    transform: scale(1.05);
    box-shadow: 0 6px 15px 10px rgba(0, 0, 0, 0.3);
}

.vote-button:active {
    transform: scale(0.95);
    box-shadow: 0 8px 8px -5px rgba(0, 0, 0, 0.2);
}

    .info-div {
      background-color: #f3f3f3;
      padding: 20px;
      border-left: 6px solid #3381a5;
      margin-top: 20px;
      border-radius: 8px;
    }

    @media (max-width: 600px) {

      .button {
        font-size: 14px;
        padding: 10px 20px;
      }

      .heading-1 {
        font-size: 22px;
      }

      .voted-paragraph {
        font-size: 14px;
      }
    }
  </style>
  <script src="../js/errorMessage_modal1.js"></script>
</head>

<body>
  <div class="container">
    <?php include '../home/sidebar.php'; ?>

    <script>
      document.querySelector('a[href="../voting/voting.php"]').classList.add('active');
    </script>
    <div id="modal1" class="modal-overlay1 all-modals">
      <div class="modal-content1">
        <p id="modalMessage1"></p>
        <button onclick="closeModal1()">Close</button>
      </div>
    </div>

    <div class="content" id="content">
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

      let votingStatus = <?php echo json_encode($_SESSION['votingStatus']); ?>;
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
          console.log(votingStatus);

        } else if (!votingStarted && (currentTime > votingStartTime && currentTime < votingEndTime)) {
          votingStarted = true;
          electionNotScheduled = votingNotStarted = votingEnded = false;
          if (votingStatus == "voted") {
            showAlreadyVoted();
          }else {
            showVotingStarted();
          }
          console.log(votingStatus);
        }
        else if (!votingEnded && currentTime > votingEndTime) {
          votingEnded = true;
          electionNotScheduled = votingNotStarted = votingStarted = false;
          if (votingStatus == "voted") {
            showAlreadyVoted();
          } else {
          showVotingEnded();
        }
          console.log(votingStatus);
        }
      }

      // Function to display "Election Not Scheduled" UI
      function showElectionNotScheduled() {
        contentDiv.innerHTML = `
        <div class="not-scheduled">
          <h3>Election Not Scheduled</h3>
          <p>Currently there is no election scheduled.As you have logged in to our system before any election is scheduled, you can look upto some features of our voting system. It includes:</p>
          <ul class="election-process">
            <li><strong>View Candidates:</strong> Explore the home page and all other pages</li>
            <li><strong>Vote:</strong> Cast your vote during the voting period to the candidate of your choice.</li>
            <li><strong>Results:</strong> After voting ends,you can view the results in results page.</li>
            <li><strong>Profile Management:</strong>You can view and edit your profile details as needed.</li>
          </ul>
          <h4>Please stay tuned for obtaining information about election scheduling and other necessary notices.</h4>
        </div>`;
      }

      function showVotingNotStarted() {
    contentDiv.innerHTML = `
    <div class="not-started">
      <h3 class="title">Election Scheduled</h3>
      <p class="description">The election is scheduled, but voting has not started yet.</p>
      
      <div class="info-section">
        <h4 class="info-title">Prepare Yourself for the Election:</h4>
        <ul class="info-list">
          <li class="info-item">📅 <strong>Election Time:</strong>From :${new Date(votingTime.startTime).toLocaleString()} To:${new Date(votingTime.endTime).toLocaleString()}</li>
          <li class="info-item">📜 <strong>Review Candidates:</strong> Learn about each candidate’s manifesto, qualifications, and vision for your community.</li>
          <li class="info-item">📊 <strong>Compare Policies:</strong> Analyze their past performance and plans to make an informed choice.</li>
          <li class="info-item">🆔 <strong>Manage Your Profile:</strong> Ensure your voter information is up-to-date to avoid any issues during voting.</li>
          <li class="info-item">📞 <strong>Report Issues:</strong> If you face any problems, reach out through the <a href="#" class="contact-link">Contact Us</a> page.</li>
          <li class="info-item">📆 <strong>Mark the Date:</strong> Stay updated with the voting start time so you don’t miss your chance.</li>
        </ul>
      </div>

      <div class="button-group">
        <a href="../candidates/candidates.php" class="btn review-btn">Review Candidates</a>
        <a href="../register_and_login/user_profile.php" class="btn profile-btn">Manage Profile</a>
        <a href="../home/contact_us.php" class="btn contact-btn">Contact Us</a>
      </div>
    </div>`;
}

      function showVotingStarted() {
        contentDiv.innerHTML = `
      <div class="voting-container">
        <h3>Voting Period</h3>
        <p><strong>Start Time:</strong> ${new Date(votingTime.startTime).toLocaleString()}</p>
        <p><strong>End Time:</strong> ${new Date(votingTime.endTime).toLocaleString()}</p>

        <h4>Available Candidates</h4>
        <form id="votingForm">
          <ul class="candidate-grid" id="candidateList">
            <!-- Candidates will be dynamically inserted here -->
          </ul>
          <button type="button" onclick="submitVote()" class="vote-button">Vote</button>
        </form>
      </div>
    `;
      fetchCandidates();
      }

      function showAlreadyVoted() {
            const headingSelector = 'h1 class="heading-1"';
            const paragraphSelector = 'p class="voted-paragraph"';

            contentDiv.innerHTML = `
                <${headingSelector}>Thank You for Voting!</${headingSelector}>
                <${paragraphSelector}>Thank you for utilizing your rights to vote and choosing the best candidates for your progress.</${paragraphSelector}>
                <${paragraphSelector}> <strong>${votingTime.electionName}</strong></${paragraphSelector}>
                <${paragraphSelector}><strong>Voting Start:</strong> ${votingTime.startTime}</${paragraphSelector}>
                <${paragraphSelector}><strong>Voting End:</strong>  ${votingTime.endTime}</${paragraphSelector}>
                <div class="info-div">
                    <${headingSelector} style="font-size: 20px;">Important Announcement</${headingSelector}>
                    <${paragraphSelector}>The election results will be published within 1 hour after the election ends. Please wait patiently for admin to publish results.</${paragraphSelector}>
                    <${paragraphSelector}>If you want to view your profile, click the button below. If you have questions, contact us using the other button.</${paragraphSelector}>
                    <div class="button-container">
                        <button class="button" onclick="window.location.href='../register_and_login/user_profile.php'">View Profile</button>
                        <button class="button" onclick="window.location.href='../home/contact_us.php'">Contact Us</button>
                    </div>
                </div>
            `;
        }
      function fetchCandidates() {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '../voting/fetch_candidates.php', true);
        xhr.onload = function () {
          if (this.status === 200) {
            const candidates = JSON.parse(this.responseText);
            const candidateList = document.getElementById('candidateList');
            candidateList.innerHTML = ''; // Clear the list before adding new items

            candidates.forEach(candidate => {
              const listItem = document.createElement('div');
              listItem.className = 'candidate-item';

              listItem.innerHTML = `
          <input 
            type="radio" 
            name="candidate" 
            value="${candidate.candidateId}" 
            id="candidate_${candidate.candidateId}" 
            class="candidate-radio" 
          />
          <label for="candidate_${candidate.candidateId}" class="candidate-label">
            <div class="candidate-details">
              <img src="../uploads/${candidate.candidate_photo}" alt="${candidate.name}" class="candidate-photo" />
              <div class="candidate-info">
                <h3>${candidate.name}</h3>
                <p>Party: ${candidate.partyName}</p>
                <img src="../uploads/${candidate.partyLogo}" alt="${candidate.partyName}" class="party-photo" />
              </div>
            </div>
          </label>
        `;

              candidateList.appendChild(listItem);
            });

            // Add event listeners for toggle selection
            document.querySelectorAll('.candidate-label').forEach(label => {
              const radio = label.previousElementSibling; // The associated radio button

              label.addEventListener('click', function (e) {
                // If already selected, unselect on second click
                if (radio.checked) {
                  radio.checked = false;
                  label.classList.remove('selected');
                } else {
                  // Select the candidate
                  document.querySelectorAll('.candidate-label').forEach(lbl => lbl.classList.remove('selected'));
                  radio.checked = true;
                  label.classList.add('selected');
                }

                // Prevent default behavior of the label toggling the radio directly
                e.preventDefault();
              });
            });
          }
        };
        xhr.send();
      }
      function submitVote() {
        const selectedCandidate = document.querySelector('input[name="candidate"]:checked');
        if (!selectedCandidate) {
          showErrorModal('Please select a candidate before voting.',false);
          // alert('Please select a candidate before voting.');
          return;
        }

        const voterPassword = prompt('Enter your password to submit your vote:');
        if (!voterPassword) {
          showErrorModal('Password is required to submit your vote.',false);
          // alert('Password is required to submit your vote.');
          return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../voting/submit_vote.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
          if (this.status === 200) {
            let response;
            try {
              response = JSON.parse(this.responseText);
            } catch (e) {
              console.error('Error parsing JSON:', response.error);
              // alert('An error occurred while processing the server response.');
              showErrorModal("An error occurred while processing the server response.",false);
              return;
            }
            console.log(xhr.responseText);
            if (response.success) {
              showErrorModal("Your vote has been successfully submitted.",true);
              showAlreadyVoted();
              // alert('Your vote has been successfully submitted.');
              // contentDiv.innerHTML = `<h3>Thank you for voting!</h3>`;
            } else if (response.error === 'password_mismatch') {
              // alert('Incorrect password. Please re-enter your password to vote.');
              showErrorModal("Incorrect password. Please re-enter your password to vote.",false);
            } else {
              // alert('An error occurred while submitting your vote. Please try again.');
              showErrorModal("An error occurred while submitting your vote. Please try again.",false);
            }
          }
        };
        xhr.send(`candidateId=${selectedCandidate.value}&password=${encodeURIComponent(voterPassword)}`);
      }

      function showVotingEnded() {
    contentDiv.innerHTML = `
    <div class="election-ended">
      <h3 class="title">Election Ended</h3>
      <p class="description">The election has concluded, and unfortunately, you did not cast your vote.</p>
      
      <div class="info-section">
        <h4 class="info-title">What You Can Do Next:</h4>
        <ul class="info-list">
          <li class="info-item">📅 <strong>Election Period:</strong> From: ${new Date(votingTime.startTime).toLocaleString()} To: ${new Date(votingTime.endTime).toLocaleString()}</li>
          <li class="info-item">📜 <strong>Review Results:</strong> Check the final election results and see how your community voted.</li>
          <li class="info-item">📊 <strong>Analyze Outcomes:</strong> Learn about the winning candidates and their upcoming policies.</li>
          <li class="info-item">🆔 <strong>Stay Updated:</strong> Ensure your voter profile is up-to-date for future elections.</li>
          <li class="info-item">📞 <strong>Report Concerns:</strong> If you have any queries, reach out through the <a href="#" class="contact-link">Contact Us</a> page.</li>
          <li class="info-item">📆 <strong>Prepare for Next Time:</strong> Mark your calendar for upcoming elections so you don't miss your chance.</li>
        </ul>
      </div>

      <div class="button-group">
        <a href="../results/results.php" class="btn results-btn">View Results</a>
        <a href="../register_and_login/user_profile.php" class="btn profile-btn">Manage Profile</a>
        <a href="../home/contact_us.php" class="btn contact-btn">Contact Us</a>
      </div>
    </div>`;
}
      // Close the modal when clicking outside of the modal content
      window.onclick = function (event) {
          var modals = document.getElementsByClassName('all-modals');
          for (var i = 0; i < modals.length; i++) {
              if (event.target == modals[i]) {
                  modals[i].style.display = 'none';
              }
          }
      }
    </script>
</body>

</html>