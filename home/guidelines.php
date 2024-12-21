<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Election Guidelines</title>
  <style>
    /* General Styles */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
      color: #333;
      line-height: 1.6;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
    }

    .container {
      max-width: 1200px;
      margin: 30px auto;
      display: flex;
      flex-direction: column;
      /* Ensures the heading and sections are stacked */
      gap: 20px;
      width: 100%;
      padding: 20px;
      background-color: #ffffff;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .header {
      text-align: center;
    }

    h1 {
      color: #5a4dad;
    }

    .sections {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .section {
      flex: 1 1 calc(33.33% - 20px);
      /* Three columns with 20px gap */
      min-width: 350px;
      /* Ensure it stacks when smaller */
      background-color: #f0e7ff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
    }

    .section:hover {
      transform: translateY(-5px);
    }

    h2 {
      color: #5a4dad;
      font-size: 1.3em;
      margin-bottom: 10px;
    }

    ul {
      list-style: none;
      padding: 0;
    }

    ul li {
      margin: 10px 0;
      padding: 10px;
      background-color: #ffffff;
      border-left: 5px solid #5a4dad;
      border-radius: 5px;
      color: #333;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      transition: background 0.3s ease;
    }

    ul li:hover {
      background-color: #e0d4f5;
    }

    .note {
      margin-top: 20px;
      padding: 15px;
      background-color: #fff3cd;
      border: 1px solid #ffeeba;
      border-radius: 5px;
      font-size: 1em;
      color: #856404;
    }

    /* Back Button Styles */
    .back-button {
      position: fixed;
      top: 3%;
        left: 1%;
      transform: translateY(-10%);
      background-color: #5a4dad;
      color: #fff;
      padding: 10px 15px;
      border: none;
      border-radius: 5px;
      font-size: 1em;
      text-decoration: none;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      transition: background 0.3s ease, transform 0.2s ease;
    }

    .back-button:hover {
      background-color: #483798;
      transform: scale(1.1);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .sections {
        flex-direction: column;
        gap: 10px;
      }

      .section {
        flex: 1 1 100%;
      }

      h1 {
        font-size: 1.8em;
      }

      .back-button {
        font-size: 0.9em;
        padding: 8px 12px;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <!-- Header Section -->
    <div class="header">
      <h1>Election Guidelines</h1>
    </div>

    <!-- Sections Container -->
    <div class="sections">
      <div class="section">
        <h2>General Rules</h2>
        <ul>
          <li>All voters must be at least 18 years of age to participate in the election.</li>
          <li>Ensure that you are registered in the system before the deadline.</li>
          <li>Each voter is allowed to vote only once. Multiple votes are strictly prohibited.</li>
          <li>Use your official credentials to log into the voting portal.</li>
          <li>Maintain the confidentiality of your voting credentials to prevent misuse.</li>
        </ul>
      </div>
      <div class="section">
        <h2>How to Vote</h2>
        <ul>
          <li>Log into the online voting portal using your registered email and password.</li>
          <li>Navigate to the <strong>Voting</strong> section and follow the on-screen instructions.</li>
          <li>Select your preferred candidate(s) from the list provided.</li>
          <li>Review your choices before submitting your vote. Votes cannot be changed after submission.</li>
          <li>Click on the <strong>Submit Vote</strong> button to confirm your selection.</li>
        </ul>
      </div>
      <div class="section">
        <h2>Important Notes</h2>
        <ul>
          <li>The voting period will be scheduled by admin and can be seen in homepage</li>
          <li>If you encounter technical issues, contact the election support team immediately.</li>
          <li>Results will be announced on the <strong>Results</strong> page after the counting process is complete.</li>
        </ul>
        <div class="note">
          <strong>Reminder:</strong> Your vote matters! Ensure that you cast your vote responsibly and encourage others to participate in the election process.
        </div>
      </div>
    </div>
  </div>

  <!-- Back Button -->
  <a href="../home/index.php" class="back-button">	&larr; Back to Home</a>
</body>

</html>