<?php
session_start();
$errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['error_message']); // Clear the message
// $conn = mysqli_connect('localhost', 'root', '', 'online_election');
require_once '../php_for_ajax/districtRegionSelect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Voter</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/register.css">
    <link rel="stylesheet" href="../styles/modal1.css">
    <style>
        body {
            background-color: #473c75;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            margin-top: 20px;
            margin-bottom: 15px;
            display: flex;
            width: 85%;
            max-width: 1100px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            background: rgba(0255, 0255, 255, 0.45);
            /* box-shadow: 0 8px 32px 0 rgba( 31, 38, 135, 0.37 ); */
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .logo {
            position: absolute;
            top: 6px;
            left: 6px;
            width: 90px;
            height: auto;

        }

        #frontPreview,
        #backPreview,
        #userPhotoPreview {
            display: none;
            /* max-width: 100%; */
            /* height: auto; */
            width: 100%;
            height: 200px;
            object-fit: contain;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 10px;
        }

        #userPhotoPreview {
            width: 50%;
        }

        @media (max-width:768px) {

            #frontPreview,
            #backPreview {
                width: 50%;
            }

        }
    </style>
    <script src="../js/register_validation.js" defer></script>
    <script src="../js/togglepassword.js"></script>
    <script src="../js/errorMessage_modal1.js"></script>
    <!-- <script src="../js/getRegion_ajax.js" defer></script> -->
</head>

<body>
<div id="loading" style="display: none; text-align: center;">
    <p>Loading...</p>
</div>
    <div id="modal1" class="modal-overlay1 all-modals">
        <div class="modal-content1">
            <p id="modalMessage1"></p>
            <button onclick="closeModal1()">Close</button>
        </div>
    </div>
    <div class="container">
        <div class="left">
            <a href="../home/index.php"><img src="../images/DMP logo.png" alt="Logo" class="logo"></a>
            <img src="../images/vote2.jpg" alt="Center Image" class="center-image">
            <div class="left-2">
                <h1>Register Yourself as a Voter</h1>
                <p>Utilize your rights to vote digitally!</p>
            </div>
        </div>
        <div class="right">
            <form name="voterForm" action="../register_and_login/voter_register.php" method="post"
                enctype="multipart/form-data" onsubmit="return validateForm();">
                <h2>Create Voter ID</h2>
                <div class="form-group two-columns">
                    <div class="field-error-groups">
                        <input type="text" name="name" id="name" placeholder="Full Name">
                        <span id="nameError" class="error"></span>
                    </div>
                    <div class="field-error-groups">
                        <select name="gender" id="gender">
                            <option value="default">-- Select Gender --</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                        <span id="genderError" class="error"></span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="address-selection">
                        <div id="district-and-region" style="flex:1.5 ;">
                            <div id="district-box" style="flex:1.75">
                                <label>District</label>
                                <?php district(); ?>
                                <span id="districtError" class="error"></span>
                            </div>
                            <div id="constituentNo" style="flex: 1.4;">
                                <label>Constituent No.</label>
                                <?php regionNo(); ?>
                                <span id="regionNoError" class="error"></span>
                            </div>
                        </div>
                        <div id="localAddress" style="flex:1;">
                            <label>Local Address</label>
                            <input type="text" name="local_address" id="local_address"
                                placeholder="eg:Banepa-8, Tindobato">
                            <span id="addressError" class="error"></span>
                        </div>
                    </div>
                </div>

                <div class="form-group two-columns">
                    <div class="field-error-groups">
                        <input type="text" name="email" id="email" placeholder="Email">
                        <span id="emailError" class="error"></span>
                    </div>
                    <div class="field-error-groups input-container">
                        <!-- <div class="input-container"> -->
                        <input type="password" id="password" name="password" placeholder="Password">
                        <i id="togglePasswordIcon" class="fas fa-eye toggle-password"
                            onclick="togglePasswordVisibility()"></i>
                        <!-- </div> -->
                        <span id="passwordError" class="error" style="margin-bottom: 8px;"></span>
                    </div>
                </div>
                <div class="form-group two-columns">
                    <div class="field-error-groups">
                        <label for="dateOfBirth" style="text-wrap: nowrap;">Date of Birth:</label>
                        <input type="date" name="dateOfBirth" id="dateOfBirth" placeholder="DOB:" min="">
                        <span id="dobError" class="error"></span>
                    </div>

                    <div class="field-error-groups">
                        <label for="citizenshipNumber">Citizenship Number</label>
                        <input type="text" name="citizenshipNumber" id="citizenshipNumber"
                            placeholder="Citizenship Number">
                        <span id="citizenshipError" class="error"></span>
                    </div>
                </div>
                <div class="form-group two-columns">
                    <div class="citizenship">
                        <label for="citizenshipFront">Citizenship Front Photo</label>
                        <input type="file" name="citizenshipFrontPhoto" id="citizenshipFront" accept="image/*" capture
                            onchange="previewImage(this, 'frontPreview')">
                        <img id="frontPreview" src="#" alt="Citizenship Front Preview">
                        <span id="citizenshipFrontError" class="error"></span>
                    </div>
                    <div class="citizenship">
                        <label for="citizenshipBack">Citizenship Back Photo</label>
                        <input type="file" name="citizenshipBackPhoto" id="citizenshipBack" accept="image/*" capture
                            onchange="previewImage(this, 'backPreview')">
                        <img id="backPreview" src="#" alt="Citizenship Back Preview">
                        <span id="citizenshipBackError" class="error"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="userPhoto">User Photo</label>
                    <input type="file" name="userPhoto" id="userPhoto" accept="image/*" capture
                        onchange="previewImage(this, 'userPhotoPreview')">
                    <img id="userPhotoPreview" src="#" alt="User Photo Preview">
                    <span id="userPhotoError" class="error"></span>
                </div>
                <div class="form-group register">
                    <input class="submit-button" type="submit" value="Register">
                </div>
                <div class="login-direction">Already a Voter?<a
                        href="../register_and_login/voter_login_form.php">Login</a> </div>
            </form>
        </div>
    </div>
    <script>
        
        const errorMessage = <?= json_encode($errorMessage); ?>;
        showErrorModal(errorMessage); // Pass PHP error to JS function

        // Close the modal when clicking outside of the modal content
        window.onclick = function (event) {
            var modals = document.getElementsByClassName('all-modals');
            for (var i = 0; i < modals.length; i++) {
                if (event.target == modals[i]) {
                    modals[i].style.display = 'none';
                }
            }
        }

    </script>

</body>

</html>
<?php
