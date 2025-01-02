<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../admin/admin_login.php');
    exit;
}
$errorMessage = isset($_SESSION['errorMsg']) ? $_SESSION['errorMsg'] : '';
unset($_SESSION['errorMsg']); // Clear the message
$fromLogIn = isset($_SESSION['fromLogIn']) ? $_SESSION['fromLogIn'] : '';
unset($_SESSION['fromLogIn']);

$_SESSION['pageName'] = "Admin-Dashboard";
require_once "../register_and_login/dbconnection.php";
require_once "../admin/total_rows_sql.php";
require_once "../home/logout_modals_html.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Online Voting System</title>
    <link rel="stylesheet" href="../admin/admin_home.css">
    <link rel="stylesheet" href="../styles/modal1.css">
    <link rel="icon" href="../images/DMP logo.png" type="image/x-icon">
    <style>

    </style>
    <script src="../js/errorMessage_modal1.js"></script>
</head>

<body>
    <!-- Welcome Message -->
    <div id="welcomeMessage" class="welcome-message">
        Welcome Admin! Manage your elections effectively.
        <button id="dismissButton"
            style="margin-left: 20px; background: white; color: #4CAF50; border: none; padding: 5px 10px; cursor: pointer; border-radius: 5px;">
            Dismiss
        </button>
    </div>

    <div id="modal1" class="modal-overlay1 all-modals">
        <div class="modal-content1">
            <p id="modalMessage1"></p>
            <button onclick="closeModal1()">Close</button>
        </div>
    </div>
    <?php require '../admin/admin_navbar.php';

    ?>
    <div class="dashboard-container">
    <?php require_once '../home/logout_modals_html.php';
        logoutModalPhp("admin"); ?>
        <section class="election-time">
            <!-- Notice Display -->
            <div class="notice-section">
                <h2>Current Election Details</h2>
                <div id="current-notice">
                    <table id="notice-table">
                        <caption>Loading.....</caption>
                    </table>
                    <button id="update-notice-btn">Update Election</button>
                </div>

                <div class="content-wrapper">
                    <div class="overview">
                    <h3>Election Overview</h3>
                        <details class="overview-item">
                            <summary>Total Voters(<?= getTotal($conn, "voters"); ?>)</summary>
                            <p>Currently we have total <?= getTotal($conn, "voters"); ?> registered voters till now</p>
                        </details>
                        <details class="overview-item">
                            <summary>Total Pending Voters</summary>
                            <p>As we progress towards election, we still have <?= getTotal($conn, "pendingstatus"); ?>
                                no of citizens whose voter resgistration is on pending</p>
                        </details>
                        <details class="overview-item">
                            <summary>Total candidates</summary>
                            <p><?= getTotal($conn, "candidates"); ?></p>
                        </details>
                        <details class="overview-item">
                            <summary>Total Parties</summary>
                            <p><?= getTotal($conn, "parties"); ?></p>
                        </details>
                        <details class="overview-item">
                            <summary>Votes Cast</summary>
                            <p><?= totalVoteCasted($conn); ?></p>
                        </details>
                    </div>

                    <!-- Election Form -->
                    <form id="election-form" action="../admin/add_election_time.php" method="POST" onsubmit="return validateElectionForm();">
                        <h3 id="form-title">Set New Election</h3>
                        <input type="hidden" id="election-id" name="electionId"/>

                        <div>
                            <label for="election-name">Election Name</label>
                            <input type="text" id="election-name" name="electionName" />
                            <span class="error" id="electionNameError"></span>
                        </div>

                        <div>
                            <label for="start-time">Start Time</label>
                            <input type="datetime-local" id="start-time" name="startTime" />
                            <span class="error" id="startTimeError"></span>
                        </div>

                        <div>
                            <label for="end-time">End Time</label>
                            <input type="datetime-local" id="end-time" name="endTime" />
                            <span class="error" id="endTimeError"></span>
                        </div>
                        <div>
                            <label for="nominationStartTime">Nomination Start Time</label>
                            <input type="datetime-local" id="nomination-StartTime" name="nominationStartTime" />
                            <span class="error" id="nominationStartTimeError"></span>
                        </div>
                        <div>
                            <label for="nominationEndTime">Nomination Start Time</label>
                            <input type="datetime-local" id="nomination-EndTime" name="nominationEndTime" />
                            <span class="error" id="nominationEndTimeError"></span>
                        </div>

                        <button type="submit" id="set-election-btn">Set Election</button>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <script src="../js/get_votingTime.js"></script>
    <script>
        //function to clear errors
        function clearErrors() {
            document.getElementById('electionNameError').textContent = '';
            document.getElementById('startTimeError').textContent = '';
            document.getElementById('endTimeError').textContent = '';
            document.getElementById('nominationStartTimeError').textContent = '';
            document.getElementById('nominationEndTimeError').textContent = '';
        }

        // Function to validate the election form
        function validateElectionForm() {
            const electionName = document.getElementById('election-name').value.trim();
            const startTime = new Date(document.getElementById('start-time').value).getTime();
            const endTime = new Date(document.getElementById('end-time').value).getTime();
            const nominationStartTime = new Date(document.getElementById('nomination-StartTime').value).getTime();
            const nominationEndTime = new Date(document.getElementById('nomination-EndTime').value).getTime();
            const currentTime = new Date().getTime();
            let isValid = true;

            // Clear previous error messages
            clearErrors();
            if (!electionName) {
                document.getElementById('electionNameError').textContent = 'Election Name is required.';
                isValid = false;
            }

            if (!startTime) {
                document.getElementById('startTimeError').textContent = 'Start Time is required.';
                isValid = false;
            } else if (startTime < currentTime) {
                document.getElementById('startTimeError').textContent = 'Start Time should not be less than the current time.';
                isValid = false;
            }

            if (!endTime) {
                document.getElementById('endTimeError').textContent = 'End Time is required.';
                isValid = false;
            } else if (endTime <= startTime) {
                document.getElementById('endTimeError').textContent = 'End Time should be greater than Start Time.';
                isValid = false;
            }
            
            if (!nominationStartTime) {
                document.getElementById('nominationStartTimeError').textContent = 'Nomination Start Time is required.';
                isValid = false;
            } else if (nominationStartTime < currentTime) {
                document.getElementById('nominationStartTimeError').textContent = 'Nomination Start Time should not be less than the current time.';
                isValid = false;
            }else if (nominationStartTime > startTime) {
                document.getElementById('nominationStartTimeError').textContent = 'Nomination Start Time should set before the Election start time.';
                isValid = false;
            }
            if (!nominationEndTime) {
                document.getElementById('nominationEndTimeError').textContent = 'Nomination End Time is required.';
                isValid = false;
            } else if (nominationEndTime < nominationStartTime) {
                document.getElementById('nominationEndTimeError').textContent = 'Nomination end Time should not be less than the Nomination start time.';
                isValid = false;
            }else if (nominationEndTime > startTime) {
                document.getElementById('nominationEndTimeError').textContent = 'Nomination end Time should not be less than the Nomination start time.';
                isValid = false;
            }

            return isValid;
        }
