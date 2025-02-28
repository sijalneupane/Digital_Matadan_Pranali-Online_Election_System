<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../admin/admin_login.php');
}
$errorMessage = isset($_SESSION['errorMsg']) ? $_SESSION['errorMsg'] : '';
unset($_SESSION['errorMsg']); //clear the message
$successMessage = isset($_SESSION['successMsg']) ? $_SESSION['successMsg'] : '';
unset($_SESSION['successMsg']); // Clear the message
$_SESSION['pageName'] = "Manage Voters";// Clear the message
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Voters</title>
    <link rel="stylesheet" href="../styles/table_img.css">
    <link rel="stylesheet" href="../admin/admin_home.css">
    <link rel="stylesheet" href="../styles/modal1.css">
    <link rel="icon" href="../images/DMP logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 90%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .buttons {
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center;
        }

        .action-btn {
            padding: 8px 15px;
            font-size: 1em;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            margin: 0 5px;
        }

        .accept-button {
            background-color: #28a745;
        }

        .accept-button:hover {
            background-color: #218838;
        }

        .send-msg-button {
            background-color: #007bff;
        }

        .send-msg-button:hover {
            background-color: #0056b3;
        }

        .decline-button,
        .delete-button {
            background-color: #dc3545;
        }

        .decline-button:hover,
        .delete-button:hover {
            background-color: #c82333;
        }

        textarea {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* search-css */
        .search-form {
            display: flex;
            flex-direction: row;
            gap: 15px;
            width: 100%;
            /* max-width: 450px; */
            margin: 10px auto;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .search-input-container {
            align-content: center;
            position: relative;
            /* flex: 1 0 40%; */
            flex: 3.5;
        }

        .search-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .search-input:focus {
            border-color: #007bff;
            outline: none;
        }

        .search-input:not(:first-child) {
            width: 15%;
        }

        #searchQuery::placeholder {
            color: #bbb;
        }

        #district,
        #regionNo {
            appearance: none;
            background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMCIgaGVpZ2h0PSI2Ij4KICA8cGF0aCBkPSJNIDAgMCAxMCAwIDUgNiIgZmlsbD0iIzY2NiIgLz4KPC9zdmc+Cg==') no-repeat right 10px center;
            background-size: 10px 6px;
            padding-right: 30px;
        }

        #district option,
        #regionNo option {
            padding: 10px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            /* table {
                font-size: 0.9em;
            } */

            table img {
                width: 50px;
                height: 50px;
            }

            .modal img {
                max-width: 90%;
            }
        }
    </style>
    <script src="../js/errorMessage_modal1.js"></script>
</head>

