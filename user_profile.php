<?php
session_start();
if (!isset($_SESSION["email"])) {
    header('Location: index.html');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/register.css">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 20px;
        }

        .content {
            padding: 0;
        }

        .profile-card {
            width: 100%;
            min-height: 100%;
            padding: 20px;
            background: linear-gradient(135deg, #4b79a1, #283e51);
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            color: #f5f5f5;
        }

        .profile-card h3 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }

        .profile-card p {
            font-size: 16px;
            line-height: 1.8;
            margin: 10px 0;
        }

        .profile-card i {
            margin-right: 8px;
            color: #ffd54f;
        }

        .image-table {
            width: 70%;
            margin: 0 auto;
            border-spacing: 15px;
            text-align: center;
        }

        .image-table img {
            width: 45%;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.3s ease;
            background-color: white;
        }

        .image-table img:hover {
            transform: scale(1.1);
        }

        .button-container {
            margin-top: 20px;
            text-align: center;
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

        .logout-button {
            background-color: #dc3545;
        }

        .logout-button:hover {
            background-color: #c82333;
        }

        /* Modal Styles */
        .modal {
            display: flex;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: -1;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            position: relative;
            padding: 2%;
            max-width: 80%;
            max-height: 100%;
        }

        .modal-content img {
            width: 100%;
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

        .modal-content1 {
            background-color: white;
            position: relative;
            padding: 20px;
            /* max-width: 80%; */
            min-width: 60%;
            max-height: 80%;
            overflow: auto;
            border-radius: 10px;
        }

        .close-modal1 {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            color: black;
            cursor: pointer;
        }

        .modal-content1 form div {
            margin-bottom: 5px;
        }

        .modal-content1 form label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .modal-content1 form input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
        <script src="../js/update_validation.js"></script>
</head>

<body>
    <div class="container" style="background-color: #b9b9b9;">
        <?php include 'sidebar.php'; ?>

        <div class="content" style="padding: 0;">
            <div class="profile-card">
                <h3><i class="fas fa-user-circle"></i> <?php echo $_SESSION['name']; ?></h3>
                <p><i class="fas fa-envelope"></i> <strong>Email:</strong> <?php echo $_SESSION['email']; ?></p>
                <p><i class="fas fa-id-card"></i> <strong>Voter ID:</strong> <?php echo $_SESSION['voterId']; ?></p>
                <p><i class="fas fa-map-marker-alt"></i> <strong>District:</strong> <?php echo $_SESSION['district']; ?></p>
                <p><i class="fas fa-map-signs"></i> <strong>Election Region:</strong> <?php echo $_SESSION['election_region']; ?></p>
                <p><i class="fas fa-home"></i> <strong>Local Address:</strong> <?php echo $_SESSION['local_address']; ?></p>
                <p><i class="fas fa-birthday-cake"></i> <strong>Birth Date:</strong> <?php echo $_SESSION['birthDate']; ?></p>
                <p><i class="fa-duotone fa-solid fa-person-half-dress"></i> <strong>Gender:</strong> <?php echo $_SESSION['gender']; ?></p>
                <p><i class="fas fa-passport"></i> <strong>Citizenship Number:</strong> <?php echo $_SESSION['citizenshipNumber']; ?></p>

                <!-- Table of Images -->
                <table class="image-table">
                    <tr>
                        <td><img src="uploads/<?php echo $_SESSION['userPhoto']; ?>" alt="User Photo" onclick="openModal(this.src)"></td>
                        <td><img src="uploads/<?php echo $_SESSION['citizenshipFrontPhoto']; ?>" alt="Citizenship Image 1" onclick="openModal(this.src)"></td>
                        <td><img src="uploads/<?php echo $_SESSION['citizenshipBackPhoto']; ?>" alt="Citizenship Image 2" onclick="openModal(this.src)"></td>
                    </tr>
                    <tr>
                        <td>User Photo</td>
                        <td>Citizenship Image 1</td>
                        <td>Citizenship Image 2</td>
                    </tr>
                </table>

                <!-- Buttons -->
                <div class="button-container">
                    <a href="register_and_login/voter_logout.php" class="button logout-button"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    <button class="button" onclick="openEditModal()"><i class="fas fa-edit"></i> Edit Profile</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="imageModal" class="modal">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <div class="modal-content">
            <img id="modalImage" src="" alt="Image">
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content1">
            <span class="close-modal1" onclick="closeEditModal()">&times;</span>
            <form action="update.php?id=<?php echo $_SESSION['voterId']; ?>" method="POST" onsubmit="return validateForm();">
                <h3>Edit Profile</h3>
                <div class="form-group two-columns">
                    <div class="field-error-groups">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo $_SESSION['name']; ?>">
                        <span id="nameError" class="error"></span>
                    </div>
                    <div class="field-error-groups">
                        <label for="gender">Gender:</label>
                        <input type="text" id="gender" name="gender" value="<?php echo $_SESSION['gender']; ?>">
                        <span id="genderError" class="error"></span>
                    </div>
                </div>

                <div class="form-group two-columns">
                    <div class="field-error-groups">
                        <label for="district">District:</label>
                        <input type="text" id="district" name="district" value="<?php echo $_SESSION['district']; ?>">
                    </div>
                    <div class="field-error-groups">
                        <label for="election_region">Election Region:</label>
                        <input type="text" id="regionNo" name="regionNo" value="<?php echo $_SESSION['election_region']; ?>">
                    </div>
                </div>
                <div class="form-group two-columns">
                    <div class="field-error-groups">
                        <label for="local_address">Local Address:</label>
                        <input type="text" id="local_address" name="local_address" value="<?php echo $_SESSION['local_address']; ?>">
                        <span id="addressError" class="error"></span>
                    </div>
                    <div class="field-error-groups">
                        <label for="email">Email:</label>
                        <input type="text" id="email" name="email" value="<?php echo $_SESSION['email']; ?>" required>
                        <span id="emailError" class="error"></span>
                    </div>
                </div>

                <div class="form-group two-columns">
                    <div class="field-error-groups">
                        <label for="dateOfBirth">Birth Date:</label>
                        <input type="date" id="dateOfBirth" name="dateOfBirth" value="<?php echo $_SESSION['birthDate']; ?>">
                        <span id="dobError" class="error"></span>
                    </div>
                    <div class="field-error-groups">
                        <label for="citizenshipNumber">Citizenship Number:</label>
                        <input type="text" id="citizenshipNumber" name="citizenshipNumber" value="<?php echo $_SESSION['citizenshipNumber']; ?>">
                        <span id="citizenshipError" class="error"></span>
                    </div>
                </div>
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
            document.getElementById('imageModal').style.zIndex = '1';
        }

        function closeModal() {
            document.getElementById('imageModal').style.display = 'none';
        }

        // Edit Profile Modal
        function openEditModal() {
            document.getElementById('editModal').style.zIndex = '1';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
    </script>
    
   
</body>

</html>