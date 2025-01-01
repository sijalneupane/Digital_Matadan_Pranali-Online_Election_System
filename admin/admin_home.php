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
    <script src="../js/get_votingTime.js"></script>
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

    <div id="modal1" class="modal-overlay1">
        <div class="modal-content1">
            <p id="modalMessage1"></p>
            <button onclick="closeModal1()">Close</button>
        </div>
    </div>
    <?php require '../admin/admin_navbar.php';

    ?>
    <div class="dashboard-container">
        <?php logoutModalPhp("admin"); ?>
        <section class="overview">
            <h2>Election Overview</h2>
            <div class="overview-flex">
                <div class="overview-item">
                    <h4>Total Voters</h4>
                    <p><?= getTotal($conn, "voters"); ?></p>
                </div>
                <div class="overview-item">
                    <h4>Total Pending Voters</h4>
                    <p><?= getTotal($conn, "pendingstatus"); ?></p>
                </div>
                <div class="overview-item">
                    <h4>Total candidates</h4>
                    <p><?= getTotal($conn, "candidates"); ?></p>
                </div>
                <div class="overview-item">
                    <h4>Total Parties</h4>
                    <p><?= getTotal($conn, "parties"); ?></p>
                </div>
                <div class="overview-item">
                    <h4>Votes Cast</h4>
                    <p><?= totalVoteCasted($conn); ?></p>
                </div>
                <div class="overview-item">
                    <h4>Election Time</h4>
                    <p>2024-12-01 (6am - 6pm)</p>
                </div>
            </div>
        </section>
        <section class="election-time">
            <!-- Notice Display -->
            <div class="notice-section">
                <p id="current-notice"></p>
                <button id="update-notice-btn">Update Notice</button>
            </div>

            <!-- Election Form -->
            <form id="election-form">
                <input type="hidden" id="election-id" />

                <div>
                    <label for="election-name">Election Name</label>
                    <input type="text" id="election-name" name="electionName" required />
                </div>

                <div>
                    <label for="start-time">Start Time</label>
                    <input type="datetime-local" id="start-time" name="startTime" required />
                </div>

                <div>
                    <label for="end-time">End Time</label>
                    <input type="datetime-local" id="end-time" name="endTime" required />
                </div>

                <button type="submit" id="set-election-btn">Set Election</button>
            </form>
        </section>
    </div>

    <script>
        window.addEventListener('load', () => {
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

        // Function to load the current notice
        function loadCurrentNotice() {
            const noticeElement = document.getElementById("current-notice");
            if (votingTime && votingTime.electionName) {
                noticeElement.textContent = `Current Election: ${votingTime.electionName} (Starts: ${votingTime.startTime}, Ends: ${votingTime.endTime})`;
            } else {
                noticeElement.textContent = "No election is currently set.";
            }
        }

        // Initial load of the notice
        loadCurrentNotice();

        // Function to populate form with voting time details for update
        function populateForm() {
            if (votingTime) {
                document.getElementById("election-id").value = votingTime.electionId || "";
                document.getElementById("election-name").value = votingTime.electionName || "";
                document.getElementById("start-time").value = votingTime.startTime || "";
                document.getElementById("end-time").value = votingTime.endTime || "";
            }
        }

        // Event listener for the Update Notice button
        document.getElementById("update-notice-btn").addEventListener("click", function () {
            document.getElementById("set-election-btn").textContent = "Update Election";
        });
    </script>
</body>

</html>