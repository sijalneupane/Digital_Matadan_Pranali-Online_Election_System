<?php
session_start();
if (!isset($_SESSION["email"])) {
    header('Location: ../home/index.php');
}
// $_SESSION['allow_logout'] = true;
// $errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
// unset($_SESSION['error_message']); // Clear the message
// $successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
// unset($_SESSION['success_message']); // Clear the message
// require_once '../php_for_ajax/districtRegionSelect.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../styles/modal1.css">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        a {
            text-decoration: none;
        }

        .container {
            padding: 20px;
        }

        .content {
            padding: 0;
            background-color: #f5f5f5 !important;
        }

        /* Profile Card Container */
        .profile-card {
            /* max-width: 800px; */
           min-height: 90%;
            margin: 12px;
            padding: 20px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            font-family: Arial, sans-serif;
        }

        /* Section 1: User Photo and Personal Info */
        .profile-top {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: center;
            /* border-bottom: 1px solid #ccc; */
        }

        /* User Photo Section */
        .user-photo {
            flex: 1;
            display: flex;
            /* text-align: center; */
            flex-direction: column;
            align-items: center;
        }

        .user-photo img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 2px solid #ccc;
            object-fit: cover;
        }

        .edit-options-box {
            text-align: center;
            height: 100px;
        }

        .change-image-box {

            display: block;
            margin-top: 10px;
        }

        /* .change-image-box a {
            text-decoration: none;
        } */

        /* details summary {
    list-style-type: none;
} */
        /* CSS */
        .button-70 {
            /* background-image: linear-gradient(#8614f8 0, #760be0 100%); */
            /* background-color: grey; */
            border: 0;
            border-radius: 4px;
            box-shadow: rgba(0, 0, 0, .3) 0 5px 15px;
            box-sizing: border-box;
            color: #fff;
            color: black;
            text-decoration: underline;
            font-family: Montserrat, sans-serif;
            font-size: .9em;
            margin: 5px;
            padding: 10px 10px;
            text-align: center;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
        }

        .button-70:hover {
            cursor: pointer;
            box-shadow: rgba(0, 0, 0, .2) 0 2px 4px -1px, rgba(0, 0, 0, .14) 0 4px 5px 0, rgba(0, 0, 0, .12) 0 1px 10px 0;
            /* background:rgb(201, 182, 219); */
        }

        .button-70:active {
            box-shadow: rgba(0, 0, 0, .2) 0 5px 5px -3px, rgba(0, 0, 0, .14) 0 8px 10px 1px, rgba(0, 0, 0, .12) 0 3px 14px 2px;
            background: rgb(237, 236, 238);
        }

        .change-image-btn {
            background-image: linear-gradient(to right, rgb(19, 146, 36) 0%, rgb(95, 175, 87) 51%, rgb(18, 112, 30) 100%)
        }

        .edit-button {
            background-image: linear-gradient(to right, #314755 0%, #26a0da 51%, #314755 100%)
        }

        .change-image-btn,
        .edit-button {
            margin: 10px;
            padding: 5px 10px;
            text-align: center;
            /* text-transform: uppercase; */
            transition: 0.5s;
            background-size: 200% auto;
            color: white;
            box-shadow: 0 0 20px #eee;
            border: none;
            border-radius: 4px;
        }

        .change-image-btn:hover,
        .edit-button:hover {
            background-position: right center;
            /* change the direction of the change here */
            color: #fff;
            text-decoration: none;
            cursor: pointer;
        }

        /* #change-userPhoto-btn, */
        #edit-button {
            /* display: none; */
        }

        /* .edit-button {
            width: 110px;
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .edit-button:hover {
            background-color: #0056b3;
        } */

        /* Personal Info Section */
        .personal-info {
            flex: 2;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .personal-info i {
            color: #3b4c67;
        }

        .personal-info h3 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 1.5rem;
            color: #333;
            width: 100%;
            text-align: start;
        }

        .personal-info p {
            margin: 10px 0;
            color: #555;
            width: 48%;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        /* Section 2: Other Images */
        .profile-bottom {
            display: flex;
            /* gap: 20px; */
            margin: 10px 0;
            justify-content: center;
        }

        .image-box {
            padding-top: 50px;
            /* width: 300; */
            position: relative;
            flex: 1;
            justify-content: center;
            text-align: center;
            gap: 10px;
            /* border: 1px solid #ccc;*/
        }

        .image-box img {
            width: 150px;
            height: 150px;
            object-fit: contain;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .image-box p {
            margin: 5px;
            color: #666;
            font-weight: bold;
            font-size: 20px;
            position: absolute;
            top: 0;
            left: 50%;
            /* Move the item to the middle */
            transform: translateX(-50%);
            /* Adjust for the item's own width */
            text-align: center;
        }

        .image-box p::after {
            content: '';
            display: block;
            width: 60%;
            height: 1.5;
            background-color: #b98181;
            margin: 0 auto;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            bottom: -5px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            right: 0;
            width: 85%;
            /* min-width: 1000px; */
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            z-index: 5;
            justify-content: center;
            align-items: center;
            transition: all 0.3s ease-in-out;
        }

        .modal-content {
            background-color: #ffffff0f;
            position: relative;
            overflow: auto;
            text-align: center;
            /* max-width: 70%; */
            width: 65%;
            max-height: 100%;
        }

        .modal-content img {
            width: 60%;
            min-width: 300px;
            object-fit: contain;
            aspect-ratio: 1 / 1;
            height: auto;
            border-radius: 10px;
        }

        .close-modal {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            color: white;
            cursor: pointer;
        }

        .modal-content2 {
            background-color: white;
            position: relative;
            padding: 20px;
            /* max-width: 80%; */
            min-width: 60%;
            max-height: 83%;
            overflow: auto;
            border-radius: 10px;
        }

        .close-modal2 {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            color: black;
            cursor: pointer;
        }

        .modal-content2 form div {
            margin-bottom: 5px;
        }

        .modal-content2 form label {
            font-weight: bold;
            color: #555;
        }

        .two-columns {
            display: flex;
            column-gap: 20px;
        }

        .field-error-groups {
            flex: 1;
        }

        .two-columns input {
            width: calc(50% - 10px);
        }

        .field-error-groups input {
            width: 100%;
        }

        .button {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #0056b3;
        }


        .modal-content2 form input:not([type="submit"]),
        .modal-content2 form select {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
            transition: all 0.3s ease;
        }

        .modal-content2 form input:not([type="submit"]):focus,
        .modal-content2 form select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            background-color: #ffffff;
        }

        .modal-content2 form select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="gray" class="bi bi-chevron-down" viewBox="0 0 16 16"%3e%3cpath fill-rule="evenodd" d="M1.646 5.646a.5.5 0 0 1 .708 0L8 11.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/%3e%3c/svg%3e') no-repeat right 10px center;
            background-size: 16px;
            padding-right: 30px;
        }

        .modal-content2 form input[type="submit"] {
            width: 80%;
            margin: 0;
        }

        .error {
            color: rgb(175, 0, 0);
            font-size: 12px;
            display: block;
            padding: 3px 0px 3px 6px;
            height: 18px;
        }

        #change-userPhoto-btn {
            display: none;
            background-color: green;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }

        #cancel-image-0,
        #cancel-image-1,
        #cancel-image-2 {
            display: none;
            background-color: rgb(231, 57, 57);
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
        }

        #cancel-image-0:hover,
        #cancel-image-1:hover,
        #cancel-image-2:hover {
            background-color: #ff0000;
        }

        .new-image-container {
            /* margin-top: 15px; */
            display: flex;
            /* align-items: center; */
            position: relative;
            justify-content: center;
            gap: 10px;
        }

        .arrow {
            align-self: center;
            font-size: 24px;
        }

        #make-changes-userPhoto {
            background-image: linear-gradient(to right, rgb(19, 146, 36) 0%, rgb(95, 175, 87) 51%, rgb(18, 112, 30) 100%);
            color: white;
            padding: 5px 15px;
            border: none;
            cursor: pointer;
            background-size: 200% auto;
            border-radius: 3px;
            transition: 0.5s;
            font-size: 16px;
        }

        .make-changes-btn {
            /* margin-bottom: 15px; */
            /* background-color: #28a745; */
            background-image: linear-gradient(to right, rgb(19, 146, 36) 0%, rgb(95, 175, 87) 51%, rgb(18, 112, 30) 100%);
            color: white;
            padding: 5px 15px;
            border: none;
            position: absolute;
            bottom: 0;
            right: 20;
            cursor: pointer;
            background-size: 200% auto;
            border-radius: 3px;
            transition: 0.5s;
            font-size: 16px;
        }

        .make-changes-btn:hover {
            /* background-color: #218838; */
            background-position: right center;
        }

        .change-password-container {
            text-align: end;
            width: 100%;
            margin-top: 7%;
        }

        .change-password-link {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .change-password-link:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .modal {
                width: 100%;
            }

            #cancel-and-apply-box {
                flex-direction: column;
                gap: 10px;
            }

            .personal-info p {
                width: 100%;
            }

            .profile-bottom {
                flex-direction: column;
                gap: 20px;
            }
        }
    </style>
    <script src="../js/update_validation.js"></script>
    <!-- <script src="../js/errorMessage_modal1.js"></script> -->
    <!-- <script src="../js/getRegion_ajax.js" defer></script> -->
