<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: ../admin/admin_login.php');
}
$errorMessage = isset($_SESSION['errorMsg']) ? $_SESSION['errorMsg'] : '';
unset($_SESSION['errorMsg']); // Clear the message
$successMessage = isset($_SESSION['successMsg']) ? $_SESSION['successMsg'] : '';
unset($_SESSION['successMsg']); // Clear the message

$_SESSION['pageName'] = "Manage Results";// Clear the message
require_once '../php_for_ajax/districtRegion2.php';
?>

<!DOCTYPE html>
<html lang="en">

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Results</title>
<link rel="icon" href="../images/DMP logo.png" type="image/x-icon">
<link rel="stylesheet" href="../styles/results_table.css">
<link rel="stylesheet" href="../styles/modal1.css">
<style>
  .container {
    width: 100%;
  }

  .header {
    margin: 15px auto;
    padding: 0px 10px;
    border-radius: 10px;
    width: 95%;
    display: flex;
    align-items: center;
  }

  .header h1 {
    flex: 1;
    text-align: center;
  }

  .header #publish-result-btn {
    width: 150px;
    font-size: 16px;
    font-weight: 600;
    color: #fff;
    cursor: pointer;
    height: 45px;
    text-align: center;
    border: none;
    background-size: 300% 100%;
    border-radius: 10px;
    moz-transition: all .4s ease-in-out;
    -o-transition: all .4s ease-in-out;
    -webkit-transition: all .4s ease-in-out;
    transition: all .4s ease-in-out;
  }

  .header #publish-result-btn:hover {
    background-position: 100% 0;
    moz-transition: all .4s ease-in-out;
    -o-transition: all .4s ease-in-out;
    -webkit-transition: all .4s ease-in-out;
    transition: all .4s ease-in-out;
  }

  .header #publish-result-btn:focus {
    outline: none;
  }

  .header #publish-result-btn {
    background-image: linear-gradient(to right,
        #29323c,
        #485563,
        #2b5876,
        #4e4376);
    box-shadow: 0 4px 15px 0 rgba(45, 54, 65, 0.75);
  }

  .content {
    height: 75vh;
    overflow-y: auto;
    scrollbar-width: 0px;
    width: 95%;
    margin: 0 auto;
    background-color: white;
    padding: 30px 20px;
    border-radius: 10px;
    -ms-overflow-style: none;
    /* IE and Edge */
    scrollbar-width: none;
    /* Firefox */
  }

  .content::-webkit-scrollbar {
    display: none;
    /* Chrome, Safari, and Opera */
  }

  /* General styles
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
    color: #333;
} */

  /* Styles specific to election warning header */
  .admin-header.election-warning-header {
    background-color: #ff6f61;
    color: white;
    padding: 20px;
    text-align: center;
    border-bottom: 3px solid #e25822;
  }

  .admin-header.election-warning-header .warning-heading {
    margin: 0;
    font-size: 28px;
    font-weight: bold;
  }

  /* Styles specific to election warning content */
  .admin-content.election-warning-content {
    padding: 20px;
    max-width: 900px;
    margin: 30px auto;
    background-color: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  }

  .admin-content.election-warning-content .notice p,
  .admin-content.election-warning-content .notice ul {
    margin: 15px 0;
    font-size: 18px;
    line-height: 1.6;
  }

  .admin-content.election-warning-content .notice ul {
    padding-left: 20px;
    list-style: disc;
  }

  .admin-content.election-warning-content .link {
    color: #007bff;
    text-decoration: none;
  }

  .admin-content.election-warning-content .link:hover {
    text-decoration: underline;
  }

  .admin-content.election-warning-content .action-suggestions,
  .admin-content.election-warning-content .contact-support {
    margin-top: 20px;
  }

  .admin-content.election-warning-content .action-suggestions h3,
  .admin-content.election-warning-content .contact-support h3 {
    font-size: 20px;
    color: #e25822;
  }

  .admin-content.election-warning-content .action-suggestions ul,
  .admin-content.election-warning-content .contact-support p {
    margin: 10px 0;
  }


  #election-info {
    display: flex;
    justify-content: space-around;
    align-items: center;
    width: 100%;
    flex-wrap: wrap;
    gap: 10px;
  }

  #election-name,
  #startTime,
  #endTime {
    padding: 10px;
    width: 20%;
    min-width: 280px;
    background-color: #344a59;
    color: white;
    border-radius: 8px;
    font-size: 16px;
    text-align: center;
  }

  #election-name {
    font-size: 20px;

    @media screen and (max-width:965px) {
      margin: 0 16%;
    }
  }

  /* Search area styling */
  .search-form {
    display: flex;
    gap: 15px;
    /* max-width: 450px; */
    margin: 10px auto;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .search-input-container {
    align-content: center;
    position: relative;
    /* flex: 1 0 40%; */
    flex: 3;
  }

  .search-input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    transition: border-color 0.3s;
  }

  .search-input:focus {
    border-color: #007bff;
    outline: none;
  }

  .search-input:not(:first-child) {
    width: 20%;
  }

  #searchQuery::placeholder {
    color: #bbb;
  }

  #district,
  #regionNo {
    appearance: none;
    background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMCIgaGVpZ2h0PSI2Ij4KICA8cGF0aCBkPSJNIDAgMCAxMCAwIDUgNiIgZmlsbD0iIzY2NiIgLz4KPC9zdmc+Cg==') no-repeat right 10px center;
    background-size: 10px 6px;
    padding-right: 30px;
  }

  #district option,
  #regionNo option {
    padding: 10px;
  }

  /* Styling for the Go to Top button */
  .go-to-top {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #007BFF;
    /* Button color */
    color: #fff;
    /* Text color */
    border: none;
    border-radius: 50%;
    padding: 10px 15px;
    cursor: pointer;
    font-size: 20px;
    display: none;
    /* Initially hidden */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s ease;
  }

  .go-to-top:hover {
    background-color: #0056b3;
    /* Darker color on hover */
  }

  /* Show the button when needed */
  .go-to-top.show {
    display: block;
  }
