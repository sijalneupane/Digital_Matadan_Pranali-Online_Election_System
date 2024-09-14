<!-- guidelines.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Voting area</title>
</head>
<body>
  <div class="container">
    <?php include 'sidebar.php'; ?>

    <script>
      document.querySelector('a[href="voting.php"]').classList.add('active');
    </script>

    <div class="content">
    <form id="voteForm">
        <label>Select your candidate:</label><br>
        <input type="radio" name="candidate" value="Candidate 1"> Candidate 1<br>
        <input type="radio" name="candidate" value="Candidate 2"> Candidate 2<br>
        <br>
        <button type="button" onclick="submitVote()">Submit Vote</button>
    </form>
    <p id="voteConfirmation"></p>
    </div>
  </div>
  <script>
function submitVote() {
    let form = document.getElementById('voteForm');
    let candidate = form.candidate.value;
    
    if (candidate) {
        document.getElementById('voteConfirmation').innerHTML = "You voted for: " + candidate;
        // Here you can send the data using AJAX or PHP form submission
    } else {
        alert('Please select a candidate!');
    }
}
</script>
</body>
</html>
