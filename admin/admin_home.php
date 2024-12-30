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
    <style>
       .welcome-message {
            position: fixed;
            top: -100px;
            /* Hidden above the screen initially */
            left: 50%;
            transform: translateX(-50%);
            background-color: #4CAF50;
            /* Green background */
            color: white;
            /* White text */
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
            transition: transform 1s ease-in-out, opacity 1s ease-out;
            z-index: 1000;
        }

        .welcome-message.show {
            margin-top:20px;
            transform: translateX(-50%) translateY(100px);
            /* Moves into view */
        }

        .welcome-message.hide {
            transform: translateX(-50%) translateY(-200px);
            /* Moves out of view */
            opacity: 0;
        }
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
        const errorMessage = <?= json_encode($errorMessage); ?>;
        if (errorMessage){ showErrorModal(errorMessage)};
         // Show error modal if there's an error message
    </script>
</body>

</html>