<body>
    <?php require '../admin/admin_navbar.php'; ?>

    <!-- Universal Modal (Only one instance) -->
    <div id="universal-modal" class="modal all-modals" style="display: none;">
        <div class="modal-content img-modal" style="position: relative;">
            <button class="close-modal" onclick="closeModal()"
                style="position: absolute; top: 10px; right: 10px; background-color:rgb(211, 56, 56); border-radius: 5px;
                padding:0px 5px;">&times;</button>
            <h3 id="modal-title" style="color: Black; text-align: center;"></h3>
            <img id="modal-img" src="" alt="Selected Image">
        </div>
    </div>

    <!-- Universal Action Modal (Only one instance) -->
    <div id="universal-action-modal" class="modal all-modals" style="display: none;">
        <div class="modal-content action-modal" style="align-items: center; gap: 20px;position: relative;">
            <button class="close-modal" onclick="closeActionModal()"
                style="position: absolute; top: 10px; right: 10px; background-color:rgb(211, 56, 56); border-radius: 5px;
                padding:0px 5px;">&times;</button>
            <h3 id="modal-action-title" style="text-align: center;"></h3>
            <div id="modal-buttons" class="buttons"></div>
        </div>
    </div>

    <div id="modal1" class="modal-overlay1 all-modals">
        <div class="modal-content1">
            <p id="modalMessage1"></p>
            <button onclick="closeModal1()">Close</button>
        </div>
    </div>
    <?php require_once '../home/logout_modals_html.php';
    logoutModalPhp('admin'); ?>
    <div class="container">
        <form onsubmit="event.preventDefault();" class="search-form">
            <div class="search-input-container">
                <input type="text" id="searchQuery" placeholder="Search by name" class="search-input">
                <i class="fas fa-search"
                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);"></i>
            </div>
            <?php require_once '../php_for_ajax/districtRegionSelect.php';
            district();
            regionNo();
            ?>
            <select class="search-input" name="voter-type" id="voter-type">
                <option value="pending">Pending Voters</option>
                <option value="verified">Verified Voters</option>
            </select>
        </form>
        <div class="table-container">
            <!-- <h3></h3> -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User Details</th>
                        <th>Date of Birth</th>
                        <th>Gender</th>
                        <th>Address</th>
                        <th>Citizenship Front</th>
                        <th>Citizenship Back</th>
                        <th>Userphoto</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="searchResults">
                    <!-- Search results will be displayed here -->
                </tbody>
            </table>
        </div>
    </div>

    <script>

        function openModal(title, imageSrc) {
            const modal = document.getElementById('universal-modal');
            const modalImg = document.getElementById('modal-img');
            const modalTitle = document.getElementById('modal-title');

            // Set modal content dynamically
            modalImg.src = imageSrc;
            modalTitle.innerText = title;

            // Show modal
            modal.style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('universal-modal').style.display = 'none';
        }

        //action handling function
        function openActionModal(id, name, email, userType) {
            const modal = document.getElementById('universal-action-modal');
            const modalTitle = document.getElementById('modal-action-title');
            const modalButtons = document.getElementById('modal-buttons');

            // Update modal title dynamically
            modalTitle.innerText = `Action for ${name}`;

            // Clear previous buttons
            modalButtons.innerHTML = '';

            if (userType === 'pending') {
                // Structure for pending users
                modalButtons.innerHTML = `
            <button class="action-btn accept-button" onclick="handleAccept(${id})">Accept</button>
            <div style="display: flex; align-items: center; border-top: 1px solid black; padding-top: 12px;">
                <textarea id="decline-message" rows="3" placeholder="Enter reason for rejection..." style="width: 100%;"></textarea>
                <button class="action-btn decline-button" onclick="handleDecline(${id}, '${email}', '${name}')">Decline</button>
            </div>
        `;
            } else if (userType === 'verified') {
                // Structure for voters
                modalButtons.innerHTML = `
            <textarea id="voter-message" rows="3" placeholder="Enter a message or reason for deletion..." style="width: 100%;"></textarea>
            <button class="action-btn send-msg-button" onclick="sendMessageOrDelete(${id}, '${email}', '${name}', 'message')">Send Message</button>
            <button class="action-btn delete-button" onclick="sendMessageOrDelete(${id}, '${email}', '${name}', 'delete')">Delete Voter</button>
        `;
            }

            // Show modal
            modal.style.display = 'flex';
        }

        function closeActionModal() {
            document.getElementById('universal-action-modal').style.display = 'none';
        }

        function handleAccept(id) {
            window.location.href = `../admin/acceptvoters.php?id=${id}`;
        }

        function handleDecline(id, email, name) {
            const message = document.getElementById('decline-message').value.trim();
            if (message === '') {
                alert('Please enter a reason for rejection.');
                return;
            }
            const encodedMessage = encodeURIComponent(message);
            window.location.href = `../admin/declinevoters.php?id=${id}&message=${encodedMessage}&email=${encodeURIComponent(email)}&name=${encodeURIComponent(name)}`;
        }

        function sendMessageOrDelete(id, email, name, action) {
            const message = document.getElementById('voter-message').value.trim();
            if (message === '') {
                alert('Please enter a message or reason for deletion.');
                return;
            }

            const encodedMessage = encodeURIComponent(message);
            window.location.href = `../admin/sendMsgOrDeleteVoters.php?id=${id}&message=${encodedMessage}&email=${encodeURIComponent(email)}&name=${encodeURIComponent(name)}&action=${action}`;
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


        // adding search-input class to the region and district select in searching area
        const selects = document.getElementsByTagName("select");
        for (let i = 0; i < selects.length; i++) {
            selects[i].classList.add('search-input');
        }

        //search funtionality
        document.addEventListener('DOMContentLoaded', function () {
            searchVoters();
            document.getElementById('searchQuery').addEventListener('input', function () {
                searchVoters();
            });
            document.getElementById('district').addEventListener('change', function () {
                setTimeout(searchVoters, 100);
            });
            document.getElementById('regionNo').addEventListener('change', function () {
                searchVoters();
            });
            document.getElementById('voter-type').addEventListener('change', function () {
                searchVoters();
            });
        });
        function searchVoters() {
            const searchQuery = document.getElementById('searchQuery').value.trim();
            const district = document.getElementById('district').value;
            const regionNo = document.getElementById('regionNo').value;
            const voterType = document.getElementById('voter-type').value;
            let isPendingStatus = (voterType === 'pending') ? true : false;
            let searchResultsBody = document.getElementById('searchResults');

            //ajax searching 
            let xhr = new XMLHttpRequest();
            xhr.open('GET', `../admin/searchVoters.php?searchQuery=${encodeURIComponent(searchQuery)}&district=${encodeURIComponent(district)}&regionNo=${encodeURIComponent(regionNo)}&voterType=${encodeURIComponent(voterType)}`, true);
            xhr.onreadystatechange = function () {
                try {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        let users = JSON.parse(xhr.responseText);
                        // console.log(users);
                        if (users.error) {
                            searchResultsBody.innerHTML = `<tr><td colspan="12">${users.message}${isPendingStatus ? ' in pending status' : ' in voter list'}</td></tr>`;
                            return;
                        }
                        searchResultsBody.innerHTML = ''; // Clear existing content
                        users.forEach(user => {
                            searchResultsBody.innerHTML += `
                                    <tr>
                                        <td>${user.id}</td>
                                        <td>
                                            <strong>Name:</strong> ${user.name}<br>
                                            <strong>Email:</strong> ${user.email}<br>
                                            <strong>Citizenship No:</strong> ${user.citizenshipNumber}
                                        </td>
                                        <td>${user.dateOfBirth}</td>
                                        <td>${user.gender}</td>
                                        <td>
                                            <strong>District:</strong> ${user.district}<br>
                                            <strong>Region No:</strong> ${user.regionNo}<br>
                                            <strong>Local Address:</strong> ${user.localAddress}<br>
                                        </td>
                                        <td><img src="../uploads/${user.citizenshipFrontPhoto}" onclick="openModal('${user.citizenshipFrontPhoto}', this.src)"></td>
                                        <td><img src="../uploads/${user.citizenshipBackPhoto}" onclick="openModal('${user.citizenshipBackPhoto}', this.src)"></td>
                                        <td><img src="../uploads/${user.userPhoto}" onclick="openModal('${user.userPhoto}', this.src)"></td>
                                        <td>
                                             ${isPendingStatus ? user.status : user.votingStatus} <br>
                                        </td>
                                        <td>
    <button class="action-btn accept-button" onclick="openActionModal(${user.id}, '${user.name}', '${user.email}', '${voterType}')">
        Actions
    </button>
</td>
                                    </tr>
                                `;
                        });
                    }
                } catch (e) {
                    console.error(e);
                }
            }
            xhr.send();
        }
    </script>
    <script>

        // Show error modal if there's an error message
        const errorMessage = <?= json_encode($errorMessage); ?>;
        const successMessage = <?= json_encode($successMessage); ?>;
        if (errorMessage) {
            showErrorModal(errorMessage);
        } else if (successMessage) {
            showErrorModal(successMessage, true);
        }
    </script>
</body>

</html>