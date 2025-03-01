<!-- guidelines.php -->
<?php
session_start();

require_once '../php_for_ajax/districtRegion2.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/results_table.css">
  <link rel="stylesheet" href="../results/results.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
  <title>Results</title>
  <style>
     body{
      /* margin:0; */
      padding: 20px 0 !important;
      /* width: 100%; */
      min-height: 100svh;
      background-color: #d3d3d3;

    }
    .content1{
      width:95%;
    }
  .back-button {
    position: fixed;
    bottom: 20px;
    left: 20px;
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    z-index: 1000;
  }

  .back-button:hover {
    background-color: #0056b3;
  }
  </style>
</head>


<body>
  <div class="container">

    <div class="content" id="content">
      <div class="header" id="header">
        <!-- <h1>Lets look at the results of the election</h1>-->
      </div>
      <div class="overview" id="overview" style="display: none;"></div>
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
  <button id="backButton" class="back-button" onclick="window.location.href='../home/index.php'">← Back</button>
  <script>
    let oldHeaderDiv = `<h2 style="margin:0px;">Lets look at the results of the election</h2>`;
    let oldOverviewDiv = `<div id="election-info">
          <h3 id="election-name"></h3>
          <p id="startTime"></p>
          <p id="endTime"></p>
        </div>`;
    let oldContentDiv = `
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
        `;


    let headerDiv = document.getElementById('header');
    let overviewDiv = document.getElementById('overview');
    let content1Div = document.getElementById("content1");

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
    setInterval(fetchVotingTime, 2000); // Fetch every 2 seconds (3000 milliseconds)


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

      xhr.onload = function () {
        if (xhr.status === 200) {
          const response = JSON.parse(xhr.responseText);
          if (response.status === "success") {
            const results = response.data;
            const container = document.getElementById('old-results');

            // Clear previous content
            container.innerHTML = "";

            if (results.length > 0) {
              // Sort results by total votes (descending order)
              results.sort((a, b) => b.totalVotes - a.totalVotes);

              // Calculate total votes
              const totalVotes = results.reduce((a, b) => parseInt(a) + parseInt(b.totalVotes), 0);

              // Extract top 3 and others
              let topCandidates = results.slice(0, 3);
              let othersVotes = results.slice(3).reduce((sum, candidate) => parseInt(sum) + parseInt(candidate.totalVotes), 0);

              // Prepare data for chart
              let chartLabels = [...topCandidates.map(c => c.name), "Others"];
              let chartData = [...topCandidates.map(c => c.totalVotes), othersVotes];
              let chartColors = [...topCandidates.map(c => c.partyThemeColor), "#000000"]; // Custom colors

              // 🎖️ Top 3 Candidates with Donut Chart 🎖️
              const top3Container = document.createElement('div');
              top3Container.className = "top-three-container";

              // Chart Container
              const chartContainer = document.createElement('div');
              chartContainer.className = "chart-container";
              chartContainer.innerHTML = `<canvas id="topCandidatesChart" height="250"></canvas>`;

              // Percentage List
              const percentageList = document.createElement('div');
              percentageList.className = "percentage-list";
              percentageList.innerHTML = `
                <h3>Results Breakdown</h3>
                ${topCandidates.map((c, index) => {
                            let electedTag = ((index === 0)&&(district!='default' && regionNo!='default'&& !searchQuery)) ? ' 🏆 (Elected)' : '';
                            return `<p>${c.name}: ${(c.totalVotes / totalVotes * 100).toFixed(2)}%${electedTag}</p>`;
                          }).join("")}
                <p>Others: ${(othersVotes / totalVotes * 100).toFixed(2)}%</p>
            `;

              top3Container.appendChild(chartContainer);
              top3Container.appendChild(percentageList);
              container.appendChild(top3Container);
              // Initialize Chart after DOM is updated
              setTimeout(() => {
                var ctx = document.getElementById('topCandidatesChart').getContext('2d');
                new Chart(ctx, {
                  type: 'doughnut',
                  data: {
                    labels: chartLabels,
                    datasets: [{
                      data: chartData,
                      backgroundColor: chartColors
                    }]
                  },
                  options: {
                    responsive: true,
                    cutout: '50%',
                    plugins: {
                      legend: {
                        labels: {
                          boxWidth: 10, // Reduce the width of color boxes in the legend
                          color: 'black', // Change legend text color
                          // font: {
                          //   size: 14 // Change legend font size
                          // }
                        },
                        position: 'bottom'
                      },
                      tooltip: {
                        callbacks: {
                          label: function (tooltipItem) {
                            let value = tooltipItem.raw;
                            let percentage = ((value / totalVotes) * 100).toFixed(2);
                            return `${tooltipItem.label}: ${percentage}%`;
                          }
                        }
                      }, datalabels: {
                        color: 'white', // Change this to any color (e.g., 'black', '#FF0000', etc.)
                        font: {
                          size: 14, // Adjust font size
                          weight: 'bold' // Make it bold if needed
                        },

                      }
                    }
                  },
                  plugins: [ChartDataLabels] // Enable datalabels
                });
              }, 200);


              // 📊 Candidate List with Votes 📊
              const progressContainer = document.createElement('div');
              progressContainer.className = "progress-container";
              const overallProgressHeading = document.createElement('h3');
              overallProgressHeading.innerHTML = "All candidates results";
              overallProgressHeading.style.width = "100%";
              progressContainer.appendChild(overallProgressHeading);

              results.forEach((result, index) => {
                let isElected = ((index === 0)&&(district!='default' && regionNo!='default'&& !searchQuery)); // First candidate in sorted list is elected

                const rowDiv = document.createElement('div');
                rowDiv.className = "progress-row";
                if (isElected) {
                  rowDiv.style.paddingInline = "10px";
                  rowDiv.style.border = "2px solid gold"; // Highlight elected candidate
                  rowDiv.style.backgroundColor = "#fffae5"; // Light yellow background
                }

                rowDiv.innerHTML = `
                    <img src="../uploads/${result.candidate_photo}" alt="Candidate" class="candidate-img-small">
                    <div class="progress-wrapper">
                        <p style="${isElected ? 'font-weight:bold;color:green;' : ''}">${result.name} ${isElected ? '🏆 (Elected)' : ''}</p>
                        <div class="progress-bar">
                            <div class="progress" style="background-color:${result.partyThemeColor};width: ${(result.totalVotes / totalVotes * 100)}%;"></div>
                        </div>
                        <p class="votes">${result.totalVotes} votes ${isElected ? '🏆' : ''}</p>
                    </div>
                    <img src="../uploads/${result.partyLogo}" alt="Party" class="party-img-small">
                `;

                progressContainer.appendChild(rowDiv);
              });

              container.appendChild(progressContainer);
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




    // let electionNotScheduled = false;
    // let votingNotStarted = false;
    // let votingStarted = false;
    let isPublishedChecked = false;
    let isNotPublishedChecked = false;
    let resultPublished = false;
    let electionNotEnded = false;

    function checkVotingTime() {
      resultPublished = (votingTime.resultStatus == 'published') ? true : false;
      // let votingStartTime=votingT
      let currentTime = new Date().getTime();
      let votingStartTime = new Date(votingTime.startTime).getTime();
      let votingEndTime = new Date(votingTime.endTime).getTime();
      if (!isPublishedChecked && resultPublished) {
        isPublishedChecked = true;
        isNotPublishedChecked = false;
        electionNotEnded = false
        console.log('a');
        // electionNotScheduled = votingNotStarted = votingStarted = false;
        showResultPublished();
      } else if (!resultPublished && !isNotPublishedChecked && currentTime > votingEndTime) {
        isPublishedChecked = false;
        isNotPublishedChecked = true;
        electionNotEnded = false;
        console.log('b');
        showResultNotPublished();
      } else if (!electionNotEnded && currentTime < votingEndTime) {
        // isPublishedChecked=false;
        isNotPublishedChecked = false;
        electionNotEnded = true;
        console.log('c');
        showElectionNotEnded();
      } else if (votingTime.error) {
        electionNotScheduled();
        // alert("No election scheduled or conducted till now. PLease come back later");
        // window.location.href = "../home/home.php";
      }
    }

    function showResultPublished() {
      // Add special classes for styling
      headerDiv.className = 'header';
      content1Div.className = 'content1';
      headerDiv.id = 'header';
      content1Div.id = 'content1';

      //display overview div
      overviewDiv.innerHTML = oldOverviewDiv;
      overviewDiv.style.display = 'block';

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

      //dispay the overview with chart for own 
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

    // Election not scheduled function
    function electionNotScheduled() {
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
      window.location.href = '../home/home.php';
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