</style>
<script src="../js/errorMessage_modal1.js"></script>
</head>

<body>
  <?php require '../admin/admin_navbar.php'; ?>
  <div class="container">
    <?php require_once '../home/logout_modals_html.php';
    logoutModalPhp("admin"); ?>
    <div id="modal1" class="modal-overlay1 all-modals">
      <div class="modal-content1">
        <p id="modalMessage1"></p>
        <button onclick="closeModal1()">Close</button>
      </div>
    </div>
    <!-- Modal Structure -->
    <div id="publishModal" class="publishModal all-modals"
      style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);">
      <div class="publish-modal-content"
        style=" background: white; padding: 20px; width: 350px; text-align: center; border-radius: 10px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
        <h2 style="margin-bottom:15px;">Confirm Publish</h2>
        <p style="margin-bottom:5px;">Do you really want to publish the results?</p>
        <div class="publish-modal-buttons" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;">
          <button id="confirmPublish"
            style="font-size: 1.1em;margin: 10px; padding: 10px 15px; border: none; cursor: pointer; border-radius: 5px; background-color: #28a745; color: white;">Publish</button>
          <button id="cancelPublish"
            style="font-size: 1.1em;margin: 10px; padding: 10px 15px; border: none; cursor: pointer; border-radius: 5px; background-color: #dc3545; color: white;">Cancel</button>
        </div>
      </div>
    </div>
    <div class="header" id="header">
      <!-- <h1>Lets look at the results of the election</h1>
      <button id="publish-result-btn">Publish Result</button> -->
    </div>

    <div class="content" id="content">
      <!-- <div id="election-info">
        <h3 id="election-name"></h3>
        <p id="startTime"></p>
        <p id="endTime"></p>
      </div>
      <form onsubmit="event.preventDefault();" class="search-form">
        <div class="search-input-container">

          <input type="text" id="searchQuery" placeholder="Search by name" class="search-input">
          <i class="fas fa-search" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);"></i>
        </div>
        <?php

        // district();
        // regionNo();
        ?>
      </form>
      <div class="responsive-table-container" id="old-results">

      </div> -->
    </div>
  </div>
  <button id="goToTop" class="go-to-top" title="Go to Top" onclick="scrollToTop()">↑</button>

  <script>
    let oldContentDiv = `
    <div id="election-info">
      <h3 id="election-name"></h3>
      <p id="startTime"></p>
      <p id="endTime"></p>
    </div>
    <form onsubmit="event.preventDefault();" class="search-form">
      <div class="search-input-container">
        <input type="text" id="searchQuery" placeholder="Search by name" class="search-input">
        <i class="fas fa-search" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);"></i>
      </div>
      <!-- PHP generated dropdowns -->
      ${`
      <?php
      district();
      regionNo();
      ?>`}
    </form>
    <div class="responsive-table-container" id="old-results"></div>
    `;;
    let oldHeaderDiv = `<h1>Lets look at the results of the election</h1>
      <button id="publish-result-btn">Publish Result</button>`;
    // Global variable to store the voting status
    let votingTime = {};

    // Function to fetch voting status from the server
    function fetchVotingTime() {
      let xhr = new XMLHttpRequest();
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

    function fetchCurrentResults() {
      var searchQuery = document.getElementById('searchQuery').value;
      var district = document.getElementById('district').value;
      var regionNo = document.getElementById('regionNo').value;
      let xhr = new XMLHttpRequest();
      xhr.open(
        'GET',
        '../results/fetch_currentresults.php?searchQuery=' +
        encodeURIComponent(searchQuery) +
        '&district=' + encodeURIComponent(district) +
        '&regionNo=' + encodeURIComponent(regionNo),
        true
      );
      xhr.onload = function () {
        if (xhr.status === 200) {
          // alert(xhr.responseText);
          let response = JSON.parse(xhr.responseText);
          if (response.status === "success") {
            let results = response.data;
            let container = document.getElementById('old-results');

            // Clear any previous content
            container.innerHTML = "";

            if (results.length > 0) {
              // Create table
              let table = document.createElement('table');
              table.className = "responsive-table";

              // Create table header
              let thead = document.createElement('thead');
              thead.innerHTML = `
            <tr>
              <th>Result ID</th>
              <th>Candidate Name</th>
              <th>Citizenship Number</th>
              <th>Party Name</th>
              <th>District</th>
              <th>Region No</th>
              <th>Total Votes</th>
            </tr>
          `;
              table.appendChild(thead);

              // Create table body
              let tbody = document.createElement('tbody');
              results.forEach(result => {
                let row = document.createElement('tr');
                row.innerHTML = `
              <td>${result.currentResultId}</td>
              <td>${result.name}</td>
              <td>${result.citizenship_number}</td>
              <td>${result.partyName}</td>
              <td>${result.district}</td>
              <td>${result.regionNo}</td>
              <td>${result.totalVotes}</td>
            `;
                tbody.appendChild(row);
              });
              table.appendChild(tbody);

              // Append table to container
              container.appendChild(table);
            } else {
              container.innerHTML = "<p>No results found.</p>";
            }
          } else {
            console.error("Error: " + response.message);
          }
        } else {
          console.error("AJAX request failed with status: " + xhr.status);
        }
      };
      xhr.send();
    }

    let headerDiv = document.getElementById('header');
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
      if (votingTime.error) {
        showElectionNotScheduled();
        // alert("No election scheduled or conducted till now. PLease come back later");
        // window.location.href = "../admin/admin_home.php";
      } else if (!votingEnded && currentTime > votingEndTime) {
        votingEnded = true;
        electionNotScheduled = votingNotStarted = votingStarted = false;
        showElectionEnded();
      } else if (currentTime < votingEndTime) {
        votingEnded = false;
        showElectionNotEnded();
      }
    }

    function showElectionEnded() {
      console.log("Election Ended");
      // Add special classes for styling
      headerDiv.className = 'header';
      contentDiv.className = 'content';
      headerDiv.id = 'header';
      contentDiv.id = 'content';
      contentDiv.innerHTML = oldContentDiv;
      document.getElementById('header').innerHTML = oldHeaderDiv;
      setDistrictRegion();
      let selects = document.getElementsByTagName("select");
      for (let i = 0; i < selects.length; i++) {
        selects[i].classList.add('search-input');
      }

      document.getElementById('election-name').innerHTML = `${votingTime.electionName}`;
      document.getElementById('startTime').innerHTML = `<strong>Start Time: </strong> ${votingTime.startTime}`;
      document.getElementById('endTime').innerHTML = `<strong>End Time: </strong>${votingTime.endTime}`;
      if (votingTime.resultStatus == 'published') {
        changePublishButtonStatus();
        // publishResultBtn.style.width=`0px`;
      }
      // Call the function to fetch results
      document.onload = fetchCurrentResults();
      document.getElementById('searchQuery').addEventListener('input', function () {
        fetchCurrentResults();
      });
      document.getElementById('district').addEventListener('change', function () {
        setTimeout(fetchCurrentResults, 200);
      });
      document.getElementById('regionNo').addEventListener('change', function () {
        fetchCurrentResults();
      });

      //Confirm publish button
      document.getElementById('confirmPublish').addEventListener('click', function () {
        hidePublishModal();
        sendPublishRequest();
      });
      //Cancel publish button
      document.getElementById('cancelPublish').addEventListener('click', function () {
        hidePublishModal();
      });
      //Publish result button
      document.getElementById('publish-result-btn').onclick = function () {
        showPublishModal();
      };

    }

    function showPublishModal() {
      document.getElementById('publishModal').style.display = 'block';
    }

    function hidePublishModal() {
      document.getElementById('publishModal').style.display = 'none';
    }

    function changePublishButtonStatus() {
      publishResultBtn = document.getElementById('publish-result-btn');
        publishResultBtn.textContent = `Result Published`;
        publishResultBtn.style.background = `#2c9f43`;
        publishResultBtn.disabled = true;
        publishResultBtn.style.boxShadow = "none";
        publishResultBtn.onmouseover = function () {
          publishResultBtn.style.cursor = `not-allowed`;

        }
    }
    let responseMsgFromPublishResult = '';

    function sendPublishRequest() {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../admin/publish_results.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          try {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
              showElectionEnded();
              changePublishButtonStatus();
              showErrorModal("Results have been published successfully!", true);
            } else {
              responseMsgFromPublishResult = "An error occurred: " + response.message;
            }
          } catch (e) {
            responseMsgFromPublishResult = "Unexpected response from the server.";
          }
        } else {
          responseMsgFromPublishResult = "Failed to connect to the server. Please try again later.";
        }

        if (responseMsgFromPublishResult) {
          showErrorModal(responseMsgFromPublishResult);
          responseMsgFromPublishResult = '';
        }
      }
    };

    xhr.send("action=publish");
  }


    function showElectionNotEnded() {
      // let contentDiv = document.getElementById('content');
      // let headerDiv = document.getElementById('header');

      // Add special classes for styling
      headerDiv.className = 'admin-header election-warning-header';
      contentDiv.className = 'admin-content election-warning-content';
      headerDiv.id = 'election-warning-header';
      contentDiv.id = 'election-warning-content';

      // Clear previous content
      contentDiv.innerHTML = '';
      headerDiv.innerHTML = '';

      // Add a heading to the headerDiv
      headerDiv.innerHTML = `
        <h1 class="warning-heading">⚠️ Election Not Ended</h1>
    `;

      // Add detailed content to the contentDiv
      contentDiv.innerHTML = `
        <div class="notice">
            <p><strong>Status:</strong> The current election is ongoing. Results will only be available after the election ends.</p>
            <p><strong>Next Steps:</strong></p>
            <ul>
                <li>Visit the <a href="../admin/admin_home.php" class="link">Homepage</a> to monitor voter turnout and live updates.</li>
                <li>Go to the <a href="../admin/manage_candidates.php" class="link">Candidate Page</a> for detailed candidate information.</li>
                <li>Ensure all pending statuses are checked by visiting <a href="../admin/manage_voters.php" class="link">Manage Voters</a>.</li>
            </ul>
            <hr>
            <div class="action-suggestions">
                <h3>💡 Suggestions</h3>
                <p>To enhance efficiency, consider:</p>
                <ul>
                    <li>Sending reminders to unregistered voters.</li>
                    <li>Reviewing submitted complaints or issues.</li>
                </ul>
            </div>
            <hr>
            <div class="contact-support">
                <h3>📞 Need Help?</h3>
                <p>If you encounter issues, contact the IT support team at 
                   <a href="mailto:sijalneupane5@gmail.com" class="link">sijalneupane5@gmail.com</a>.</p>
            </div>
        </div>
    `;
    }

      // Election not scheduled function
      function showElectionNotScheduled() {
      document.getElementById('content').innerHTML = `
      <div id="no-election-modal-overlay" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.5); display: flex; justify-content: center; align-items: center;">
        <div id="no-election-modal-content" style="background: white; padding: 20px; border-radius: 10px; position: relative; width: 300px; text-align: center; border: 2px solid red; box-shadow: 0 0 5px red;">
          <button id="no-election-modal-close-btn" style="position: absolute; top: 10px; right: 10px; background: red; color: white; border: none; border-radius:4px;padding: 5px 10px; cursor: pointer;">&times;</button>
          <h2 style="color: red;">No Election Found</h2>
          <p style="font-weight:bold;">Any election has not been Scheduled. Please check back later.</p>
        </div>
      </div>
    `;

      // Close modal when clicking outside the modal content
      document.getElementById('no-election-modal-overlay').addEventListener('click', function (event) {
        if (event.target.id === 'no-election-modal-overlay' || event.target.id === 'no-election-modal-close-btn') {
          closeNoElectionModal();
        }
      });
    }
    function closeNoElectionModal() {
      document.getElementById('content').innerHTML = '';
      window.location.href = '../admin/admin_home.php';
    }



    // Show/hide the Go to Top button based on scroll position of the content container
    let contentContainer = document.getElementById("content");
    let goToTopButton = document.getElementById("goToTop");

    contentContainer.addEventListener("scroll", function () {
      if (contentContainer.scrollTop > 100) {
        goToTopButton.classList.add("show");
      } else {
        goToTopButton.classList.remove("show");
      }
    });

    // Scroll to the top of the content container when the button is clicked
    function scrollToTop() {
      contentContainer.scrollTo({
        top: 0,
        behavior: "smooth",
      });
    }

    // Show error modal if there's an error message
    let errorMessage = <?= json_encode($errorMessage); ?>;

    let successMessage = <?= json_encode($successMessage); ?>;
    if (errorMessage) {
      showErrorModal(errorMessage);
    } else if (successMessage) {
      showErrorModal(successMessage, true);
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