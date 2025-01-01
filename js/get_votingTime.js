// Global variable to store the voting status
let votingTime = {};

// Function to fetch voting status from the server
function fetchVotingTime() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../time/fetch_voting_time.php', true);
    xhr.onload = function () {
        if (this.status === 200) {
            votingTime = JSON.parse(this.responseText);
        }
    };
    xhr.send();
}


window.onload = function () {
    fetchVotingTime();
};
// // Automatically fetch the voting status every 10 seconds
setInterval(fetchVotingTime, 1000); // Fetch every 10 seconds (10000 milliseconds)