<!-- candidates.php -->
<?php
session_start();
if (!isset($_SESSION["email"])) {
  header('Location:../home/index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Candidates Page</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    .content {
      padding-left: 40px !important;
    }

    .candidates {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .candidate {
      display: flex;
      align-items: start;
      justify-content: space-between;
      border: 1px solid #ccc;
      background-color: #f0f8ff;
      color: #333;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s, box-shadow 0.3s;
      /* width: calc(33.333% - 20px); */
      width: 100%;
      max-width: 850px;
      min-height: 180px;
      padding: 15px;
      gap: 15px;
    }

    .candidate:hover {
      /* transform: scale(1.015); */
      box-shadow: 0 0 40px rgba(127, 64, 230, 0.35);
    }
.personal-details{
  width:50%;
  display: flex;
  flex-direction: row;
  justify-content: space-around;
  gap: 15px;
}
    .photo {
      align-self: stretch;
      align-content: center;
    }

    .photo img {
      width: 140px;
      aspect-ratio: 1 / 1;
      object-position: center;
      object-fit: contain;
    }

    .candidates p {
      width: 100%;
      margin: 5px 0;
      word-wrap: break-word; /* Allows long words to break */
            overflow-wrap: break-word; /* Modern alternative for word wrapping */
            white-space: normal; /* Ensures the text wraps */
    }

    .candidate-info
    {
      max-width:50%;
      padding: 6px;
      font-family: 'Poppins', sans-serif;
    }
    .party-info,.manifesto {
      max-width:25%;
      padding: 6px;
      font-family: 'Poppins', sans-serif;
    }

    .candidate h3 {
      margin-top: 0;
      margin-bottom: 10px;
    }

    .party-info {
      justify-self: end;
    }

    .party-info img {
      max-width: 50px;
      aspect-ratio: 1/1;
      border-radius: 10px;
      object-fit: contain;
    }

    /* Search area styling */
    .search-form {
      display: none;
      gap: 15px;
      /* max-width: 450px; */
      margin: 10px auto;
      padding: 15px;
      border: 1px solid #ddd;
      border-radius: 8px;
      background-color: #fff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    #open-search {
      padding: 10px 20px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .search-input-container {
      align-content: center;
      position: relative;
      /* flex: 1 0 40%; */
      flex: 3.5;
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
      width: 15%;
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

    #view-all-checkbox {
      width: 12%;
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

    /* Modal styles */
    .modal {   
       display: none;
    flex-direction: column;
    justify-content: center;
    align-items: center;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.9);
    }

    .modal-content {
      background-color: rgb(228, 226, 231);
      display: block;
      max-width: 90%;
      /* min-width: 400px;
      height: 85%; */
    max-height: 85%;
      padding: 40px;
      overflow-x: auto;
      border-radius: 10px;
    }

    #caption {
      display: block;
      width: 80%;
      max-width: 700px;
      text-align: center;
      color: #ccc;
      padding: 10px 0;
    }

    @media screen and (max-width: 768px) {
      #h1-candidates{
        margin-left: 30px;
      }
      .candidates{
        align-items: center;
      }
      .candidate{
        flex-direction: column;
        padding-left: 40px;
        max-width: 95%;
      }
      .manifesto, .party-info{
        max-width: 90%;
      }
      .personal-details{
        width: 100%;
      }
      .photo, .candidate-info{
        width: 50%;
      }
      .candidate-info{
        order:-1;
      }
      
      .search-form {
        flex-wrap: wrap;
        /* gap: 10px; */
      }

      #open-search {
        position: relative;
        left: 60px;
        top: 5px;
      }

      .search-input-container,
      #district,
      #regionNo,
      #view-all-checkbox {
        /* flex-basis: calc(50% - 10px); */
        flex: 1 1 40%;
        ;
      }

    }
  </style>
</head>

<div class="container">
  <!-- Include sidebar -->
  <?php include '../home/sidebar.php'; ?>

  <!-- Add 'active' class to Candidates link -->
  <script>
    document.querySelector('a[href="../candidates/candidates.php"]').classList.add('active');
  </script>

  <div class="content" id="content">
    <button id="open-search" class="inactive">Open Search Area</button>
    <form onsubmit="event.preventDefault();" class="search-form">
      <div class="search-input-container">
        <input type="text" id="searchQuery" placeholder="Search by name" class="search-input">
        <i class="fas fa-search" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);"></i>
      </div>
      <?php require_once '../php_for_ajax/districtRegionSelect.php';
      district("$_SESSION[district]");
      regionNo("$_SESSION[election_region]");
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

    <h1 id="h1-candidates">Candidates from Your Constituency</h1>
    <div class="candidates" id="candidates">
      <?php
      require_once '../register_and_login/dbconnection.php';

      $dId = $_SESSION['dId'];
      // $dId = 1; // For testing purposes, set the district ID to 1
      $query = "SELECT candidates.*, parties.partyName,parties.partyLogo, district.district, district.regionNo 
                FROM candidates 
                JOIN parties ON candidates.partyId = parties.partyId 
                JOIN district ON candidates.dId = district.dId
                where candidates.dId = $dId";
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
              <p><?= $row['manifesto'] ?></p>
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
  </div>
</div>
<script>
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
      xhr.open('GET', 'getCandidates.php?searchQuery=' + encodeURIComponent(searchQuery) + '&district=' + encodeURIComponent(district) + '&regionNo=' + encodeURIComponent(regionNo), true);
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