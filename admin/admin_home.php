<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_home.css">
</head>
<body>
    <?php require 'admin_navbar.php' ?>

    <div class="dashboard-container">
        <!-- Header -->
        <div class="header">
            <h2>Dashboard</h2>
            <div class="quick-stats">
                <div class="quick-stat">
                    <h3>Total Candidates</h3>
                    <p>25</p>
                </div>
                <div class="quick-stat">
                    <h3>Total Voters</h3>
                    <p>500</p>
                </div>
                <div class="quick-stat">
                    <h3>Voting Status</h3>
                    <p>Open</p>
                </div>
            </div>
        </div>

        <!-- Overview Cards -->
        <div class="overview-cards">
            <div class="overview-card">
                <h2>Manage Candidates</h2>
                <p>View and manage all candidates for the election.</p>
                <a href="manage_candidates.php" class="action-button">Go</a>
            </div>
            <div class="overview-card">
                <h2>Manage Election Time</h2>
                <p>Set and update the election dates and times.</p>
                <a href="manage_election_time.php" class="action-button">Go</a>
            </div>
            <div class="overview-card">
                <h2>View Results</h2>
                <p>Analyze the results of the election.</p>
                <a href="view_results.php" class="action-button">Go</a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="recent-activity">
            <h2>Recent Activity</h2>
            <ul>
                <li>Candidate John Doe was added on September 18, 2024.</li>
                <li>Election time updated to October 1, 2024 - October 15, 2024.</li>
                <li>Voting status changed to "Open" on September 19, 2024.</li>
            </ul>
        </div>

        <!-- Chart Section -->
        <!-- <div class="chart-container">
            <canvas id="votingChart" width="400" height="200"></canvas>
        </div> -->
    </div>

    <!-- Chart.js (for interactive charts) -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Example data for Chart.js
        const ctx = document.getElementById('votingChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Candidates', 'Voters', 'Votes Cast'],
                datasets: [{
                    label: 'Election Statistics',
                    data: [25, 500, 300],
                    backgroundColor: ['#007bff', '#28a745', '#ffc107'],
                    borderColor: ['#0056b3', '#218838', '#e0a800'],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script> -->
</body>
</html>