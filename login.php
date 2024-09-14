<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Matadan Pranali</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            background-image: url('https://www.transparenttextures.com/patterns/asfalt-light.png'); /* Adding a soft background pattern */
        }

        .container {
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.05);
        }

        .login-box {
            display: flex;
            width: 900px;
            height: 600px;
            background-color: white;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            position: relative;
            transition: all 0.3s ease;
        }

        /* Left Panel with Nepali Flag */
        .left-panel {
            flex: 1;
            background-color: #d32f2f;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px;
            background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/9/9b/Flag_of_Nepal.svg/1280px-Flag_of_Nepal.svg.png');
            background-repeat: no-repeat;
            background-size: 200px;
            background-position: bottom center; /* Position the flag in a visible location */
            position: relative;
        }

        .left-panel h2 {
            margin-bottom: 20px;
            font-size: 2.4rem;
            font-family: 'Noto Sans', sans-serif;
        }

        .left-panel p {
            font-size: 1.2rem;
            line-height: 1.5;
            text-align: center;
            opacity: 0.9;
        }

        .left-panel img {
            width: 200px;
            height: auto;
            margin-top: 20px;
            opacity: 0.8;
        }

        /* Right Panel - Login Form */
        .right-panel {
            flex: 1;
            padding: 50px;
            background-color: #1976d2;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .right-panel h3 {
            margin-bottom: 30px;
            font-size: 1.8rem;
            color: white;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .btn {
            padding: 12px;
            font-size: 1.1rem;
            background-color: #ffeb3b;
            color: black;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #fdd835;
        }

        /* New Vote Icon */
        .vote-icon {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 50px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-box {
                flex-direction: column;
                height: auto;
                width: 100%;
            }

            .left-panel {
                display: none; /* Hide left panel on small screens */
            }

            .right-panel {
                padding: 30px;
                width: 100%;
                background-color: rgba(33, 150, 243, 0.9);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-box">
            <div class="left-panel">
                <h2>डिजिटल मतदान प्रणाली</h2>
                <p>
                    हाम्रो "डिजिटल मतदान प्रणाली" मा स्वागत छ। यस प्रणालीले नेपालको भविष्य निर्माणमा तपाईँको अमूल्य मतको योगदान सुनिश्चित गर्न मद्दत गर्दछ।
                    नेपालको मौलिक पहिचान झल्काउने यो प्रणाली प्रयोग गरी आफ्नो मत सुनिश्चित गर्नुहोस्।
                </p>
            </div>
            <div class="right-panel">
                <h3>Sign In</h3>
                <form>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="form-group">
                        <label for="voter-id">Voter ID</label>
                        <input type="text" id="voter-id" name="voter-id" placeholder="Enter your voter ID" required>
                    </div>
                    <button type="submit" class="btn">Login</button>
                </form>
                <img class="vote-icon" src="https://cdn-icons-png.flaticon.com/512/3447/3447347.png" alt="Vote Icon">
            </div>
        </div>
    </div>
</body>
</html>
