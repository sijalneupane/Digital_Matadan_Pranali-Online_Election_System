<?php
session_start();
$errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['error_message']); // Clear the message
$conn = mysqli_connect('localhost', 'root', '', 'online_election');

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
$name = "";
$email = "";
$password = "";
$dateOfBirth = "";
$citizenshipNumber = "";
$gender = "";
$addressid = "";
$citizenshipFrontPhoto = "";
$citizenshipBackPhoto = "";
$userPhoto = "";
$district = "";
$regionNo = "";
$local_address = "";

if ($id != 0) {
    $sql = "SELECT * 
            FROM voters V 
            INNER JOIN localaddress la ON V.addressId = la.lid
            INNER JOIN district D ON D.dId = la.dId
            WHERE id = '$id';
        ";
    $results = $conn->query($sql);
    if ($results->num_rows > 0) {
        $data = mysqli_fetch_assoc($results);
        $name = $data['name'];
        $email = $data['email'];
        $password = $data['password'];
        $dateOfBirth = $data['dateOfBirth'];
        $district = $data['district'];
        $local_address = $data['local_address'];
        $election_region = $data['regionNo'];
        $citizenshipNumber = $data['citizenshipNumber'];
        $gender = $data['gender'];
        $citizenshipFrontPhoto = $data['citizenshipFrontPhoto'];
        $citizenshipBackPhoto = $data['citizenshipBackPhoto'];
        $userPhoto = $data['userPhoto'];
    } else {
        echo "No record found with ID: $id";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Voter</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/register.css">

    <link rel="stylesheet" href="modal.css">
    <style>
        #frontPreview,
        #backPreview,
        #userPhotoPreview {
            display: none;
            max-width: 100%;
            height: auto;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
    <script>
        function showError(elementId, message) {
            document.getElementById(elementId).innerHTML = message;
        }

        function clearErrors() {
            let errorSpans = document.querySelectorAll(".error");
            errorSpans.forEach(span => span.innerHTML = "");
        }

        function validateForm() {
            clearErrors();

            // Get form values
            let name = document.forms["voterForm"]["name"].value;
            let gender = document.forms["voterForm"]["gender"].value;
            let local_address = document.forms["voterForm"]["local_address"].value;
            let email = document.forms["voterForm"]["email"].value;
            let password = document.forms["voterForm"]["password"].value;
            let citizenship = document.forms["voterForm"]["citizenshipNumber"].value;
            let dob = document.forms["voterForm"]["dateOfBirth"].value;
            let regionNo = document.forms["voterForm"]["regionNo"].value;


            // File inputs
            let citizenshipFront = document.forms["voterForm"]["citizenshipFrontPhoto"].files[0];
            let citizenshipBack = document.forms["voterForm"]["citizenshipBackPhoto"].files[0];
            let userPhoto = document.forms["voterForm"]["userPhoto"].files[0];

            // Regular expressions for validation
            let emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            let passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/;

            let isValid = true;

            if (name.trim() === "") {
                showError("nameError", "Full Name is required");
                isValid = false;
            }

            if (gender.trim() === "" || gender.trim() === "default") {
                showError("genderError", "Please select a gender");
                isValid = false;
            }

            if (local_address.trim() === "") {
                showError("addressError", "Local Address is required");
                isValid = false;
            }

            if (email.trim() === "") {
                showError("emailError", "Email is required");
                isValid = false;
            } else if (!emailPattern.test(email)) {
                showError("emailError", "Invalid email format");
                isValid = false;
            }

            if (password.trim() === "") {
                showError("passwordError", "Password is required");
                isValid = false;
            } else if (!passwordPattern.test(password)) {
                showError(
                    "passwordError",
                    "Password must include 6-20 characters, uppercase, lowercase, digit, and special character"
                );
                isValid = false;
            }

            if (citizenship.trim() === "" || isNaN(citizenship)) {
                showError("citizenshipError", "Please enter Citizenship Number");
                isValid = false;
            }
            if (regionNo.trim() === "") {
                showError("regionNoError", "Enter Constituent No");
                isValid = false;
            }
            // Validate Date of Birth
            if (dob.trim() === "") {
                showError("dobError", "Date of Birth is required");
                isValid = false;
            } else {
                let selectedDate = new Date(dob);
                let today = new Date();
                let age = today.getFullYear() - selectedDate.getFullYear();
                let monthDiff = today.getMonth() - selectedDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < selectedDate.getDate())) {
                    age--;
                }
                if (age < 18) {
                    showError("dobError", "You must be at least 18 years old to register");
                    isValid = false;
                }
            }

            // File input validation
            function validateImage(errorName, file, inputName) {
                if (!file) {
                    showError(errorName, "Please upload" + inputName);
                    return false;
                }
                const validTypes = ["image/jpeg", "image/png", "image/jpg"];
                if (!validTypes.includes(file.type)) {
                    showError(errorName, inputName + "must be a JPG/PNG image");
                    return false;
                }
                if (file.size > 2 * 1024 * 1024) {
                    showError(errorName, inputName + "size should not exceed 2MB");
                    return false;
                }
                return true;
            }

            if (!validateImage("citizenshipFrontError", citizenshipFront, "Citizenship Front Photo")) {
                isValid = false;
            }
            if (!validateImage("citizenshipBackError", citizenshipBack, "Citizenship Back Photo")) {
                isValid = false;
            }
            if (!validateImage("userPhotoError", userPhoto, "User Photo")) {
                isValid = false;
            }

            return isValid;
        }

        // Preview image function
        function previewImage(input, previewId) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(previewId);
                    preview.src = e.target.result;
                    preview.style.display = "block";
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
    <script src="../togglepassword.js"></script>
