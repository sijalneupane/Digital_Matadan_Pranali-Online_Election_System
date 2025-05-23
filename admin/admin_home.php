<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../admin/admin_login.php');
    exit;
}
$errorMessage = isset($_SESSION['errorMsg']) ? $_SESSION['errorMsg'] : '';
unset($_SESSION['errorMsg']); // Clear the message
$successMessage = isset($_SESSION['successMsg']) ? $_SESSION['successMsg'] : '';
unset($_SESSION['successMsg']); // Clear the message
$fromLogIn = isset($_SESSION['fromLogIn']) ? $_SESSION['fromLogIn'] : '';
unset($_SESSION['fromLogIn']);
$newNoticeName = isset($_SESSION['newNoticeName']) ? $_SESSION['newNoticeName'] : '';
unset($_SESSION['newNoticeName']);

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

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
                <h1>Dashboard</h1>
                <!-- <div class="content-wrapper"> -->
                <div class="dashboard">
                    <div class="card">
                        <div class="card-content">
                            <div class="text">
                                <h4>Total Approved Voters</h4>
                                <p><?= getTotal($conn, "voters"); ?></p>
                            </div>
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-content">
                            <div class="text">
                                <h4>Total Pending Voters</h4>
                                <p><?= getTotal($conn, "pendingVoters"); ?></p>
                            </div>
                            <i class="fas fa-user-clock"></i>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-content">
                            <div class="text">
                                <h4>Total Candidates</h4>
                                <p><?= getTotal($conn, "candidates"); ?></p>
                            </div>
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-content">
                            <div class="text">
                                <h4>Total Parties</h4>
                                <p><?= getTotal($conn, "parties"); ?></p>
                            </div>
                            <i class="fas fa-landmark"></i>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-content">
                            <div class="text">
                                <h4>Votes Cast in numbers</h4>
                                <p><?= totalVoteCasted($conn); ?></p>
                            </div>
                            <i class="fas fa-vote-yea"></i>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-content">
                            <div class="text">
                                <h4>Votes Cast in percentage</h4>
                                <p>
                                    <?php
                                    try {
                                        $totalVoters = getTotal($conn, "voters");
                                        if ($totalVoters == 0) {
                                            throw new Exception("Total voters is zero.");
                                        }
                                        echo (totalVoteCasted($conn) / $totalVoters) * 100 . " %";
                                    } catch (Exception $e) {
                                        echo $e->getMessage();
                                    }
                                    ?>
                                </p>
                            </div>
                            <i class="fas fa-percent"></i>
                        </div>
                    </div>
                </div>
                <div class="notice-link-box">
                    <a href="../home/notices.php?userType=admin" class="notice-link">View All Notices</a>
                </div>

                <h2>Current Election Details</h2>
                <div id="current-notice">
                    <table id="notice-table">
                        <caption>Loading.....</caption>
                    </table>
                    <button id="update-notice-btn">Update Election</button>
                </div>
                <!-- Election Form -->
                <form id="election-form" action="../admin/add_election_time.php" method="POST"
                    onsubmit="return validateElectionForm();">
                    <h3 id="form-title">Set New Election</h3>
                    <input type="hidden" id="election-id" name="electionId" />

                    <div>
                        <label for="election-name">Election Name</label>
                        <input type="text" id="election-name" name="electionName" />
                        <span class="error" id="electionNameError"></span>
                    </div>
                    <div>
                        <label for="nomination-StartTime">Nomination Start Time</label>
                        <input type="datetime-local" id="nomination-StartTime" name="nominationStartTime" />
                        <span class="error" id="nominationStartTimeError"></span>
                    </div>
                    <div>
                        <label for="nomination-EndTime">Nomination End Time</label>
                        <input type="datetime-local" id="nomination-EndTime" name="nominationEndTime" />
                        <span class="error" id="nominationEndTimeError"></span>
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


                    <button type="submit" id="set-election-btn">Set Election</button>
                </form>
                <!-- </div> -->
            </div>
        </section>
        <section id="votersMessages">
            <h2>Recent Voter Messages</h2>
            <div class="messages-container">
                <?php
                $query = "SELECT vm.messages, v.id AS voterId, v.dId, v.name, vm.created_at 
                  FROM votersMessages vm 
                  JOIN voters v ON vm.voterId = v.id 
                  ORDER BY vm.created_at DESC 
                  LIMIT 10";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='message-item'>";
                        echo "<p><strong>ID: " . htmlspecialchars($row['voterId']) . " | District: " . htmlspecialchars($row['dId']) . " | " . htmlspecialchars($row['name']) . ":</strong></p>";
                        echo "<p>" . nl2br(htmlspecialchars($row['messages'])) . "</p>";
                        echo "<small>Sent on: " . date("F j, Y, g:i A", strtotime($row['created_at'])) . "</small>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No messages found.</p>";
                }
                ?>
            </div>
        </section>
    </div>
    <?php include '../home/footer.php'; ?>

    <script src="../js/get_votingTime.js"></script>
    <script>
        // function to open the notice if created
        let newNoticeName = <?= json_encode($newNoticeName); ?>;
        if (newNoticeName) {
            window.open(newNoticeName, '_blank');
        }

        // Function to update the minimum dates for the election form
        document.addEventListener("DOMContentLoaded", function () {
            if (document.getElementById("election-id").value === "") {
                return;
            }
            const nominationStart = document.getElementById("nomination-StartTime");
            const nominationEnd = document.getElementById("nomination-EndTime");
            const startTime = document.getElementById("start-time");
            const endTime = document.getElementById("end-time");

            function updateMinDates() {
                let nowUTC = new Date();
                let offset = 5.75 * 60; // 5 hours 45 minutes in minutes
                let now = new Date(nowUTC.getTime() + offset * 60000)
                    .toISOString()
                    .slice(0, 16);

                // console.log(now);


                // Set initial minimum values
                nominationStart.min = now;
                nominationEnd.min = nominationStart.value || now;
                startTime.min = nominationEnd.value || now;
                endTime.min = startTime.value || now;
            }

            function updateMinTime(field, dependentField) {
                if (field.value) {
                    dependentField.min = field.value;
                }
            }

            // Event listeners (use 'change' for proper handling of time changes)
            nominationStart.addEventListener("change", () => updateMinTime(nominationStart, nominationEnd));
            nominationEnd.addEventListener("change", () => updateMinTime(nominationEnd, startTime));
            startTime.addEventListener("change", () => updateMinTime(startTime, endTime));

            updateMinDates(); // Set initial constraints on load
        });



        window.onload = function () {
            fetchVotingTime();
        };
        // // Automatically fetch the voting status every 10 seconds
        setInterval(fetchVotingTime, 1000); // Fetch every 10 seconds (10000 milliseconds)

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

            if ((document.getElementById("election-id").value) === "") {
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


                if (!nominationStartTime) {
                    document.getElementById('nominationStartTimeError').textContent = 'Nomination Start Time is required.';
                    isValid = false;
                } else if (nominationStartTime < currentTime) {
                    document.getElementById('nominationStartTimeError').textContent = 'Nomination Start Time should not be less than the current time.';
                    isValid = false;
                } else if (nominationStartTime > startTime) {
                    document.getElementById('nominationStartTimeError').textContent = 'Nomination Start Time should set before the Election start time.';
                    isValid = false;
                }
                if (!nominationEndTime) {
                    document.getElementById('nominationEndTimeError').textContent = 'Nomination End Time is required.';
                    isValid = false;
                } else if (nominationEndTime < nominationStartTime) {
                    document.getElementById('nominationEndTimeError').textContent = 'Nomination end Time should not be less than the Nomination start time.';
                    isValid = false;
                } else if (nominationEndTime > startTime) {
                    document.getElementById('nominationEndTimeError').textContent = 'Nomination end Time should not be more than the Election start time.';
                    isValid = false;
                }

            }

            if (!endTime) {
                document.getElementById('endTimeError').textContent = 'End Time is required.';
                isValid = false;
            } else if (endTime <= startTime) {
                document.getElementById('endTimeError').textContent = 'End Time should be greater than Start Time.';
                isValid = false;
            } else if (endTime < currentTime) {
                document.getElementById('endTimeError').textContent = 'End Time should not be less than the current time.';
                isValid = false;
            }

            if ((document.getElementById("election-id").value) === "" && votingTime.resultStatus === "notPublished") {
                showErrorModal("Current election result is not published yet. You can't  set new election");
                // document.getElementById('election-form').reset();
                clearErrors();
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
        const successMessage = <?= json_encode($successMessage); ?>;
        if (errorMessage) {
            showErrorModal(errorMessage,false);
        } else if (successMessage) {
            showErrorModal(successMessage, true);
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
                <thead style="">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Nomination Start</th>
                        <th>Nomination End</th>
                        <th>Result Status</th>
                    </tr>
                </thead>
                <tbody style="">
                    <tr>
                        <td style="padding:5px 10px;">${votingTime.electionId}</td>
                        <td style="padding:5px 10px;">${votingTime.electionName}</td>
                        <td style="padding:5px 10px;">${formatDateTimeWithAMPM(votingTime.startTime)}</td>
                        <td style="padding:5px 10px;">${formatDateTimeWithAMPM(votingTime.endTime)}</td>
                        <td style="padding:5px 10px;">${formatDateTimeWithAMPM(votingTime.nominationStartTime)}</td>
                        <td style="padding:5px 10px;">${formatDateTimeWithAMPM(votingTime.nominationEndTime)}</td>
                        <td style="padding:5px 10px;">${votingTime.resultStatus}</td>
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
                if (votingTime.resultStatus === "published") {
                    showErrorModal("Result has been published for this election. You can't update the election details.", false);
                    return;
                }
                setElectionBtn.textContent = "Update Election";
                populateForm();
                clearErrors();
                // console.log(document.getElementById("election-id").value+"\n");
                formTitle.textContent = "Update Election";
                this.textContent = "Reset Form";
            } else {
                setElectionBtn.textContent = "Set Election";
                formTitle.textContent = "Set New Election";
                document.getElementById("election-form").reset();
                clearErrors();
                document.getElementById("election-id").value = "";
                // console.log(document.getElementById("election-id").value+"\n");
                this.textContent = "Update Notice";
            }
        });

        // //functionality to close another details when one is opened
        // document.addEventListener('DOMContentLoaded', function () {
        //     const detailsElements = document.querySelectorAll('.overview-item');

        //     detailsElements.forEach(details => {
        //         details.addEventListener('toggle', function () {
        //             if (details.open) {
        //                 detailsElements.forEach(otherDetails => {
        //                     if (otherDetails !== details) {
        //                         otherDetails.removeAttribute('open');
        //                     }
        //                 });
        //             }
        //         });
        //     });
        // });
    </script>
</body>

</html>