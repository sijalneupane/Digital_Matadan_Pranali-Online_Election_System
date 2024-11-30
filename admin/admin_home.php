<?php 
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: admin_login.php');
    exit;
}
$_SESSION['pageName'] = "Admin-Dashboard";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Online Voting System</title>
    <link rel="stylesheet" href="admin_home.css">
</head>
<body>
    <?php require 'admin_navbar.php'; ?>

    <div class="dashboard-container">
        <header class="dashboard-header">
            <h1>Admin Dashboard</h1>
            <p>Welcome back, Admin! Manage your elections effectively.</p>
        </header>

        <div class="dashboard-content">
            <section class="overview">
                <h2>Election Overview</h2>
                <div class="overview-flex">
                    <div class="overview-item">
                        <h3>Total Voters</h3>
                        <p>1200</p>
                    </div>
                    <div class="overview-item">
                        <h3>Total candidates</h3>
                        <p>40</p>
                    </div>
                    <div class="overview-item">
                        <h3>Votes Cast</h3>
                        <p>1000</p>
                    </div>
                    <div class="overview-item">
                        <h3>Election Time</h3>
                        <p>2024-12-01 (6am - 6pm)</p>
                    </div>
                </div>
            </section>

            <section class="admin-actions">
                <h2>Admin Actions</h2>
                <div class="actions-flex">
                    <a href="add_candidates.php" class="action-card">Add candidates</a>
                    <a href="verify_voters.php" class="action-card">Verify Voters</a>
                    <a href="#" class="action-card">View voters and Candidates</a>
                    <!-- <a href="view_results.php" class="action-card">View Results</a> -->
                    <a href="#" class="action-card">View Results</a>
                    <!-- <a href="manage_voting_time.php" class="action-card">System Settings</a> -->
                    <a href="#" class="action-card">Manage Election Time</a>
                </div>
            </section>
        </div>
    </div>
</body>
</html>
