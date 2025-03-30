<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../admin/admin_login.php');
    exit;
}
$_SESSION['pageName'] = "Manage Candidates";
require_once "../php_for_ajax/districtRegionSelect.php";
require_once "../home/logout_modals_html.php";
require_once "../register_and_login/dbconnection.php";

$errorMessage = isset($_SESSION['errorMsg']) ? $_SESSION['errorMsg'] : '';
unset($_SESSION['errorMsg']); // Clear the message
$successMessage = isset($_SESSION['successMsg']) ? $_SESSION['successMsg'] : '';
unset($_SESSION['successMsg']); // Clear the message
$candidateId = $name = $dob = $gender = $citizenshipNumber = $educationLevel = $manifesto = $partyName = $district = $regionNo = $candidatePhoto = "";

if (isset($_GET['id'])) {
    $candidateId = $_GET['id'];
    $sql = "SELECT candidates.*, parties.partyName, district.district, district.regionNo 
            FROM candidates 
            JOIN parties ON candidates.partyId = parties.partyId 
            JOIN district ON candidates.dId = district.dId
            WHERE candidateId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $candidateId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $name = htmlspecialchars($row['name']);
        $dob = htmlspecialchars($row['dob']);
        $gender = htmlspecialchars($row['gender']);
        $citizenshipNumber = htmlspecialchars($row['citizenship_number']);
        $educationLevel = htmlspecialchars($row['education_level']);
        $manifesto = htmlspecialchars($row['manifesto']);
        $partyName = htmlspecialchars($row['partyName']);
        $district = htmlspecialchars($row['district']);
        $regionNo = htmlspecialchars($row['regionNo']);
        $candidatePhoto = htmlspecialchars($row['candidate_photo']);
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Online Voting System</title>
    <link rel="stylesheet" href="../styles/modal1.css">
    <link rel="stylesheet" href="../admin/left_right_party_candidate.css">
    <link rel="stylesheet" href="../styles/table_img.css">
    <link rel="icon" href="../images/DMP logo.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Form Layout */
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 10px;
        }

        .form-group {
            flex: 1;
            position: relative;
            margin-bottom: 0;
        }

        .form-group.full-width {
            flex: 0 0 100%;
        }

        /* Icon Positioning Fix */
        .form-group .input-container {
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 500;
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #7f8c8d;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
            outline: none;
        }

        input:disabled,
        select:disabled,
        textarea:disabled,
        button:disabled {
            background-color: #f5f5f5;
            cursor: not-allowed;
        }

        .candidate-form-modal {
            display: none;
            /* Initially hidden */
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .candidate-form-modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            position: relative;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .candidate-form-modal-close-button {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 18px;
            cursor: pointer;
        }

        #loading-screen {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 44px;
            position: absolute;
            bottom: 0;
            left: 0;
            top: 0px;
            right: 0;
            z-index: 100;
            width: 100%;
            height: 100%;
            color: red;
            background-color: transparent;
            transition: all 0.3s ease-in out;
        }

        .loading-indicator {
            width: 70px;
            height: 70px;
            border: 6px solid rgba(0, 0, 0, 0.2);
            /* Light border */
            /* border-top: 6px solid    #3498db; */
            border-top: 6px solid #007bff;
            /* Blue border */
            border-radius: 50%;
            animation: spin 1s linear infinite;
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

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .form-group {
                margin-bottom: 25px;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {}
    </style>
    <script src="../js/errorMessage_modal1.js"></script>
</head>

<body style="
  font-family: 'Poppins', sans-serif;">
    <?php require_once 'admin_navbar.php'; ?>
    <div class="content" id="content">
        <?php require_once '../home/logout_modals_html.php';
        logoutModalPhp("admin"); ?>
        <div id="modal1" class="modal-overlay1 all-modals">
            <div class="modal-content1">
                <p id="modalMessage1"></p>
                <button onclick="closeModal1()">Close</button>
            </div>
        </div>

        <div class="main-container">
            <div class="left">
                <button id="addBtn" onclick="showForm()">Add Candidates </button>
                <button id="viewBtn" onclick="showData()">View Candidates </button>
                <div id="displayNominationTime" style="position: relative;
                bottom:0;left:20"></div>
            </div>
            <div class="right">
                <div id="loading-screen">
                    <div class="loading-indicator"></div>
                </div>
                <div id="right1" class="right-content" style="display: none;">
                    <form id="addCandidateForm"
                        action="add_candidates_controller.php<?= isset($candidateId) ? '?id=' . $candidateId : '' ?>"
                        method="POST" enctype="multipart/form-data" onsubmit="event.preventDefault();">
                        <h2 id="formTitle"><?= $candidateId != '' ? 'Update Candidate' : 'Add Candidates' ?></h2>
                        <input type="hidden" name="candidateId" id="candidateId" value="<?= $candidateId ?>">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <div class="input-container">
                                    <i class="fas fa-user"></i>
                                    <input type="text" id="name" name="name" value="<?= $name ?>">
                                </div>
                                <span id="nameError"></span>
                            </div>

                            <div class="form-group">
                                <label for="dob">Date of Birth</label>
                                <div class="input-container">
                                    <i class="fas fa-calendar"></i>
                                    <input type="date" id="dob" name="dob" value="<?= $dob ?>">
                                </div>
                                <span id="dobError"></span>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <div class="input-container">
                                    <i class="fas fa-venus-mars"></i>
                                    <select id="gender" name="gender">
                                        <option value="default">--Select Gender--</option>
                                        <option value="Male" <?= $gender == 'male' ? 'selected' : '' ?>>Male</option>
                                        <option value="Female" <?= $gender == 'female' ? 'selected' : '' ?>>Female</option>
                                        <option value="Other" <?= $gender == 'other' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>
                                <span id="genderError"></span>
                            </div>

                            <div class="form-group">
                                <label for="citizenship_number">Citizenship Number</label>
                                <div class="input-container">
                                    <i class="fas fa-phone"></i>
                                    <input type="text" id="citizenship_number" name="citizenship_number"
                                        value="<?= $citizenshipNumber ?>">
                                </div>
                                <span id="citizenshipNumberError"></span>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="education_level">Education Level</label>
                                <div class="input-container">
                                    <i class="fas fa-graduation-cap"></i>
                                    <input type="text" id="education_level" name="education_level"
                                        value="<?= $educationLevel ?>">
                                </div>
                                <span id="educationLevelError"></span>
                            </div>

                            <div class="form-group">
                                <label for="partyName">Party Name</label>
                                <div class="input-container">
                                    <i class="fas fa-flag"></i>
                                    <select id="partyName" name="partyName">
                                        <option value="default">-- Select Party --</option>
                                        <?php
                                        $data = [];
                                        // Fetch party names
                                        $sql = "SELECT partyName FROM parties";
                                        $result = mysqli_query($conn, $sql);

                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $data[] = $row['partyName'];
                                                echo '<option value="' . $row['partyName'] . '"' . ($partyName == $row['partyName'] ? ' selected' : '') . '>' . htmlspecialchars($row['partyName']) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <span id="partyNameError"></span>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="district">District</label>
                                <div class="input-container">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?= $district == '' ? district() : district($district) ?>
                                </div>
                                <span id="districtError"></span>
                            </div>

                            <div class="form-group">
                                <label for="regionNo">Region</label>
                                <div class="input-container">
                                    <i class="fas fa-globe"></i>
                                    <?= $regionNo == '' ? regionNo() : regionNo($regionNo) ?>
                                </div>
                                <span id="regionError"></span>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group full-width">
                                <label for="manifesto">Manifesto</label>
                                <div class="input-container">
                                    <i class="fas fa-file-alt"></i>
                                    <textarea id="manifesto" name="manifesto" rows="4"><?= $manifesto ?></textarea>
                                </div>
                                <span id="manifestoError"></span>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group full-width">
                                <label for="candidate_photo">Candidate Photo</label>
                                <div class="input-container">
                                    <i class="fas fa-camera"></i>
                                    <input type="file" id="candidate_photo" name="candidate_photo" accept="image/*">
                                </div>
                                <div class="photo-preview" id="photoPreview">
                                    <?php if ($candidatePhoto != ''): ?>
                                        <img src="../uploads/<?= $candidatePhoto ?>" alt="Preview">
                                    <?php else: ?>
                                        <i class="fas fa-user-circle fa-3x"></i>
                                    <?php endif; ?>
                                </div>
                                <span id="candidatePhotoError"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit"
                                id="submitButton"><?= $candidateId != '' ? 'Update Candidate' : 'Add Candidate' ?></button>
                        </div>
                    </form>

                </div>
                <div id="right2" class="right-content" style="display: none;">
                    <h2>View Candidates</h2>
                    <form onsubmit="event.preventDefault();" class="search-form">
                        <div class="search-input-container">
                            <input type="text" id="searchQuery" placeholder="Search by name" class="search-input">
                            <i class="fas fa-search"
                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);"></i>
                        </div>
                        <select id="districtSearch" class="search-input">
                            <option value="">Select District</option>
                            <?php
                            $districts = [
                                "Bhaktapur",
                                "Chitwan",
                                "Dhading",
                                "Dolakha",
                                "Kathmandu",
                                "Kavrepalanchok",
                                "Lalitpur",
                                "Makwanpur",
                                "Nuwakot",
                                "Ramechhap",
                                "Rasuwa",
                                "Sindhuli",
                                "Sindhupalchok"
                            ];
                            foreach ($districts as $district) {
                                echo '<option value="' . htmlspecialchars($district) . '">' . htmlspecialchars($district) . '</option>';
                            }
                            ?>
                        </select>
                        <select id="partySearch" class="search-input">
                            <option value="">Select Party</option>
                            <?php foreach ($data as $party): ?>
                                <option value="<?= htmlspecialchars($party) ?>"><?= htmlspecialchars($party) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                    <div class="table-container">
                        <table border="1">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Date of Birth</th>
                                    <th>Gender</th>
                                    <th>Citizenship Number</th>
                                    <th>Education Level</th>
                                    <th>Party Name</th>
                                    <th>District</th>
                                    <th>Region</th>
                                    <th>Photo</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="results">
                            </tbody>
                            <tbody id="pre-fetched-data">
                                <?php
                                require_once '../register_and_login/dbconnection.php';
                                $sql2 = "SELECT candidates.*, parties.partyName, district.district, district.regionNo 
                                         FROM candidates 
                                         JOIN parties ON candidates.partyId = parties.partyId 
                                         JOIN district ON candidates.dId = district.dId
                                         ORDER BY candidateId ASC";
                                $result2 = mysqli_query($conn, $sql2);
                                if (mysqli_num_rows($result2) > 0) {
                                    while ($row = mysqli_fetch_assoc($result2)) {

                                        ?>
                                        <tr>
                                            <td> <?= htmlspecialchars($row['candidateId']) ?> </td>
                                            <td> <?= htmlspecialchars($row['name']) ?> </td>
                                            <td> <?= htmlspecialchars($row['dob']) ?> </td>
                                            <td> <?= htmlspecialchars($row['gender']) ?> </td>
                                            <td> <?= htmlspecialchars($row['citizenship_number']) ?> </td>
                                            <td> <?= htmlspecialchars($row['education_level']) ?> </td>
                                            <td> <?= htmlspecialchars($row['partyName']) ?> </td>
                                            <td> <?= htmlspecialchars($row['district']) ?> </td>
                                            <td> <?= htmlspecialchars($row['regionNo']) ?> </td>
                                            <td><img src="../uploads/<?= htmlspecialchars($row['candidate_photo']) ?>"
                                                    onclick="openModal(<?= $row['candidateId'] ?>,'<?= htmlspecialchars($row['candidate_photo']) ?>')"
                                                    alt='Candidate Photo' style='max-width: 100px;'></td>
                                            <td>
                                                <button class="delete-btn styled-btn"
                                                    onclick="openDeleteModal(<?= $row['candidateId'] ?>)">Delete</button>
                                                <button class="update-btn styled-btn"
                                                    onclick="window.location.href='add_candidates.php?id=<?= $row['candidateId'] ?>'">Edit</button>
                                            </td>
                                        </tr>
                                        <div id="delete-modal-<?= $row['candidateId'] ?>" class="delete-modal all-modals">
                                            <div class="delete-modal-content">
                                                <p>Are you sure you want to delete this candidate?</p>
                                                <button class="delete-modal-btn confirm-btn"
                                                    onclick="confirmDelete(<?= $row['candidateId'] ?>,'candidates','<?= $row['candidate_photo'] ?>')">Yes</button>
                                                <button class="delete-modal-btn cancel-btn"
                                                    onclick="closeDeleteModal(<?= $row['candidateId'] ?>)">No</button>
                                            </div>
                                        </div>
                                        <div id="modal-<?= $row['candidateId'] ?>" class="modal all-modals">
                                            <button class="close-modal"
                                                onclick="closeModal(<?= $row['candidateId'] ?>)">&times;</button>
                                            <div class="modal-content img-modal">
                                                <h3 id="modal-title-<?= $row['candidateId'] ?>"
                                                    style="color: Black; text-align: center;"></h3>
                                                <img id="modal-img-<?= $row['candidateId'] ?>" src="" alt="Selected Image">
                                            </div>
                                        </div>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan='10'>No candidates found.</td>
                                    </tr>
                                <?php }
                                mysqli_close($conn);
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <button id="goToTop" class="go-to-top" title="Go to Top" onclick="scrollToTop()">↑</button>
    <script>
        //set the max date 25 years from before for the age of the candidate
        async function setMinDOB() {
            // const loader = document.getElementById("loading"); // Reference the loader
            //   loader.style.display = "block"; // Show the loader
            let dob=document.getElementById("dob");
            dob.disabled=true;

            try {
                let response = await fetch("https://timeapi.io/api/Time/current/zone?timeZone=Asia/Kathmandu");
                let apiDate = await response.json();

                let currentDate = new Date(apiDate.dateTime);
                currentDate.setFullYear(currentDate.getFullYear() - 25);

                document.getElementById("dob").setAttribute("max", currentDate.toISOString().split("T")[0]);
            } catch (error) {
                console.error("Failed to fetch time from API. Falling back to local time.");

                // // Fallback to local time
                // let today = new Date();
                // today.setFullYear(today.getFullYear() - 18);
                // let formattedDate = today.toISOString().split("T")[0];

                // document.getElementById("dateOfBirth").setAttribute("max", formattedDate);
            } finally {
                // loader.style.display = "none"; // Hide the loader
                dob.disabled=false;

            }
        }

        document.addEventListener("DOMContentLoaded", setMinDOB);

        // Global variable to store the voting status
        let votingTime = {};
        let previousNominationStart = null;
        let previousNominationEnd = null;
        let nominationCheckInterval = null;
        let nominationTimeChecked = false; // Flag to ensure checkNominationTime is called only once

        // Function to fetch voting status from the server
        function fetchVotingTime() {
            let xhr = new XMLHttpRequest();
            xhr.open('GET', '../time/fetch_voting_time.php', true);
            xhr.onload = function () {
                if (this.status === 200) {
                    let newVotingTime = JSON.parse(this.responseText);
                    if (previousNominationStart !== new Date(newVotingTime.nominationStartTime).getTime() ||
                        previousNominationEnd !== new Date(newVotingTime.nominationEndTime).getTime()) {
                        votingTime = newVotingTime;
                        checkNominationTime();
                    }
                    previousNominationStart = new Date(newVotingTime.nominationStartTime).getTime();
                    previousNominationEnd = new Date(newVotingTime.nominationEndTime).getTime();
                }
            };
            xhr.send();
        }

        window.onload = function () {
            fetchVotingTime();
        };

        // Automatically fetch the voting status every 10 seconds
        setInterval(fetchVotingTime, 1000); // Fetch every 10 seconds (10000 milliseconds)

        // check time every 1 sec
        document.addEventListener('DOMContentLoaded', function () {
            if (!votingTime.startTime) {
                setTimeout(() => {
                    document.getElementById('loading-screen').style.display = 'none';
                    // document.getElementById('right1').style.display = 'block';
                    // checkNominationTime();
                }, 1000);
            }
        });

        // Function for making input fields disabled
        function disableFormFields(disable) {
            let form = document.getElementById('addCandidateForm');
            let fields = form.querySelectorAll('input, select, textarea');
            fields.forEach(field => {
                field.disabled = disable;
            });
        }

        function makeDisabled(disableOrNot) {
            if (disableOrNot) {
                document.getElementById('right1').style.display = 'block';
                disableFormFields(true);
                document.querySelectorAll('.form-group span').forEach(span => span.textContent = '');
            } else {
                disableFormFields(false);
            }
        }

        let noElectionScheduledChecked = false;
        // Function for checking nomination time
        function checkNominationTime(fromsubmit = false) {
            // console.log(votingTime);
            // Parse the current time and nomination times as timestamps
            let currentTime = new Date().getTime();
            let nominationStartTime = new Date(votingTime.nominationStartTime).getTime();
            let nominationEndTime = new Date(votingTime.nominationEndTime).getTime();

            // Get the form element
            let form = document.getElementById('addCandidateForm');

            if (!votingTime.error) {
                // Check nomination time conditions
                if (currentTime < nominationStartTime) {
                    // Nomination time has not started
                    showCandidateFormModal('Election nomination date and time is yet to come.');
                    makeDisabled(true);
                    return false; // Prevent form submission
                } else if (currentTime > nominationEndTime) {
                    // Nomination time has ended
                    // showCandidateFormModal('The candidate nomination date and time is over. Please head over to the candidates list.');
                    // makeDisabled(true);
                    document.getElementById('right1').style.display = 'none';
                    console.log('election ended');
                    showCandidateFormModal('The candidate nomination date and time is over. Please head over to the candidates list.');
                    electionEnded = true;
                    return false; // Prevent form submission
                } else {
                    document.getElementById('loading-screen').style.display = 'none';
                    document.getElementById('right1').style.display = 'block';
                    document.getElementById('right2').style.display = 'none';
                    makeDisabled(false); // Enable the form when nomination time is valid

                    if (fromsubmit) {
                        return validateForm(); // Allow form submission if validation passes
                    }
                    return false; // Prevent form submission if not triggered by the submit button
                }
            } else {
                // if (noElectionScheduledChecked == false) {
                //     showCandidateFormModal('No upcoming election found. Please ensure that you have scheduled any election.');
                //     // makeDisabled(true);
                //     noElectionScheduledChecked = true;
                // }
                return false; // Prevent form submission
            }
        }

        // Real-time nomination time check
        let electionEnded = false;
        setInterval(() => {
            let currentTime = new Date().getTime();
            let nominationStartTime = new Date(votingTime.nominationStartTime).getTime();
            let nominationEndTime = new Date(votingTime.nominationEndTime).getTime();

            if (currentTime >= nominationStartTime && currentTime <= nominationEndTime) {
                // If within nomination time
                makeDisabled(false);
            } else {
                // If outside mainly nomination time
                if (currentTime < nominationStartTime) {
                    makeDisabled(true);
                } else if (currentTime > nominationEndTime) {
                    if (!electionEnded) {
                        document.getElementById('right1').style.display = 'none';
                        console.log('election ended');
                        showCandidateFormModal('The candidate nomination date and time is over. Please head over to the candidates list.');
                        electionEnded = true;
                    }

                } else if (votingTime.error) {
                    if (noElectionScheduledChecked == false) {
                        showCandidateFormModal('No upcoming election found. Please ensure that you have scheduled any election.');
                        // makeDisabled(true);
                        noElectionScheduledChecked = true;
                    }
                    // showCandidateFormModal('No upcoming election found. Please ensure that you have scheduled any election.');
                }
            }
        }, 1000); // Check every second

        // Function to show a modal
        function showCandidateFormModal(message) {
            let modal = document.createElement('div');
            modal.classList.add('candidate-form-modal');
            modal.innerHTML = `
                <div class="candidate-form-modal-content">
                    <span class="candidate-form-modal-close-button">&times;</span>
                    <p>${message}</p>
                </div>
                `;
            modal.style.display = 'flex';
            document.getElementById('content').appendChild(modal);

            // Close the modal
            modal.querySelector('.candidate-form-modal-close-button').onclick = function () {
                modal.remove();
                if (votingTime.error) {
                    window.location.href = '../admin/admin_home.php';
                }
                if (new Date(votingTime.nominationEndTime).getTime() < new Date().getTime()) {
                    showData();
                }
            };
        }
        window.onclick = function (event) {
            modal = document.querySelector('.candidate-form-modal');
            if (event.target === modal) {
                modal.remove();
                if (new Date(votingTime.nominationEndTime).getTime() < new Date().getTime()) {
                    showData();
                }
                if (votingTime.error) {
                    window.location.href = '../admin/admin_home.php';
                }
            }

            var modals = document.getElementsByClassName('all-modals');
            for (var i = 0; i < modals.length; i++) {
                if (event.target == modals[i]) {
                    modals[i].style.display = 'none';
                }
            }
        };
        // document.getElementById('go-to-candidate-list-button').onclick = function () {
        //     showData();
        //     modal.remove();
        // };

        //call the function to check nomination time when the page loads
        document.getElementById('addCandidateForm').addEventListener('submit', function () {
            checkNominationTime(true);
        }
        );

        function searchCandidates() {
            var searchQuery = document.getElementById('searchQuery').value;
            var district = document.getElementById('districtSearch').value;
            var party = document.getElementById('partySearch').value;
            if (searchQuery == '' && district == '' && party == '') {
                resetResults();
            } else {
                document.getElementById('results').style.display = 'table-row-group';
                document.getElementById('pre-fetched-data').style.display = 'none';
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'searchCandidates.php?searchQuery=' + encodeURIComponent(searchQuery) + '&district=' + encodeURIComponent(district) + '&party=' + encodeURIComponent(party), true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var response = JSON.parse(xhr.responseText);
                        var resultsDiv = document.getElementById('results');
                        resultsDiv.innerHTML = '';
                        // alert(xhr.responseText);
                        if (response.length > 0) {
                            response.forEach(function (candidate) {
                                var row = document.createElement('tr');
                                row.innerHTML = `
                                <td>${candidate.candidateId}</td>
                                <td>${candidate.name}</td>
                                <td>${candidate.dob}</td>
                                <td>${candidate.gender}</td>
                                <td>${candidate.citizenship_number}</td>
                                <td>${candidate.education_level}</td>
                                <td>${candidate.partyName}</td>
                                <td>${candidate.district}</td>
                                <td>${candidate.regionNo}</td>
                                <td><img src="../uploads/${candidate.candidate_photo}" onclick="openModal(${candidate.candidateId},'${candidate.candidate_photo}')" alt='Candidate Photo' style='max-width: 100px;'></td>
                        
                                <td>
                                    <button class="delete-btn styled-btn" onclick="openDeleteModal(${candidate.candidateId})">Delete</button>
                                    <button class="update-btn styled-btn" onclick="window.location.href='add_candidates.php?id=${candidate.candidateId}'">Edit</button>
                                </td>
                            `;
                                resultsDiv.appendChild(row);
                            });
                        } else {
                            var noResultsRow = document.createElement('tr');
                            noResultsRow.innerHTML = '<td colspan="11">No results found</td>';
                            resultsDiv.appendChild(noResultsRow);
                        }
                    }
                };
                xhr.send();
            }
        }
        function resetResults() {
            var searchQuery = document.getElementById('searchQuery').value;
            var district = document.getElementById('districtSearch').value;
            var party = document.getElementById('partySearch').value;

            if (searchQuery === '' && district === '' && party === '') {
                document.getElementById('results').style.display = 'none';
                document.getElementById('pre-fetched-data').style.display = 'table-row-group';
            }
        }
        window.onload = function () {
            document.getElementById('results').style.display = 'none';
            document.getElementById('pre-fetched-data').style.display = 'table-row-group';
        }
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('searchQuery').addEventListener('input', function () {
                searchCandidates();
            });
            document.getElementById('districtSearch').addEventListener('change', function () {
                searchCandidates();
            });
            document.getElementById('partySearch').addEventListener('change', function () {
                searchCandidates();
            });
        });

        // Show error modal if there's an error message
        let errorMessage = <?= json_encode($errorMessage); ?>;
        let successMessage = <?= json_encode($successMessage); ?>;
        if (errorMessage) {
            showErrorModal(errorMessage);
        } else if (successMessage) {
            showErrorModal(successMessage, true);
        }

        // Function to show the form (right1)
        function showForm() {
            window.location.href = '../admin/add_candidates.php';
        }

        // Function to show the data table (right2)
        function showData() {
            document.getElementById("right1").style.display = "none";  // Hide the form
            document.getElementById("right2").style.display = "block"; // Show the table
            // document.querySelectorAll('.right-content').forEach(element => {
            //     element.style.width = '95%';
            // });
        }

        // Initialize by hiding one of the sections (optional, depending on your default view)
        window.onload = function () {
            // document.getElementById("right1").style.display = "none"; // Initially hide the form
            document.getElementById("right2").style.display = "none"; // Initially hide the table
        };

        // Add this to your existing JavaScript
        document.getElementById('candidate_photo').addEventListener('change', function (e) {
            let preview = document.getElementById('photoPreview');
            let file = e.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                }
                reader.readAsDataURL(file);
            }
        });

        function validateForm() {
            let valid = true;
            let namePattern = /^[A-Za-z\s]{7,}$/;
            // Clear previous errors
            document.querySelectorAll('.form-group span').forEach(span => span.textContent = '');

            // Validate Name
            let name = document.getElementById('name').value;
            if (name.trim() === '') {
                document.getElementById('nameError').textContent = 'Name is required';
                valid = false;
            }else if (!namePattern.test(name.trim())){
                document.getElementById('nameError').textContent = 'Name must only contain alphabet characters';
                valid = false;
            }

            // Validate Date of Birth
            let dob = document.getElementById('dob').value;
            if (dob.trim() === '') {
                document.getElementById('dobError').textContent = 'Date of Birth is required';
                valid = false;
            } else {
                let dobDate = new Date(dob);
                let today = new Date();
                let age = today.getFullYear() - dobDate.getFullYear();
                let monthDiff = today.getMonth() - dobDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dobDate.getDate())) {
                    age--;
                }
                if (age < 25) {
                    document.getElementById('dobError').textContent = 'Candidate must be at least 25 years old';
                    valid = false;
                }
            }

            // Validate Gender
            let gender = document.getElementById('gender').value;
            if (gender.trim() === '' || gender.trim() === 'default') {
                document.getElementById('genderError').textContent = 'Gender is required';
                valid = false;
            }

            // Validate Citizenship Number
            let citizenshipNumber = document.getElementById('citizenship_number').value;
            if (citizenshipNumber.trim() === '') {
                document.getElementById('citizenshipNumberError').textContent = 'Citizenship Number is required';
                valid = false;
            }else if (isNaN(citizenshipNumber.trim())) {
                document.getElementById('citizenshipNumberError').textContent = 'Citizenship Number must be number';
                valid = false;
            }
            
            // Validate Education Level
            let educationLevel = document.getElementById('education_level').value;
            if (educationLevel.trim() === '') {
                document.getElementById('educationLevelError').textContent = 'Education Level is required';
                valid = false;
            }

            // Validate Manifesto
            let manifesto = document.getElementById('manifesto').value;
            if (manifesto.trim() === '') {
                document.getElementById('manifestoError').textContent = 'Manifesto is required';
                valid = false;
            }

            // Validate Party Name
            let partyName = document.getElementById('partyName').value;
            if (partyName.trim() === '' || partyName.trim() === 'default') {
                document.getElementById('partyNameError').textContent = 'Please select party';
                valid = false;
            }

            // Validate District
            let district = document.getElementById('district').value;
            if (district.trim() === '' || district.trim() === 'default') {
                document.getElementById('districtError').textContent = 'Please select district';
                valid = false;
            }

            // Validate Region
            let region = document.getElementById('regionNo').value;
            if (region.trim() === '' || region.trim() === 'default') {
                document.getElementById('regionError').textContent = 'Please Select region';
                valid = false;
            }

            // Validate Candidate Photo
            let allowedTypes = ['image/jpeg', 'image/png'];
            let maxSize = 2 * 1024 * 1024;
            let candidatePhoto = document.getElementById('candidate_photo').files[0];
            if (!candidatePhoto) {
                document.getElementById('candidatePhotoError').textContent = 'Candidate Photo is required';
                valid = false;
            }
            if (candidatePhoto && !allowedTypes.includes(candidatePhoto.type)) {
                document.getElementById('candidatePhotoError').textContent = 'Only JPG and PNG files are allowed';
                valid = false;
            }
            if (candidatePhoto && candidatePhoto.size > maxSize) {
                document.getElementById('candidatePhotoError').textContent = 'File size must be less than 2MB';
                valid = false;
            }

            // return valid;
            if (valid) {
                document.getElementById("addCandidateForm").submit();
            }
        }

        //image modal
        function openModal(id, title) {
            let modal = document.getElementById('modal-' + id);
            let modalImg = document.getElementById('modal-img-' + id);
            let modalTitle = document.getElementById('modal-title-' + id);

            // Determine the image to display
            let imageSrc = event.target.src;

            // Set modal content
            modalImg.src = imageSrc;
            modalTitle.innerText = title;

            // Show modal
            modal.style.display = 'flex';
        }

        function closeModal(id) {
            let modal = document.getElementById('modal-' + id);
            modal.style.display = 'none';

            // Clear the modal content
            let modalImg = document.getElementById('modal-img-' + id);
            modalImg.src = '';
        }
        //delete comfirmation modal
        function openDeleteModal(candidateId) {
            var modal = document.getElementById('delete-modal-' + candidateId);
            modal.style.display = 'flex';
        }

        function closeDeleteModal(candidateId) {
            var modal = document.getElementById('delete-modal-' + candidateId);
            modal.style.display = 'none';
        }

        // Close the modal when clicking outside of the modal content

        function confirmDelete(id, table, photoPath) {
            window.location.href = `delete_party_candidate.php?table=${table}&id=${id}&photoPath=${photoPath}`;
        }
        document.addEventListener('DOMContentLoaded', function () {
            let id = document.getElementById('candidateId').value;
            if (id) {
                let candidatePhotoInput = document.getElementById("candidate_photo");
                let candidatePhoto = `<?= $candidatePhoto ?>`;
                let filePath = `../uploads/${candidatePhoto}`;
                fetch(filePath)
                    .then(response => response.blob())
                    .then(blob => {
                        let file = new File([blob], candidatePhoto, { type: blob.type });
                        let dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        candidatePhotoInput.files = dataTransfer.files;
                    })
                    .catch(error => console.error('Error fetching the file:', error));
                document.getElementById("photoPreview").innerHTML = `<img src="../uploads/${candidatePhoto}" alt="Preview">`;
            }
        });
        let contentContainer = document.body;
        let goToTopButton = document.getElementById("goToTop");

        // Show/hide the Go to Top button based on scroll position of the window
        window.addEventListener("scroll", function () {
            if (window.scrollY > 100) {
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