</head>

<body>
    <div id="modal1" class="modal-overlay1">
        <div class="modal-content1">
            <p id="modalMessage1"></p>
            <button onclick="closeModal1()">Close</button>
        </div>
    </div>
    <div class="container">
        <div class="left">
            <img src="../images/DMP logo.png" alt="Logo" class="logo">
            <img src="../images/vote2.jpg" alt="Center Image" class="center-image">
            <div class="left-2">
                <h1>Register Yourself as a Voter</h1>
                <p>Utilize your rights to vote digitally!</p>
            </div>
        </div>
        <div class="right">
            <form name="voterForm" action="voter_register.php" method="post" enctype="multipart/form-data"
                onsubmit="return validateForm();">
                <h2>Create Voter ID</h2>
                <div class="form-group two-columns">
                    <div class="field-error-groups">
                        <input type="text" name="name" placeholder="Full Name">
                        <span id="nameError" class="error"></span>
                    </div>
                    <div class="field-error-groups">
                        <select name="gender" id="gender">
                            <option value="default">-- Select Gender --</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="others">others</option>
                        </select>
                        <span id="genderError" class="error"></span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="address-selection">
                        <div id="district-and-region" style="flex:1.5 ;">
                            <div id="district" style="flex:1.75">
                                <label>District</label>
                                <select name="district" id="district">
                                    <option value="Kathmandu">Kathmandu</option>
                                    <option value="Lalitpur">Lalitpur</option>
                                    <option value="Bhaktapur">Bhaktapur</option>
                                    <option value="Chitwan">Chitwan</option>
                                    <option value="Rasuwa">Rasuwa</option>
                                    <option value="Kavrepalanchok">Kavrepalanchok</option>
                                    <option value="Ramechhap">Ramechhap</option>
                                    <option value="Makwanpur">Makwanpur</option>
                                    <option value="Dhading">Dhading</option>
                                    <option value="Nuwakot">Nuwakot</option>
                                    <option value="Sindhupalchoke">Sindhupalchoke</option>
                                    <option value="Dolakha">Dolakha</option>
                                    <option value="Sindhuli">Sindhuli</option>
                                </select>
                            </div>
                            <div id="constituentNo" style="flex: 1.4;">
                                <label>Constituent No.</label>
                                <input type="number" name="regionNo" id="regionNo" placeholder="eg:1">
                                <span id="regionNoError" class="error"></span>
                            </div>
                        </div>
                        <div id="localAddress" style="flex:1;">
                            <label>Local Address</label>
                            <input type="text" name="local_address" placeholder="eg:Banepa-8, Tindobato">
                            <span id="addressError" class="error"></span>
                        </div>
                    </div>
                </div>

                <div class="form-group two-columns">
                    <div class="field-error-groups">
                        <input type="text" name="email" placeholder="Email">
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
                        <label for="" style="text-wrap: nowrap;">Date of Birth:</label>
                        <input type="date" name="dateOfBirth" placeholder="DOB:">
                        <span id="dobError" class="error"></span>
                    </div>
                    <div class="field-error-groups">
                        <label for="">Citizenship Number</label>
                        <input type="text" name="citizenshipNumber" placeholder="Citizenship Number">
                        <span id="citizenshipError" class="error"></span>
                    </div>
                </div>
                <div class="form-group two-columns">
                    <div class="citizenship">
                        <label for="">Citizenship Front Photo</label>
                        <input type="file" name="citizenshipFrontPhoto" id="citizenshipFront" accept="image/*" capture
                            onchange="previewImage(this, 'frontPreview')">
                        <img id="frontPreview" src="#" alt="Citizenship Front Preview">
                        <span id="citizenshipFrontError" class="error"></span>
                    </div>
                    <div class="citizenship">
                        <label for="">Citizenship Back Photo</label>
                        <input type="file" name="citizenshipBackPhoto" id="citizenshipBack" accept="image/*" capture
                            onchange="previewImage(this, 'backPreview')">
                        <img id="backPreview" src="#" alt="Citizenship Back Preview">
                        <span id="citizenshipBackError" class="error"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label>User Photo</label>
                    <input type="file" name="userPhoto" id="userPhoto" accept="image/*" capture
                        onchange="previewImage(this, 'userPhotoPreview')">
                    <img id="userPhotoPreview" src="#" alt="User Photo Preview">
                    <span id="userPhotoError" class="error"></span>
                </div>
                <div class="form-group register">
                    <input class="submit-button" type="submit" value="Register">
                </div>
                <div class="login-direction">Already a Voter?<a href="voter_login_form.php">Login</a> </div>
            </form>
        </div>
    </div>
    <script>
        // PHP Message passed to JavaScript
        const errorMessage = <?= json_encode($errorMessage); ?>;

        // Show modal if there is a message
        if (errorMessage) {
            const modal = document.getElementById('modal1');
            const modalMessage = document.getElementById('modalMessage1');
            modalMessage.textContent = errorMessage;
            modal.style.display = 'flex';
        }

        // Function to close the modal
        function closeModal1() {
            document.getElementById('modal1').style.display = 'none';
        }
    </script>
