<!-- candidates.php -->
<?php

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Candidates Page</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../candidates/candidates.css">
  <style>
    body{
      margin:0;
      padding: 20px 0;
      width: 100%;
      min-height: 100svh;
      background-color: #d3d3d3;

    }
    .container {
      /* display: flex;
      justify-content: center;
      align-items: start;
       */
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

<div class="container">
  <div class="content" id="content">
    <button id="open-search" class="inactive">Open Search Area</button>
    <form onsubmit="event.preventDefault();" class="search-form">
      <div class="search-input-container">
        <input type="text" id="searchQuery" placeholder="Search by name" class="search-input">
        <i class="fas fa-search" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);"></i>
      </div>
      <?php require_once '../php_for_ajax/districtRegionSelect.php';
      district();
      regionNo();
      ?>
      <div class="search-input" id="view-all-checkbox" style=" align-content:center">
        <input type="checkbox" name="all" id="all" value="all" style="margin: 0;"><span style="font-size: 12px;">View
          all</span>
      </div>
    </form>

    <!-- Modal Structure for image-->
    <div id="imageModal" class="modal all-modals">
      <img class="modal-content" id="modalImage">
      <div id="caption"></div>
    </div>

    <h1 id="h1-candidates">View All the candidates present in this election</h1>
    <div class="candidates" id="candidates">
    <?php
      require_once '../register_and_login/dbconnection.php';

      // $dId = $_SESSION['dId'];
      // $dId = 1; // For testing purposes, set the district ID to 1
      $query = "SELECT candidates.*, parties.partyName,parties.partyLogo, district.district, district.regionNo 
                FROM candidates 
                JOIN parties ON candidates.partyId = parties.partyId 
                JOIN district ON candidates.dId = district.dId";
      $result = mysqli_query($conn, $query);

      if (mysqli_num_rows($result) > 0) {
        ?>
        <?php
        while ($row = mysqli_fetch_assoc($result)) { ?>
          <div class="candidate">
            <div class="personal-details">
            <div class="photo"><img src="../uploads/<?= $row['candidate_photo'] ?>" alt="<?= $row['name'] ?>"
                class="candidate-photo"></div>
            <div class="candidate-info">
              <h3><?= $row['name'] ?></h3>
              <p><span class="title">Date of birth</span>: <?= $row['dob'] ?></p>
              <p><span class="title">Gender</span>: <?= $row['gender'] ?></p>
              <p><span class="title">Constituency</span>: <?= $row['district'] ?>- <?= $row['regionNo'] ?> </p>
              <p><span class="title">Education Level</span>: <?= $row['education_level'] ?></p>
            </div>
            </div>
            <div class="manifesto">
              <h3>Manifesto</h3>
              <p><?=  nl2br(htmlspecialchars($row["manifesto"])); ?></p>
            </div>
            <div class="party-info">
              <h3>Party Information</h3>
              <p><?= $row['partyName'] ?></p>
              <img src="../uploads/<?= $row['partyLogo'] ?>" alt="Party Logo">
            </div>
          </div>
        <?php }
      } else if (mysqli_num_rows($result) == 0) {
        echo '<p>Currently there are no candidates available for you constituency.</p>';
      } else {
        echo '<p>No candidates found</p>';
      }
      mysqli_close($conn);
      ?>
    </div>
    <button id="goToTop" class="go-to-top" title="Go to Top" onclick="scrollToTop()">↑</button>
    <button id="backButton" class="back-button" onclick="window.location.href='../home/index.php'">← Back</button>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', searchCandidates);
  // Function to attach event listeners to candidate photos
  function attachModalEventListeners() {
    var modal = document.getElementById("imageModal");
    var modalImg = document.getElementById("modalImage");
    var captionText = document.getElementById("caption");

    document.querySelectorAll('.candidate-photo').forEach(function (img) {
      img.onclick = function () {
        modal.style.display = "flex";
        modalImg.src = this.src;
        captionText.innerHTML = this.alt;
      }
    });
    // Close the modal when clicking outside of the modal content
    window.onclick = function (event) {
      var modals = document.getElementsByClassName('all-modals');
      for (var i = 0; i < modals.length; i++) {
        if (event.target == modals[i]) {
          modals[i].style.display = 'none';
        }
      }
    }
  }
  attachModalEventListeners();

  const selects = document.getElementsByTagName("select");
  for (let i = 0; i < selects.length; i++) {
    selects[i].classList.add('search-input');
  }

  const toggleButton = document.getElementById('open-search');
  toggleButton.addEventListener('click', () => {
    if (toggleButton.classList.contains('inactive')) {
      toggleButton.classList.remove('inactive');
      toggleButton.classList.add('active');
      toggleButton.textContent = 'Close Search Area';
      document.querySelector('.search-form').style.display = 'flex';
    } else {
      toggleButton.classList.remove('active');
      toggleButton.classList.add('inactive');
      toggleButton.textContent = 'Open Search Area';
      document.querySelector('.search-form').style.display = 'none';
    }
  });
  //ajax for searching candidates by name, district and region
  function searchCandidates() {
    var searchQuery = document.getElementById('searchQuery').value;
    var district = document.getElementById('district').value;
    var regionNo = document.getElementById('regionNo').value;
    if (document.getElementById('all').checked) {
      searchQuery = '1';
    }
    if (searchQuery == '' && district == 'default' && regionNo == 'default' || searchQuery == '' && district == 'default') {
      resetResults();
    } else {
      var xhr = new XMLHttpRequest();
      xhr.open('GET', '../candidates/getCandidates.php?searchQuery=' + encodeURIComponent(searchQuery) + '&district=' + encodeURIComponent(district) + '&regionNo=' + encodeURIComponent(regionNo), true);
      xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
          document.getElementById('h1-candidates').textContent = "All Candidates"
          var response = JSON.parse(xhr.responseText);
          candidatesDiv = document.getElementById('candidates');
          candidatesDiv.innerHTML = '';
          // alert(xhr.responseText);
          if (response.length > 0) {
            response.forEach(function (candidate) {
              var candidateDiv = document.createElement('div');
              candidateDiv.className = 'candidate';
              candidateDiv.innerHTML = `
                    <div class="personal-details">
                      <div class="photo"><img src="../uploads/${candidate.candidate_photo}" alt="${candidate.name}" class="candidate-photo"></div>
                      <div class="candidate-info">
                        <h3>${candidate.name}</h3>
                        <p><span class="title">Date of birth</span>: ${candidate.dob}</p>
                        <p><span class="title">Gender</span>: ${candidate.gender}</p>
                        <p><span class="title">Constituency</span>: ${candidate.district} - ${candidate.regionNo}</p>
                        <p><span class="title">Education Level</span>: ${candidate.education_level}</p>
                      </div>
                    </div>
                    <div class="manifesto">
                      <h3>Manifesto</h3>
                      <p>${candidate.manifesto}</p>
                    </div>
                    <div class="party-info">
                      <h3>Party Information</h3>
                      <p>${candidate.partyName}</p>
                      <img src="../uploads/${candidate.partyLogo}" alt="Party Logo">
                    </div>
                  `;
              candidatesDiv.appendChild(candidateDiv);
              attachModalEventListeners();
            });
          } else {
            candidatesDiv.innerHTML = `<p>No candidates found</p>`;
          }
        }
      };
      xhr.send();
    }
  }

  // let initialResults = '';
  const initialResults = document.getElementById('candidates').innerHTML;// Get the initial results
  document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('searchQuery').addEventListener('input', function () {
      searchCandidates();
    });
    document.getElementById('district').addEventListener('change', function () {
      setTimeout(searchCandidates, 100);
    });
    document.getElementById('regionNo').addEventListener('change', function () {
      searchCandidates();
    });
    document.getElementById('all').addEventListener('change', function () {
      searchCandidates();
    });
  });
  function resetResults() {
    var searchQuery = document.getElementById('searchQuery').value;
    var district = document.getElementById('district').value;
    var regionNo = document.getElementById('regionNo').value;

    if (searchQuery === '' && district === 'default' && regionNo === 'default') {
      document.getElementById('candidates').innerHTML = initialResults;
    }
  }

  const contentContainer = document.getElementById("content");
  const goToTopButton = document.getElementById("goToTop");

  // Show/hide the Go to Top button based on scroll position of the content container
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
</script>
</body>

</html>