</head>

<body>
    <div class="container" style="background-color: #b9b9b9;">
        <?php include '../home/sidebar.php'; ?>
        <div class="content" style="padding: 0;">
            <div class="profile-card">
                <!-- Section 1: User Photo and Personal Info -->
                <div class="profile-top">
                    <div class="user-photo">
                        <img src="../uploads/<?php echo $_SESSION['userPhoto']; ?>" alt="User Photo" id="userPhoto"
                            onclick="openModal(this.src)">
                        <!-- <div class="" id="cancel-and-apply-box" style="display: none; margin-top: 15px;  gap: 20px;">
                            <button id="make-changes-userPhoto" style="display: none;"><i class="fa fa-check"
                                    aria-hidden="true"></i> Apply</button>
                            <button id="cancel-image-0" style="display: none;"><i class="fa fa-times"
                                    aria-hidden="true"></i> Cancel</button>
                        </div> -->
                        <div class="edit-options-box" id="edit-options-box">
                            <!-- <button class="button-70" id="toggle-edit-buttons-visibility">Edit Profile</button><br>
                            <label for="change-userPhoto" class="change-image-btn" id="change-userPhoto-btn">
                                <i class="fa fa-picture-o" aria-hidden="true"></i> Change
                            </label>
                            <input type="file" id="change-userPhoto" accept="image/*" style="display: none;"> -->
                            <button class="edit-button" onclick="openEditModal()" id="edit-button">
                                <i class="fas fa-edit"></i> Update Details
                            </button>
                        </div>
                    </div>

                    <!-- Hidden form to submit data -->
                    <form id="updatePhotoForm"
                        action="../register_and_login/user_photo_update.php?oldImageName=<?= $_SESSION['userPhoto'] ?>&type=userPhoto"
                        method="POST" enctype="multipart/form-data" style="display: none;">
                        <input type="hidden" name="voterId" value="<?php echo $_SESSION['voterId']; ?>">
                        <input type="file" name="newImage" id="hiddenFileInput">
                    </form>
                    <div class="personal-info">
                        <h3><i class="fas fa-user-circle"></i> <?php echo $_SESSION['name']; ?></h3>
                        <p><i class="fas fa-envelope"></i> <strong>Email:</strong> <?php echo $_SESSION['email']; ?></p>
                        <p><i class="fas fa-id-card"></i> <strong>Voter ID:</strong> <?php echo $_SESSION['voterId']; ?>
                        </p>
                        <p><i class="fas fa-map-marker-alt"></i> <strong>District:</strong>
                            <?php echo $_SESSION['district']; ?></p>
                        <p><i class="fas fa-map-signs"></i> <strong>Election Region:</strong>
                            <?php echo $_SESSION['election_region']; ?></p>
                        <p><i class="fas fa-home"></i> <strong>Local Address:</strong>
                            <?php echo $_SESSION['localAddress']; ?></p>
                        <p><i class="fas fa-birthday-cake"></i> <strong>Birth Date:</strong>
                            <?php echo $_SESSION['birthDate']; ?></p>
                        <p><i class="fas fa-venus-mars"></i> <strong>Gender:</strong> <?php echo $_SESSION['gender']; ?>
                        </p>
                        <p><i class="fas fa-passport"></i> <strong>Citizenship Number:</strong>
                            <?php echo $_SESSION['citizenshipNumber']; ?></p>
                    </div>
                </div>

                <!-- Section 2: Other Images -->
                <div class="profile-bottom">
                    <div class="image-box" id="box1">
                        <p>Citizenship Front</p>
                        <div class="old-image">
                            <img src="../uploads/<?php echo $_SESSION['citizenshipFrontPhoto']; ?>"
                                alt="Citizenship Front" id="currentImage1" onclick="openModal(this.src)">
                            <!-- <div class="change-image-box">
                                <label for="change-citizenshipFront" class="change-image-btn" id="change-label-1">
                                    <i class="fa fa-picture-o" aria-hidden="true"></i> Change
                                </label>
                                <input type="file" id="change-citizenshipFront" accept="image/*"
                                    onchange="previewNewImage(this, 'box1','change-label-1','cancel-image-1')"
                                    style="display: none;">
                                <button id="cancel-image-1"><i class="fa fa-times" aria-hidden="true"></i>
                                    Cancel</button>

                            </div> -->
                        </div>
                        <!-- <div id="newImageContainer1" class="new-image-container" style="display: none;">
                            <span class="arrow">→</span>
                            <img id="newImagePreview1" alt="New Image Preview">
                            <button class="make-changes-btn"
                                onclick="confirmChange('box1','change-label-1','cancel-image-1')"><i class="fa fa-check"
                                    aria-hidden="true"></i> Apply</button>
                        </div> -->
                    </div>

                    <div class="image-box" id="box2">
                        <p>Citizenship Back</p>
                        <div class="old-image">
                            <img src="../uploads/<?php echo $_SESSION['citizenshipBackPhoto']; ?>"
                                alt="Citizenship Back" id="currentImage2" onclick="openModal(this.src)">
                            <!-- <div class="change-image-box">
                                <label for="change-citizenshipBack" class="change-image-btn" id="change-label-2">
                                    <i class="fa fa-picture-o" aria-hidden="true"></i> Change
                                </label>
                                <input type="file" id="change-citizenshipBack" accept="image/*"
                                    onchange="previewNewImage(this, 'box2','change-label-2','cancel-image-2')"
                                    style="display: none;">
                                <button id="cancel-image-2"><i class="fa fa-times" aria-hidden="true"></i>
                                    Cancel</button>
                            </div> -->
                        </div>
                        <!--    <div id="newImageContainer2" class="new-image-container" style="display: none;">
                            <span class="arrow">→</span>
                            <img id="newImagePreview2" alt="New Image Preview">
                            <button class="make-changes-btn"
                                onclick="confirmChange('box2','change-label-2','cancel-image-2')"><i class="fa fa-check"
                                    aria-hidden="true"></i> Apply</button>
                        </div> -->
                    </div>
                </div>
                <div class="change-password-container">
                    <a href="../forgotpassword/reset_password.php?id=<?= $_SESSION['voterId']; ?>"
                        class="change-password-link">Change Password &rarr;</a>
                </div>
            </div>
            <!--Image Modal -->
            <div id="imageModal" class="modal all-modals">
                <span class="close-modal" onclick="closeModal()">&times;</span>
                <div class="modal-content">
                    <img id="modalImage" src="" alt="Image">
                </div>
            </div>
            <!-- Change Image Modal -->
            <div class="modal" id="changeImageModal">
                <div class="modal-header">Upload a new image</div>
                <input type="file" id="imageInput" accept=".jpg,.jpeg,.png">
                <div class="modal-footer">
                    <button id="cancelChangeBtn"><i class="fa fa-times" aria-hidden="true"></i> Cancel</button>
                    <button id="submitChangeBtn">Change</button>
                </div>
            </div>

            <!-- Confirmation Modal -->
            <div class="modal" id="confirmationModal">
                <div class="modal-header">Confirm Update</div>
                <p>Are you sure you want to update the image?</p>
                <div class="modal-footer">
                    <button id="cancelUpdateBtn"><i class="fa fa-times" aria-hidden="true"></i> Cancel</button>
                    <button id="confirmUpdateBtn">OK</button>
                </div>
            </div>

            <div class="modal-overlay" id="modalOverlay"></div>
            <?php include '../home/footer.php'; ?>
        </div>
    </div>
    <!-- Error modal-->
    <!-- <div id="modal1" class="modal-overlay1">
        <div class="modal-content1">
            <p id="modalMessage1"></p>
            <button onclick="closeModal1()">Close</button>
        </div>
    </div> -->

    <!-- logout Modal -->
    <!-- Modal -->
    <?php require_once '../home/logout_modals_html.php';
    logoutModalPhp("voter"); ?>
    <!-- Edit Profile Modal -->
    <div id="editModal" class="modal all-modals">
        <div class="modal-content2">
            <span class="close-modal2" onclick="closeEditModal()">&times;</span>
            <form action="../register_and_login/user_profile_update.php" method="POST"
                onsubmit="return validateForm();">
                <h3>Edit Profile</h3>
                <!-- <div class="form-group two-columns">
                    <div class="field-error-groups">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="<?php //echo $_SESSION['name']; ?>">
                        <span id="nameError" class="error"></span>
                    </div>
                    <div class="field-error-groups">
                        <label for="gender">Gender:</label>
                        <select id="gender" name="gender">
                            <option value="male" <?php // echo ($_SESSION['gender'] == 'male') ? 'selected' : ''; ?>>Male
                            </option>
                            <option value="female" <?php // echo ($_SESSION['gender'] == 'female') ? 'selected' : ''; ?>>
                                Female</option>
                            <option value="other" <?php // echo ($_SESSION['gender'] == 'other') ? 'selected' : ''; ?>>Other
                            </option>
                        </select>
                        <span id="genderError" class="error"></span>
                    </div>
                </div> -->

                <!-- <div class="form-group two-columns">
                    <div class="field-error-groups">
                        <label for="district">District:</label>
                        <?php // district($_SESSION['district']); ?>
                        <span id="districtError" class="error"></span>
                    </div>
                    <div class="field-error-groups">
                        <label for="regionNo">Election Region:</label>
                        <?php // regionNo($_SESSION['election_region']); ?>
                        <span id="regionNoError" class="error"></span>
                    </div>
                </div> -->
                <div class="form-group two-columns">
                    <!-- <div class="field-error-groups">
                        <label for="local_address">Local Address:</label>
                        <input type="text" id="local_address" name="local_address"
                            value="<?php //echo $_SESSION['localAddress']; ?>">
                        <span id="addressError" class="error"></span>
                    </div> -->
                    <div class="field-error-groups">
                        <label for="email">Email:</label>
                        <input type="text" id="email" name="email" value="<?php echo $_SESSION['email']; ?>">
                        <span id="emailError" class="error"></span>
                    </div>
                </div>

                <!-- <div class="form-group two-columns">
                    <div class="field-error-groups">
                        <label for="dateOfBirth">Birth Date:</label>
                        <input type="date" id="dateOfBirth" name="dateOfBirth"
                            value="<?php // echo $_SESSION['birthDate']; ?>">
                        <span id="dobError" class="error"></span>
                    </div>
                    <div class="field-error-groups">
                        <label for="citizenshipNumber">Citizenship Number:</label>
                        <input type="text" id="citizenshipNumber" name="citizenshipNumber"
                            value="<?php // echo $_SESSION['citizenshipNumber']; ?>">
                        <span id="citizenshipError" class="error"></span>
                    </div>
                </div> -->
                <div style="margin-top: 20px; text-align: center;">
                    <input type="submit" class="button" value="Update Profile">
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Image Modal
        function openModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('imageModal').style.display = 'none';
            document.getElementById('modalImage').src = '';
        }

        // Store the initial values from session variables
        const initialFormValues = {
            name: "<?php echo $_SESSION['name']; ?>",
            gender: "<?php echo $_SESSION['gender']; ?>",
            district: "<?php echo $_SESSION['district']; ?>",
            election_region: "<?php echo $_SESSION['election_region']; ?>",
            local_address: "<?php echo $_SESSION['localAddress']; ?>",
            email: "<?php echo $_SESSION['email']; ?>",
            birthDate: "<?php echo $_SESSION['birthDate']; ?>",
            citizenshipNumber: "<?php echo $_SESSION['citizenshipNumber']; ?>"
        };

        // Function to populate form with initial values
        function resetFormValues() {
            // document.getElementById('name').value = initialFormValues.name;
            // document.getElementById('gender').value = initialFormValues.gender;
            // document.getElementById('district').value = initialFormValues.district;
            // document.getElementById('regionNo').value = initialFormValues.election_region;
            // document.getElementById('local_address').value = initialFormValues.local_address;
            document.getElementById('email').value = initialFormValues.email;
            // document.getElementById('dateOfBirth').value = initialFormValues.birthDate;
            // document.getElementById('citizenshipNumber').value = initialFormValues.citizenshipNumber;
        }

        // Edit Profile Modal
        function openEditModal() {
            document.getElementById('editModal').style.display = 'flex';
            resetFormValues(); // Populate form with current session values
            clearErrors();
        }
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
            resetFormValues(); // Reset form fields to initial values
        }

        // Show error modal if there's an error message
        // const errorMessage = <= json_encode($errorMessage); ?>;
        // const successMessage = <= json_encode($successMessage); ?>;
        // if (errorMessage) {
        //     showErrorModal(errorMessage);
        // } else if (successMessage) {
        //     showErrorModal(successMessage, true);
        // }

        // Close image modal when clicking outside of the image
        window.onclick = function (event) {
            var imageModal = document.getElementById('imageModal');
            if (event.target == imageModal) {
                closeModal();
            }

            // Closing other modals when clicked outside
            var modals = document.getElementsByClassName('all-modals');
            for (var i = 0; i < modals.length; i++) {
                if (event.target == modals[i]) {
                    modals[i].style.display = 'none';
                }
            }
        }

        //show and hide change and edit toggle-edit-buttons-visibility
        // const toggleEditBtn = document.getElementById('toggle-edit-buttons-visibility');
        // toggleEditBtn.addEventListener('click', function () {
        //     var changeUserPhotoBtn = document.getElementById('change-userPhoto-btn');
        //     var editButton = document.getElementById('edit-button');
        //     if (toggleEditBtn.textContent === 'Edit Profile') {
        //         changeUserPhotoBtn.style.display = 'inline-block';
        //         editButton.style.display = 'inline-block';
        //         toggleEditBtn.textContent = 'Hide Options';
        //     } else if (toggleEditBtn.textContent === 'Hide Options') {
        //         toggleEditBtn.textContent = 'Edit Profile';
        //         changeUserPhotoBtn.style.display = 'none';
        //         editButton.style.display = 'none';
        //     }
        // });
    </script>
    <script>
        // function previewNewImage(input, boxId, labelId, cancelButton) {
        //     document.getElementById(labelId).style.display = "none";
        //     const cancelBtn = document.getElementById(cancelButton);
        //     cancelBtn.style.display = "inline-block";
        //     cancelBtn.addEventListener('click', function () {
        //         cancelBtn.style.display = "none";
        //         resetNewImage(boxId, labelId, cancelButton);

        //     });

        //     document.getElementById(boxId).style.display = "flex";
        //     const file = input.files[0];
        //     if (file) {
        //         const newImageContainer = document.getElementById(`newImageContainer${boxId.slice(-1)}`);
        //         const newImagePreview = document.getElementById(`newImagePreview${boxId.slice(-1)}`);
        //         const reader = new FileReader();

        //         reader.onload = function (e) {
        //             newImagePreview.src = e.target.result;
        //             newImageContainer.style.display = "flex";
        //         };

        //         reader.readAsDataURL(file);
        //     }
        // }

        // function confirmChange(boxId, labelId, cancelBtn) {
        //     const confirmation = confirm("Do you want to confirm this change?");
        //     if (confirmation) {
        //         const currentImage = document.getElementById(`currentImage${boxId.slice(-1)}`);
        //         const newImagePreview = document.getElementById(`newImagePreview${boxId.slice(-1)}`);
        //         let oldImageName = '';
        //         let type = '';
        //         if (boxId == 'box1') {
        //             type = 'citizenshipFrontPhoto';
        //             oldImageName = '<php echo $_SESSION['citizenshipFrontPhoto']; ?>';
        //         } else {
        //             type = 'citizenshipBackPhoto';
        //             oldImageName = '<php echo $_SESSION['citizenshipBackPhoto']; ?>';
        //         }
        //         // Create a form to submit the image change
        //         const form = document.createElement('form');
        //         form.style.display = 'none';
        //         form.method = 'POST';
        //         form.action = '../register_and_login/user_photo_update.php?oldImageName=' + oldImageName + '&type=' + type;
        //         form.enctype = 'multipart/form-data';

        //         // Create an input element to hold the image file
        //         const input = document.createElement('input');
        //         input.type = 'hidden';
        //         input.name = 'imageData';
        //         const fileInput = document.querySelector(`#${boxId} input[type="file"]`);
        //         input.type = 'file';
        //         input.name = 'newImage';
        //         input.files = fileInput.files;

        //         // Create an input element to hold the box ID
        //         const voterId = document.createElement('input');
        //         voterId.type = 'hidden';
        //         voterId.name = 'voterId';
        // voterId.value = <= //json_encode($_SESSION['voterId']); ?>;

        //         // Append the inputs to the form
        //         form.appendChild(input);
        //         form.appendChild(voterId);

        //         // Append the form to the body and submit it
        //         document.body.appendChild(form);
        //         form.submit();
        //     } else {
        //         // Cancel the change
        //         resetNewImage(boxId, labelId, cancelBtn);
        //     }
        // }

        // function resetNewImage(boxId, labelId, cancelButton) {
        //     document.getElementById(labelId).style.display = "inline";
        //     document.getElementById(cancelButton).style.display = "none";
        //     const newImageContainer = document.getElementById(`newImageContainer${boxId.slice(-1)}`);
        //     const newImagePreview = document.getElementById(`newImagePreview${boxId.slice(-1)}`);
        //     const fileInput = document.querySelector(`#${boxId} input[type="file"]`);

        //     // Clear the file input
        //     fileInput.value = "";

        //     // Hide the new image container
        //     newImageContainer.style.display = "none";

        //     // Clear the new image preview
        //     newImagePreview.src = "";
        // }
    </script>
    <script>
        // document.getElementById('change-userPhoto').addEventListener('change', function (event) {
        //     document.getElementById('edit-options-box').style.display = 'none';
        //     const fileInput = event.target;
        //     const newImage = fileInput.files[0];

        //     if (newImage) {
        //         const reader = new FileReader();
        //         reader.onload = function (e) {
        //             // Show the new image preview
        //             document.getElementById('userPhoto').src = e.target.result;

        //             // Show cancel and apply changes buttons
        //             document.getElementById('cancel-and-apply-box').style.display = 'flex';
        //             document.getElementById('cancel-image-0').style.display = 'block';
        //             document.getElementById('make-changes-userPhoto').style.display = 'block';

        //             // Set the selected file to the hidden file input for form submission
        //             document.getElementById('hiddenFileInput').files = fileInput.files;
        //         };
        //         reader.readAsDataURL(newImage);
        //     }
        // });

        // document.getElementById('cancel-image-0').addEventListener('click', function () {
        //     resetProfileImg();
        // });
        // function resetProfileImg() {
        //     // Reset to the original image
        //     document.getElementById('userPhoto').src = "../uploads/<php echo $_SESSION['userPhoto']; ?>";

        //     // Hide cancel and apply changes buttons

        //     document.getElementById('cancel-and-apply-box').style.display = 'none';
        //     document.getElementById('cancel-image-0').style.display = 'none';
        //     document.getElementById('make-changes-userPhoto').style.display = 'none';

        //     // Clear the file input
        //     document.getElementById('change-userPhoto').value = '';
        //     document.getElementById('hiddenFileInput').value = '';
        //     document.getElementById('hiddenFileInput').files = null;
        //     document.getElementById('edit-options-box').style.display = 'block';
        // }
        // document.getElementById('make-changes-userPhoto').addEventListener('click', function () {
        //     const newImageFile = document.getElementById('hiddenFileInput').files[0];

        //     if (newImageFile) {
        //         const confirmApply = confirm('Are you sure you want to apply the changes?');
        //         if (confirmApply) {
        //             // Submit the form
        //             document.getElementById('updatePhotoForm').submit();
        //         } else if (!confirmApply) {
        //             resetProfileImg();
        //         }
        //     } else {
        //         alert('Please select an image before applying changes.');
        //     }
        // });
    </script>
</body>

</html>