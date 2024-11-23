<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User - Voting Status</title>
    <script>
        // Function to fetch voting status from the server
        function fetchVotingStatus() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_voting_status.php', true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById('voting-message').innerHTML = this.responseText;
                }
            }
            xhr.send();
        }

        // Automatically fetch the voting status every 10 seconds
        setInterval(fetchVotingStatus, 10000);  // Fetch every 10 seconds (10000 milliseconds)

        // Fetch voting status immediately when page loads
        window.onload = fetchVotingStatus;
    </script>
</head>
<body>
    <h2>Voting Status</h2>
    <p id="voting-message">Loading...</p>
</body>
</html>