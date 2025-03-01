<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Online Election System</title>
    <link rel="icon" href="../images/DMP logo.png" type="image/x-icon">
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;

        }

        *::-webkit-scrollbar {
            display: none;
        }

        body {
            font-family: Arial, sans-serif;
            background: url('../images/index\ background.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .navbar {
            height: 15%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 3%;
            background-color: rgba(230, 230, 244, 0.9);
            box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.7);

        }

        .logo img {
            width: 145px;
            min-width: 50px;
        }

        .election-time {
            font-size: 1.2rem;
            color: #0d2ec4;
        }

        .nav-links .admin-login {
            color: #fff;
            text-decoration: none;
            font-size: 1.2rem;
            background-color: #ff6347;
            padding: 0.5rem 1.5rem;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .nav-links .admin-login:hover {
            background-color: #ff4500;
        }

        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 4% 2% 2%;
            flex: 1;
            gap: 7%;
            /* Light white shadow */
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(10px);
            /* For Safari support */
            /* margin: 3% 10%; */
        }

        .main-content {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 4% 2% 2%;
            flex: 1;
            gap: 7%;
            box-shadow: 0 8px 32px rgba(255, 255, 255, 0.3);
            /* Light white shadow */
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(10px);
            /* For Safari support */
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin: 3% 10%;
            background-color: rgba(0, 0, 0, 0.1);
        }

        .main-content h1 {
            color: black;
            font-size: 3rem;
            margin-bottom: 1rem;
            text-shadow: 0px 0px 4px rgba(255, 255, 255, 0.6);
        }

        .main-content p {
            color: black;
            font-size: 1.5rem;
            margin-bottom: 2rem;
            max-width: fit-content;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            margin-bottom: 0.5rem;
        }

        .action-buttons a {
            margin: 0 1rem;
            padding: 0.5rem 1rem;
            text-decoration: none;
            background-color: rgb(129, 23, 23);
            color: white;
            border: 1px solid rgb(170, 147, 14);
            border-radius: 10px;
            font-size: 1.2rem;
            /* font-weight: bold; */
            /* transition:;? */
            transition: all 0.1s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .action-buttons a span {
            margin-left: 0.5rem;
            font-size: 1.2rem;
        }

        .action-buttons a:hover {
            background-color: rgb(223, 223, 223);
            color: red;
            transform: translateY(-10px);
            box-shadow: 0px 10px 10px rgba(0, 0, 0, 0.4);

        }

        .guidelines-link {
            display: block;
            /* width: 40%; */
            margin: 4% auto 0px;
            color: #510202;
            text-decoration: none;
            padding: 16px 10px;
            border: none;
            border-radius: 5px;
            /* transition: all ease-in-out 0.05s; */
            text-align: center;
            /* background-color: rgb(18, 50, 63); */
        }

        .guidelines-link:hover {
            text-decoration: underline;
            /* background-color: rgb(39, 94, 116); */
            /* box-shadow: 0 2px 5px 4px rgba(0255, 0255, 255, 0.45); */
        }

        .voting-image {
            border: none;
            border-radius: 20px;
            width: 100%;
            max-width: 300px;
        }

        .content {
            text-align: center;
        }

        .buttons-box {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-top: 20px;
        }

        .buttons-box a {
            width: 200px;
            display: inline-block;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all ease-in 0.05s;
            opacity: 0.9;
        }
        .buttons-box a:first-child {
            background-color: rgb(189, 52, 52);
        }
        .buttons-box a:nth-child(2) {
            background-color: rgb(24, 103, 187);
        }
        .buttons-box a:nth-child(3) {
            background-color: rgb(182, 115, 15);
        }
        .buttons-box a:hover {
            opacity: 1;
            transform: scale(1.1);
        }
        .marquee {
            white-space: nowrap;
            overflow: hidden;
            display: none;
            padding: 10px;
            width: 100%;
            background-color: rgb(114, 114, 114);
        }

        .marquee span {
            color: white;
            font-size: 1.2em;
            display: inline-block;
            padding-left: 100%;
            animation: marquee 40s ease infinite;
        }

        @keyframes marquee {
            from {
                transform: translateX(0%);
            }

            to {
                transform: translateX(-100%);
            }
        }

        @media (max-width: 768px) {

            .main-content {
                margin: 3% 10%;
                flex-direction: column;
            }

            .main-content h1 {
                font-size: 2.5rem;
            }

            .main-content p {
                font-size: 1.2rem;
            }

            .action-buttons a {
                font-size: 0.9rem;
            }

            .voting-image {
                margin-top: 40px;
                width: 50%;
            }

            .arrow img {
                width: 10%;
            }

            .guidelines-link {
                width: 70%;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .main-content h1 {
                font-size: 2rem;
            }

            .main-content p {
                font-size: 1rem;
            }

            .voting-image {
                width: 80%;
            }

            .action-buttons a {
                font-size: 0.9rem;
            }

            .arrow img {
                width: 20%;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <img src="../images/DMP logo.png" alt="Election Logo">
        </div>
        <div class="election-time" id="election-time">

        </div>
        <div class="nav-links">
            <a href="../admin/admin_login.php" class="admin-login">Admin Login</a>
        </div>
    </nav>

    <div id="marquee" class="marquee">
    </div>
    <!-- Main Content -->
    <div class="container">
        <div class="main-content">
            <div class="content">
                <h1>Welcome to the Online Election System</h1>
                <p>Participate in a fair and transparent voting system. Your vote matters.</p>
                <div class="action-buttons">
                    <a href="../register_and_login/voter_login_form.php" class="voter-login">Voter Login
                        <span>&rarr;</span></a>
                    <a href="../register_and_login/voter_register_form.php" class="voter-register">Voter
                        Register<span>&rarr;</span></a>
                </div>
                <a href="../home/guidelines.php" class="guidelines-link">Need Some Guidelines? Click here</a>

            </div>
            <div class="image-section">
                <img src="https://media.istockphoto.com/id/1258633187/vector/online-vote.jpg?s=612x612&w=0&k=20&c=7eOLMUx6_EKkXPjMrElvFYkz2x0rdShD8DNAlrff6-E="
                    alt="Voting Image" class="voting-image">
            </div>
        </div>
        <div class="buttons-box">
            <a href="../home/notices.php">
                View All Notices &rarr;
            </a>
            <a href="../home/view_all_candidates.php">
                View Candidates &rarr;
            </a>
            <a href="../home/view_all_results.php">
                View Results &rarr;
            </a>
        </div>
    </div>
    <?php include '../home/footer.php'; ?>
    <script>
        // Global variable to store the voting status
        let votingTime = {};

        // Function to fetch voting status from the server
        function fetchVotingTime() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', '../time/fetch_voting_time.php', true);
            xhr.onload = function () {
                if (this.status === 200) {
                    const newVotingTime = JSON.parse(this.responseText);
                    if (JSON.stringify(votingTime) !== JSON.stringify(newVotingTime)) {
                        votingTime = newVotingTime;
                        if (votingTime.error) {
                            document.getElementById('election-time').innerHTML = 'No election scheduled till now';
                        } else {
                            const currentTime = new Date();
                            const startTime = new Date(votingTime.startTime);
                            const endTime = new Date(votingTime.endTime);
                            let marqueeElement = document.getElementById('marquee');
                            if (currentTime < endTime) {
                                marqueeElement.style.display = 'block';
                                marqueeElement.innerHTML = `<span>New Election is scheduled. Make sure to view the notices and participate in voting.</span>`;
                                document.getElementById('election-time').innerHTML = `
                                <div>Election Start: ${startTime.toLocaleString()} <br>Election End: ${endTime.toLocaleString()}</div>
                                `;
                            } else {
                                console.log('changed');
                                document.getElementById('election-time').innerHTML = 'Election has ended';
                            }
                        }
                    }
                }
            };
            xhr.send();
        }
        fetchVotingTime();
        setInterval(fetchVotingTime, 2000);
    </script>
</body>

</html>