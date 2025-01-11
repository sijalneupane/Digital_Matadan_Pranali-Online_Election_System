<!-- guidelines.php -->
<?php
session_start();
if (!isset($_SESSION["email"])) {
  header('Location: ../home/index.php');
}
require_once '../php_for_ajax/districtRegion2.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/results_table.css">
  <title>Results</title>
  <style>
    /* .container {
      width: 100%;
    } */

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

    .content1 {
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

    .content1::-webkit-scrollbar {
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

    /* Styles specific to election warning content1 */
    .admin-content1.election-warning-content1 {
      padding: 20px;
      max-width: 900px;
      margin: 30px auto;
      background-color: white;
      border: 1px solid #ddd;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .admin-content1.election-warning-content1 .notice p,
    .admin-content1.election-warning-content1 .notice ul {
      margin: 15px 0;
      font-size: 18px;
      line-height: 1.6;
    }

    .admin-content1.election-warning-content1 .notice ul {
      padding-left: 20px;
      list-style: disc;
    }

    .admin-content1.election-warning-content1 .link {
      color: #007bff;
      text-decoration: none;
    }

    .admin-content1.election-warning-content1 .link:hover {
      text-decoration: underline;
    }

    .admin-content1.election-warning-content1 .action-suggestions,
    .admin-content1.election-warning-content1 .contact-support {
      margin-top: 20px;
    }

    .admin-content1.election-warning-content1 .action-suggestions h3,
    .admin-content1.election-warning-content1 .contact-support h3 {
      font-size: 20px;
      color: #e25822;
    }

    .admin-content1.election-warning-content1 .action-suggestions ul,
    .admin-content1.election-warning-content1 .contact-support p {
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
</head>


<body>
  <div class="container">
    <?php include '../home/sidebar.php'; ?>

    <script>
      document.querySelector('a[href="../results/results.php"]').classList.add('active');
    </script>
<div class="content">
<div class="header" id="header">
      <!-- <h1>Lets look at the results of the election</h1>-->
    </div>
    <div class="content1" id="content1">
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

      </div>  -->
    </div>
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
  district($_SESSION['district']);
  regionNo($_SESSION['election_region']);
  ?>`}
</form>
<div class="responsive-table-container" id="old-results"></div>
`;;
    let oldHeaderDiv = `<h1>Lets look at the results of the election</h1>`;
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

    // Automatically fetch the voting status every 10 seconds
    setInterval(fetchVotingTime, 1000); // Fetch every 10 seconds (10000 milliseconds)


    function fetchCurrentResults() {
      var searchQuery = document.getElementById('searchQuery').value;
      var district = document.getElementById('district').value;
      var regionNo = document.getElementById('regionNo').value;
      const xhr = new XMLHttpRequest();
      xhr.open(
        'GET',
        '../results/fetch_currentresults.php?searchQuery=' +
        encodeURIComponent(searchQuery) +
        '&district=' + encodeURIComponent(district) +
        '&regionNo=' + encodeURIComponent(regionNo),
        true
      );
      // console.log(district);
      // console.log(regionNo);
      
      xhr.onload = function () {
        if (xhr.status === 200) {
          // alert(xhr.responseText);
          const response = JSON.parse(xhr.responseText);
          if (response.status === "success") {
            const results = response.data;
            const container = document.getElementById('old-results');

            // Clear any previous content1
            container.innerHTML = "";

            if (results.length > 0) {
              // Create table
              const table = document.createElement('table');
              table.className = "responsive-table";

              // Create table header
              const thead = document.createElement('thead');
              thead.innerHTML = `
            <tr>
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
              const tbody = document.createElement('tbody');
              results.forEach(result => {
                const row = document.createElement('tr');
                row.innerHTML = `
              <td>${result.candidateName}</td>
              <td>${result.citizenshipNumber}</td>
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
    let content1Div = document.getElementById("content1");
    // let electionNotScheduled = false;
    // let votingNotStarted = false;
    // let votingStarted = false;
    let isPublishedChecked=false;
    let isNotPublishedChecked=false;
    let resultPublished=false;
    let electionNotEnded=false;

    function checkVotingTime() {
      resultPublished = (votingTime.resultStatus=='published')?true:false;
      // let votingStartTime=votingT
      let currentTime = new Date().getTime();
      let votingStartTime = new Date(votingTime.startTime).getTime();
      let votingEndTime = new Date(votingTime.endTime).getTime();
      if (!isPublishedChecked && resultPublished) {
        isPublishedChecked=true;
        isNotPublishedChecked=false;
        electionNotEnded=false
        console.log('a');
        // electionNotScheduled = votingNotStarted = votingStarted = false;
        showResultPublished();
      } else if (!resultPublished && !isNotPublishedChecked && currentTime > votingEndTime) {
        isPublishedChecked=false;
        isNotPublishedChecked=true;
        electionNotEnded=false;
        console.log('b');
        showResultNotPublished();
      }else if(!electionNotEnded && currentTime < votingEndTime ){
        // isPublishedChecked=false;
        isNotPublishedChecked=false;
        electionNotEnded=true;
        console.log('c');
        showElectionNotEnded();
      }else if(votingTime.error){
        alert("No election scheduled or conducted till now. PLease come back later");
        window.location.href="../home/home.php";
      }
    }

    function showResultPublished() {
      // Add special classes for styling
      headerDiv.className = 'header';
      content1Div.className = 'content1';
      headerDiv.id = 'header';
      content1Div.id = 'content1';
      content1Div.innerHTML = oldContentDiv;
      document.getElementById('header').innerHTML = oldHeaderDiv;
      setDistrictRegion();
      const selects = document.getElementsByTagName("select");
      for (let i = 0; i < selects.length; i++) {
        selects[i].classList.add('search-input');
      }

      document.getElementById('election-name').innerHTML = `${votingTime.electionName}`;
      document.getElementById('startTime').innerHTML = `<strong>Start Time: </strong> ${votingTime.startTime}`;
      document.getElementById('endTime').innerHTML = `<strong>End Time: </strong>${votingTime.endTime}`;
    
      // Call the function to fetch results
      document.onload = setTimeout(fetchCurrentResults, 200);
      document.getElementById('searchQuery').addEventListener('input', function () {
        fetchCurrentResults();
      });
      document.getElementById('district').addEventListener('change', function () {
        setTimeout(fetchCurrentResults, 200);
      });
      document.getElementById('regionNo').addEventListener('change', function () {
        fetchCurrentResults();
      });
    }


    function showResultNotPublished() {
      // let content1Div = document.getElementById('content1');
      // let headerDiv = document.getElementById('header');

      // Add special classes for styling
      headerDiv.className = 'admin-header election-warning-header';
      content1Div.className = 'admin-content1 election-warning-content1';
      headerDiv.id = 'election-warning-header';
      content1Div.id = 'election-warning-content1';

      // Clear previous content1
      content1Div.innerHTML = '';
      headerDiv.innerHTML = '';

      // Add a heading to the headerDiv
      headerDiv.innerHTML = `
        <h1 class="warning-heading">⚠️ Results is not published ! ! !.</h1>
    `;

      // Add detailed content1 to the content1Div
      content1Div.innerHTML = `
        <div class="notice">
            <p><strong>Status:</strong> Although election ended, Results will only be available after the results is published by admin.</p>
            <p><strong>Next Steps:</strong></p>
            <ul>
                <li>Visit the <a href="../home/home.php" class="link">Home</a> to view the home page.</li>
                <li>Go to the <a href="../canidate/candidates.php" class="link">Candidate Page</a> for detailed candidate information.</li>
                <li>Ensure your profile is filled with correct details by visiting <a href="../register_and_login/user_profile.php" class="link">Profile page</a>.</li>
            </ul>
            <hr>
            <div class="action-suggestions">
                <h3>💡 Suggestions</h3>
                <p>To enhance your experience, consider:</p>
                <ul>
                    <li>Viewing previous winner of your constituency in homepage if any election were held previously</li>
                    <li>Submitting complaints or issue in <a href="../home/home.php" class="link">contact_us</a> if you have faced any</li>
                </ul>
            </div>
            <hr>
            <div class="contact-support">
                <h3>📞 Need Help?</h3>
                <p>If you encounter issues, contact our customer support team at 
                <a href="mailto:sijalneupane5@gmail.com" class="link">sijalneupane5@gmail.com</a>.</p>
            </div>
        </div>
    `;
    }

    function showElectionNotEnded() {
      // let content1Div = document.getElementById('content1');
      // let headerDiv = document.getElementById('header');

      // Add special classes for styling
      headerDiv.className = 'admin-header election-warning-header';
      content1Div.className = 'admin-content1 election-warning-content1';
      headerDiv.id = 'election-warning-header';
      content1Div.id = 'election-warning-content1';

      // Clear previous content1
      content1Div.innerHTML = '';
      headerDiv.innerHTML = '';

      // Add a heading to the headerDiv
      headerDiv.innerHTML = `
        <h1 class="warning-heading" style="background-color:light-blue">⚠️ Election is not ended ! ! !.</h1>
    `;

      // Add detailed content1 to the content1Div
      content1Div.innerHTML = `
        <div class="notice">
            <p><strong>Status:</strong> Results will only be available after the voting period ends.</p>
            <p><strong>Next Steps:</strong></p>
            <ul>
                <li>Visit the <a href="../home/home.php" class="link">Home</a> to view homepage.</li>
                <li>Go to the <a href="../canidate/candidates.php" class="link">Candidate Page</a> for detailed candidate information.</li>
                <li>Ensure your profile is filled with correct details by visiting <a href="../register_and_login/user_profile.php" class="link">Profile page</a>.</li>
            </ul>
            <hr>
            <div class="action-suggestions">
                <h3>💡 Suggestions</h3>
                <p>To enhance your experience, consider:</p>
                <ul>
                    <li>Viewing previous winner of your constituency in homepage if any election were held previously</li>
                    <li>Submitting complaints or issue in <a href="../home/home.php" class="link">contact_us</a> if you have faced any</li>
                </ul>
            </div>
            <hr>
            <div class="contact-support">
                <h3>📞 Need Help?</h3>
                <p>If you encounter issues, contact our customer support team at 
                <a href="mailto:sijalneupane5@gmail.com" class="link">sijalneupane5@gmail.com</a>.</p>
            </div>
        </div>
    `;
    }
    // Show/hide the Go to Top button based on scroll position of the content1 container
    const contentContainer = document.getElementById("content1");
    const goToTopButton = document.getElementById("goToTop");

    contentContainer.addEventListener("scroll", function () {
      if (contentContainer.scrollTop > 100) {
        goToTopButton.classList.add("show");
      } else {
        goToTopButton.classList.remove("show");
      }
    });

    // Scroll to the top of the content1 container when the button is clicked
    function scrollToTop() {
      contentContainer.scrollTo({
        top: 0,
        behavior: "smooth",
      });
    }
  </script>
</body>

</html>