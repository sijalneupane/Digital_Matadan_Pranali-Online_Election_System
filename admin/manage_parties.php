<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../admin/admin_login.php');
    exit;
}
$_SESSION['pageName'] = "Manage Parties";
$errorMessage = isset($_SESSION['errorMsg']) ? $_SESSION['errorMsg'] : '';
unset($_SESSION['errorMsg']); // Clear the message
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Online Voting System</title>

    <link rel="stylesheet" href="../admin/left_right_party_candidate.css">
    <link rel="stylesheet" href="../styles/modal1.css">
    <link rel="stylesheet" href="../styles/table_img.css">
    <style>
        
        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fdfdfd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form h2 {
            text-align: center;
            font-size: 1.5em;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 5px;
        }

        .form-group label {
            display: block;
            margin-bottom: 4px;
            font-weight: bold;
            color: #555;
        }

        .form-group input[type="text"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-group span {
            color: red;
            font-size: 12px;
            display: block;
            height: 16px;
        }
        input[type="submit"] {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
        @media (max-width: 768px) {
        }

        
    </style>
    <script src="../js/errorMessage_modal1.js"></script>
</head>

<body>
    <?php require '../admin/admin_navbar.php'; ?>
    <div class="content">
        <?php require_once '../home/logout_modals_html.php';
        logoutModalPhp('admin'); ?>
        <div id="modal1" class="modal-overlay1 all-modals">
            <div class="modal-content1">
                <p id="modalMessage1"></p>
                <button onclick="closeModal1()">Close</button>
            </div>
        </div>
        <div class="main-container">
            <div class="left">
                <button id="addBtn" onclick="showForm()">Add parties </button>
                <button id="viewBtn" onclick="showData()">View parties </button>
            </div>
            <div class="right">
                <div id="right1" class="right-content" style="width: 75%;">
                    <form id="partyForm" action="../admin/manage_parties_add_controller.php" method="POST"
                        enctype="multipart/form-data" onsubmit="return validateForm()">
                        <h2 id="formTitle">Add Party</h2>
                        <input type="hidden" id="partyId" name="partyId">
                        <div class="form-group">
                            <label for="partyName">Party Name:</label>
                            <input type="text" id="partyName" name="partyName">
                            <span id="partyNameError"></span>
                        </div>
                        <div class="form-group">
                            <label for="partyLeader">Party Leader:</label>
                            <input type="text" id="partyLeader" name="partyLeader">
                            <span id="partyLeaderError"></span>
                        </div>
                        <div class="form-group">
                            <label for="partyLogo">Party Logo:</label>
                            <input type="file" id="partyLogo" name="partyLogo" accept="image/*">
                            <div class="photo-preview" id="logoPreview">

                            </div>
                            <span id="partyLogoError"></span>
                        </div>
                        <div class="form-group">
                            <input type="submit" id="submitButton" value="Add Party">
                        </div>
                    </form>
                </div>

                <div id="right2" class="right-content" style="flex-direction: column;
                    align-items:center;width: 75%;">
                    <h2>View Parties</h2>
                    <div class="table-container" style="width: fit-content;" >
                        <table>
                            <thead>
                                <tr>
                                <th>Party Id</th>
                                    <th>Party Name</th>
                                    <th>Party Leader</th>
                                    <th>Party Logo</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require_once '../register_and_login/dbconnection.php';
                                $sql = "SELECT * FROM parties";
                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) { ?>
                                        <tr>
                                        <td> <?= $row['partyId'] ?> </td>
                                            <td> <?= $row['partyName'] ?> </td>
                                            <td> <?= $row['partyLeader'] ?> </td>
                                            <td><img src="../uploads/<?= $row['partyLogo'] ?>"
                                                    onclick="openModal(<?= $row['partyId'] ?>,'<?= $row['partyLogo'] ?>')"
                                                    alt='Party Logo' style='max-width: 100px;'></td>
                                            <td>
                                            <button class="delete-btn styled-btn"
                                            onclick="openDeleteModal(<?= $row['partyId'] ?>)">Delete</button>
                                                <button class="update-btn styled-btn" onclick="editParty(<?= $row['partyId'] ?>, '<?= $row['partyName'] ?>', '<?= $row['partyLeader'] ?>', '<?= $row['partyLogo'] ?>')">Edit</button>
                                            </td>
                                        </tr>
                                        <div id="delete-modal-<?= $row['partyId'] ?>" class="delete-modal all-modals">
                                            <div class="delete-modal-content">
                                                <p>Are you sure you want to delete this candidate?</p>
                                                <button class="delete-modal-btn confirm-btn"
                                                    onclick="confirmDelete(<?= $row['partyId'] ?>,'parties','<?= $row['partyLogo']?>')">Yes</button>
                                                <button class="delete-modal-btn cancel-btn"
                                                    onclick="closeDeleteModal(<?= $row['partyId'] ?>)">No</button>
                                            </div>
                                        </div>
                                        <div id="modal-<?= $row['partyId'] ?>" class="modal all-modals">
                                            <button class="close-modal" onclick="closeModal(<?= $row['partyId']?>)">&times;</button>
                                            <div class="modal-content img-modal">
                                                <h3 id="modal-title-<?=$row['partyId'] ?>" style="color: Black; text-align: center;"></h3>
                                                <img id="modal-img-<?= $row['partyId'] ?>" src="" alt="Selected Image">
                                            </div>
                                        </div>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan='4'>No parties found.</td>
                                    </tr>
                                <?php }
                                mysqli_close($conn);
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Function to show the form (right1)
        function showForm() {
            document.getElementById("right1").style.display = "block"; // Show the form
            document.getElementById("right2").style.display = "none";  // Hide the table
            document.getElementById("formTitle").innerText = "Add Party";
            document.getElementById("submitButton").innerText = "Add Party";
            document.getElementById("partyForm").action = "../admin/manage_parties_add_controller.php";
            document.getElementById("partyId").value = "";
            document.getElementById("partyName").value = "";
            document.getElementById("partyLeader").value = "";
            document.getElementById("partyLogo").value = "";
            document.getElementById("logoPreview").innerHTML = "";
        }

        // Function to show the data table (right2)
        function showData() {
            document.getElementById("right1").style.display = "none";  // Hide the form
            document.getElementById("right2").style.display = "flex"; // Show the table
        }

        // Initialize by hiding one of the sections (optional, depending on your default view)
        window.onload = function () {
            // document.getElementById("right1").style.display = "none"; // Initially hide the form
            document.getElementById("right2").style.display = "none"; // Initially hide the table
        };

        function validateForm() {
            let isValid = true;
            const partyName = document.getElementById('partyName').value;
            const partyLeader = document.getElementById('partyLeader').value;
            const partyLogo = document.getElementById('partyLogo').files[0];
            const allowedTypes = ['image/jpeg', 'image/png'];
            const maxSize = 2 * 1024 * 1024; // 2MB

            document.getElementById('partyNameError').innerText = '';
            document.getElementById('partyLeaderError').innerText = '';
            document.getElementById('partyLogoError').innerText = '';

            if (!partyName) {
                document.getElementById('partyNameError').innerText = 'Party name is required.';
                isValid = false;
            }

            if (!partyLeader) {
                document.getElementById('partyLeaderError').innerText = 'Party leader is required.';
                isValid = false;
            }
            if (!partyLogo) {
                document.getElementById('partyLogoError').innerText = 'Party logo is required.';
                isValid = false;
            } else if (!allowedTypes.includes(partyLogo.type)) {
                document.getElementById('partyLogoError').innerText = 'Only JPG, JPEG, and PNG files are allowed.';
                isValid = false;
            } else if (partyLogo.size > maxSize) {
                document.getElementById('partyLogoError').innerText = 'File size must be less than 2MB.';
                isValid = false;
            }
            return isValid;
        }

        document.getElementById('partyLogo').addEventListener('change', function (e) {
            const preview = document.getElementById('logoPreview');
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                }
                reader.readAsDataURL(file);
            }
        });
        // Show error modal if there's an error message
        const errorMessage = <?= json_encode($errorMessage); ?>;
        if (errorMessage) {
            showErrorModal(errorMessage);
        }

        //image modal
        function openModal(id, title) {

            const modal = document.getElementById('modal-' + id);
            const modalImg = document.getElementById('modal-img-' + id);
            const modalTitle = document.getElementById('modal-title-' + id);

            // Determine the image to display
            const imageSrc = event.target.src;

            // Set modal content
            modalImg.src = imageSrc;
            modalTitle.innerText = title;

            // Show modal
            modal.style.display = 'flex';
        }

        function closeModal(id) {
            const modal = document.getElementById('modal-' + id);
            modal.style.display = 'none';

            // Clear the modal content
            const modalImg = document.getElementById('modal-img-' + id);
            modalImg.src = '';
        }
        //delete confirm modal
        function openDeleteModal(partyId) {
            var modal = document.getElementById('delete-modal-' + partyId);
            modal.style.display = 'flex';
        }

        function closeDeleteModal(partyId) {
            var modal = document.getElementById('delete-modal-' + partyId);
            modal.style.display = 'none';
        }

        // Close the modal when clicking outside of the modal content
        window.onclick = function (event) {
            var modals = document.getElementsByClassName('all-modals');
            for (var i = 0; i < modals.length; i++) {
                if (event.target == modals[i]) {
                    modals[i].style.display = 'none';
                }
            }
        }
        function confirmDelete(id, table,photoPath) {
            window.location.href = `delete_party_candidate.php?table=${table}&id=${id}&photoPath=${photoPath}`;
        }
        function editParty(partyId, partyName, partyLeader, partyLogo) {
            showForm();
            document.getElementById("formTitle").innerText = "Update Party";
            document.getElementById("submitButton").innerText = "Update Party";
            document.getElementById("partyForm").action = "../admin/manage_parties_add_controller.php?id=" + partyId;
            document.getElementById("partyId").value = partyId;
            document.getElementById("partyName").value = partyName;
            document.getElementById("partyLeader").value = partyLeader;
            const partyLogoInput = document.getElementById("partyLogo");
            const filePath = `../uploads/${partyLogo}`;
            fetch(filePath)
                .then(response => response.blob())
                .then(blob => {
                    const file = new File([blob], partyLogo, { type: blob.type });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    partyLogoInput.files = dataTransfer.files;
                })
                .catch(error => console.error('Error fetching the file:', error));
            document.getElementById("logoPreview").innerHTML = `<img src="../uploads/${partyLogo}" alt="Preview">`;
        }
    </script>
</body>

</html>