</body>

</html>
<?php 
/*<div class="right">
    <form name="voterForm" action="voter_register.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm();">
        <h2>Create Voter ID</h2>
        <div class="form-group two-columns">
            <div class="field-error-groups">
                <input type="text" name="name" placeholder="Full Name" value="<?= htmlspecialchars($name); ?>">
                <span id="nameError" class="error"></span>
            </div>
            <div class="field-error-groups">
                <select name="gender" id="gender">
                    <option value="default" <?= $gender === "" ? 'selected' : ''; ?>>-- Select Gender --</option>
                    <option value="male" <?= $gender === "male" ? 'selected' : ''; ?>>Male</option>
                    <option value="female" <?= $gender === "female" ? 'selected' : ''; ?>>Female</option>
                    <option value="others" <?= $gender === "others" ? 'selected' : ''; ?>>Others</option>
                </select>
                <span id="genderError" class="error"></span>
            </div>
        </div>
        <div class="form-group">
            <div class="address-selection">
                <div id="district-and-region" style="flex:1.5 ;">
                    <div id="district" style="flex:1.75">
                        <label>District</label>
                        <select name="district" id="district">
                            <?php
                            $districts = ["Kathmandu", "Lalitpur", "Bhaktapur", "Chitwan", "Rasuwa", "Kavrepalanchok", "Ramechhap", "Makwanpur", "Dhading", "Nuwakot", "Sindhupalchoke", "Dolakha", "Sindhuli"];
                            foreach ($districts as $dist) {
                                $selected = $district === $dist ? 'selected' : '';
                                echo "<option value='$dist' $selected>$dist</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div id="constituentNo" style="flex: 1.4;">
                        <label>Constituent No.</label>
                        <input type="number" name="regionNo" id="regionNo" placeholder="eg:1" value="<?= htmlspecialchars($regionNo); ?>">
                        <span id="regionNoError" class="error"></span>
                    </div>
                </div>
                <div id="localAddress" style="flex:1;">
                    <label>Local Address</label>
                    <input type="text" name="local_address" placeholder="eg:Banepa-8, Tindobato" value="<?= htmlspecialchars($local_address); ?>">
                    <span id="addressError" class="error"></span>
                </div>
            </div>
        </div>
        <div class="form-group two-columns">
            <div class="field-error-groups">
                <input type="text" name="email" placeholder="Email" value="<?= htmlspecialchars($email); ?>">
                <span id="emailError" class="error"></span>
            </div>
            <div class="field-error-groups input-container">
                <input type="password" id="password" name="password" placeholder="Password" value="<?= htmlspecialchars($password); ?>">
                <i id="togglePasswordIcon" class="fas fa-eye toggle-password" onclick="togglePasswordVisibility()"></i>
                <span id="passwordError" class="error" style="margin-bottom: 8px;"></span>
            </div>
        </div>
        <div class="form-group two-columns">
            <div class="field-error-groups">
                <label for="" style="text-wrap: nowrap;">Date of Birth:</label>
                <input type="date" name="dateOfBirth" value="<?= htmlspecialchars($dateOfBirth); ?>">
                <span id="dobError" class="error"></span>
            </div>
            <div class="field-error-groups">
                <label for="">Citizenship Number</label>
                <input type="text" name="citizenshipNumber" placeholder="Citizenship Number" value="<?= htmlspecialchars($citizenshipNumber); ?>">
                <span id="citizenshipError" class="error"></span>
            </div>
        </div>
        <div class="form-group two-columns">
            <div class="citizenship">
                <label for="">Citizenship Front Photo</label>
                <input type="file" name="citizenshipFrontPhoto" id="citizenshipFront" accept="image/*" onchange="previewImage(this, 'frontPreview')">
                <img id="frontPreview" src="../uploads/<?= htmlspecialchars($citizenshipFrontPhoto); ?>" alt="Citizenship Front Preview">
                <span id="citizenshipFrontError" class="error"></span>
            </div>
            <div class="citizenship">
                <label for="">Citizenship Back Photo</label>
                <input type="file" name="citizenshipBackPhoto" id="citizenshipBack" accept="image/*" onchange="previewImage(this, 'backPreview')">
                <img id="backPreview" src="../uploads/<?= htmlspecialchars($citizenshipBackPhoto); ?>" alt="Citizenship Back Preview">
                <span id="citizenshipBackError" class="error"></span>
            </div>
        </div>
        <div class="form-group">
            <label>User Photo</label>
            <input type="file" name="userPhoto" id="userPhoto" accept="image/*" onchange="previewImage(this, 'userPhotoPreview')">
            <img id="userPhotoPreview" src="../uploads/<?= htmlspecialchars($userPhoto); ?>" alt="User Photo Preview">
            <span id="userPhotoError" class="error"></span>
        </div>
        <div class="form-group register">
            <input class="submit-button" type="submit" value="Register">
        </div>
        <div class="login-direction">Already a Voter?<a href="voter_login_form.php">Login</a></div>
    </form>
</div>
*/
?>