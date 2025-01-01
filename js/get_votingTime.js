// Global variable to store the voting status
let votingTime = {};

// Function to fetch voting status from the server
function fetchVotingTime() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../time/fetch_voting_time.php', true);
    xhr.onload = function () {
        if (this.status === 200) {
            votingTime = JSON.parse(this.responseText);
            // document.getElementById('voting-message').innerHTML = JSON.stringify(votingTime, null, 2);
            // document.getElementById('voting-message').innerHTML = votingTime.electionId;
        }
    };
    xhr.send();
}

// Automatically fetch the voting status every 10 seconds
setInterval(fetchVotingTime, 10000); // Fetch every 10 seconds (10000 milliseconds)

// Fetch voting status immediately when page loads
window.onload = fetchVotingTime;