window.onclick = function () {
    
            //clsing the modal when clicked outside
            var modals = document.getElementsByClassName('all-modals');
            for (var i = 0; i < modals.length; i++) {
                if (event.target == modals[i]) {
                    modals[i].style.display = 'none';
                }
            }
}
        // Function to show the welcome message
        window.addEventListener('load', () => {
            // Get the welcome message element and dismiss button
            const logIn = <?= json_encode($fromLogIn); ?>;
            const welcomeMessage = document.getElementById('welcomeMessage');
            const dismissButton = document.getElementById('dismissButton');
            if (logIn) {

                // Show the welcome message
                welcomeMessage.classList.add('show');

                // Automatically hide after 5 seconds
                setTimeout(() => {
                    welcomeMessage.classList.add('hide');
                }, 5000);

                // Allow user to dismiss manually
                dismissButton.addEventListener('click', () => {
                    welcomeMessage.classList.add('hide');
                });
            }
        });

        // Show error modal if there's an error message
        const errorMessage = <?= json_encode($errorMessage); ?>;
        if (errorMessage) {
            showErrorModal(errorMessage);
        }

        // Function to convert date string to required format
        function formatDateTimeWithAMPM(dateString) {
            // Parse the date string into a Date object
            const [datePart, timePart] = dateString.split(' ');
            const [year, month, day] = datePart.split('-').map(Number);
            const [hours, minutes, seconds] = timePart.split(':').map(Number);
            const date = new Date(year, month - 1, day, hours, minutes, seconds);

            // Format the time with AM/PM
            const formattedHours = hours % 12 || 12; // Convert 24-hour to 12-hour
            const ampm = hours >= 12 ? 'PM' : 'AM';
            const formattedTime = `${formattedHours}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')} ${ampm}`;

            // Combine date and formatted time
            return `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')} ${formattedTime}`;
        }

        // Function to load the current notice
        function loadCurrentNotice() {
            const noticeElement = document.getElementById("notice-table");
            if (votingTime && votingTime.electionName) {
                document.getElementById("update-notice-btn").style.display = "inline";
                noticeElement.innerHTML = ``;
                noticeElement.innerHTML = `
                <thead>
                    <tr>
                        <th>Election ID</th>
                        <th>Election Name</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Nomination Start</th>
                        <th>Nomination End</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding:5px 10px;">${votingTime.electionId}</td>
                        <td style="padding:5px 10px;">${votingTime.electionName}</td>
                        <td style="padding:5px 10px;">${formatDateTimeWithAMPM(votingTime.startTime)}</td>
                        <td style="padding:5px 10px;">${formatDateTimeWithAMPM(votingTime.endTime)}</td>
                        <td style="padding:5px 10px;">${formatDateTimeWithAMPM(votingTime.nominationStartTime)}</td>
                        <td style="padding:5px 10px;">${formatDateTimeWithAMPM(votingTime.nominationEndTime)}</td>
                    </tr>
                </tbody>
            `;
            } else {
                noticeElement.textContent = "No election is currently set.";
                // document.getElementById("update-notice-btn").style.display = "none";
            }
        }
        document.getElementById("update-notice-btn").style.display = "none";

        // Call the function in every half of a seconds to load the notice
        setInterval(loadCurrentNotice, 500);
        // if(votingTime.endTime>new Date().to){}


        // Function to populate form with voting time details for update
        function populateForm() {
            if (votingTime) {
                document.getElementById("election-id").value = votingTime.electionId || "";
                document.getElementById("election-name").value = votingTime.electionName || "";
                document.getElementById("start-time").value = votingTime.startTime || "";
                document.getElementById("end-time").value = votingTime.endTime || "";
                document.getElementById("nomination-StartTime").value = votingTime.nominationStartTime || "";
                document.getElementById("nomination-EndTime").value = votingTime.nominationEndTime || "";
            }
        }

        // Event listener for the Update Notice button
        document.getElementById("update-notice-btn").addEventListener("click", function () {
            const setElectionBtn = document.getElementById("set-election-btn");
            const formTitle = document.getElementById("form-title");
            if (setElectionBtn.textContent === "Set Election") {
                setElectionBtn.textContent = "Update Election";
                populateForm();
                clearErrors();
                formTitle.textContent = "Update Election";
                this.textContent = "Reset Form";
            } else {
                setElectionBtn.textContent = "Set Election";
                formTitle.textContent = "Set New Election";
                document.getElementById("election-form").reset();
                clearErrors();
                this.textContent = "Update Notice";
            }
        });

        //functionality to close another details when one is opened
        document.addEventListener('DOMContentLoaded', function () {
            const detailsElements = document.querySelectorAll('.overview-item');

            detailsElements.forEach(details => {
                details.addEventListener('toggle', function () {
                    if (details.open) {
                        detailsElements.forEach(otherDetails => {
                            if (otherDetails !== details) {
                                otherDetails.removeAttribute('open');
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>