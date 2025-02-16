<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../admin/admin_login.php');
}
$errorMessage = isset($_SESSION['errorMsg']) ? $_SESSION['errorMsg'] : '';
unset($_SESSION['errorMsg']);
$_SESSION['pageName'] = "Verify Voters";// Clear the message
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Voters</title>
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

        .decline-button {
            background-color: #dc3545;
        }

        .decline-button:hover {
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
</head>

<body>
    <?php require '../admin/admin_navbar.php'; ?>
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
                <tbody id="pendingVoters">
                    <?php
                    // Database connection
                    require '../register_and_login/dbconnection.php';
                    // SQL Query
                    $query = "SELECT * 
                    FROM pendingstatus P
                    INNER JOIN district D ON p.dId = D.dId
                    WHERE status = 'pending'
                ";

                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $id = htmlspecialchars($row['id']);
                            $name = htmlspecialchars($row['name']);
                            $email = htmlspecialchars($row['email']);
                            $dateOfBirth = htmlspecialchars($row['dateOfBirth']);
                            $citizenshipNumber = htmlspecialchars($row['citizenshipNumber']);
                            $gender = htmlspecialchars($row['gender']);
                            $localAddress = htmlspecialchars($row['localAddress']);
                            $district = htmlspecialchars($row['district']);
                            $regionNo = htmlspecialchars($row['regionNo']);
                            $frontPhoto = htmlspecialchars($row['citizenshipFrontPhoto']);
                            $backPhoto = htmlspecialchars($row['citizenshipBackPhoto']);
                            $userPhoto = htmlspecialchars($row['userPhoto']);
                            $status = htmlspecialchars($row['status']);
                            ?>
                            <tr>
                                <td><?= $id ?></td>
                                <td>
                                    <strong>Name:</strong> <?= $name ?><br>
                                    <strong>Email:</strong> <?= $email ?><br>
                                    <strong>Citizenship No:</strong> <?= $citizenshipNumber ?>
                                </td>
                                <td><?= $dateOfBirth ?></td>
                                <td><?= $gender ?></td>
                                <td>
                                    <strong>District:</strong> <?= $district ?><br>
                                    <strong>Region No:</strong> <?= $regionNo ?><br>
                                    <strong>Local Address:</strong> <?= $localAddress ?><br>
                                </td>
                                <td><img src="../uploads/<?= $frontPhoto ?>"
                                        onclick="openModal(<?= $id ?>,'<?= $frontPhoto ?>')">
                                </td>
                                <td><img src="../uploads/<?= $backPhoto ?>" onclick="openModal(<?= $id ?>,'<?= $backPhoto ?>')">
                                </td>
                                <td><img src="../uploads/<?= $userPhoto ?>" onclick="openModal(<?= $id ?>,'<?= $userPhoto ?>')">
                                </td>
                                <!-- <td>
                                <button class="action-btn accept-button">Accept</button>
                                <button class="action-btn decline-button">Decline</button>
                            </td> -->
                                <td>
                                    <strong>Status:</strong> <?= $status ?> <br>
                                </td>
                                <td><button class="action-btn accept-button"
                                        onclick="openActionModal(<?= $id ?>)">Actions</button>
                                </td>
                            </tr>
                            <div id="modal-<?= $id ?>" class="modal all-modals">
                                <button class="close-modal" onclick="closeModal(<?= $id ?>)">&times;</button>
                                <div class="modal-content img-modal">
                                    <h3 id="modal-title-<?= $id ?>" style="color: Black; text-align: center;"></h3>
                                    <img id="modal-img-<?= $id ?>" src="" alt="Selected Image">
                                </div>
                            </div>
                            <!-- Accept/Decline Modal -->
                            <div id="action-modal-<?= $id ?>" class="modal all-modals">
                                <div class="modal-content  action-modal" style="align-items: center; gap: 20px;">
                                    <h3 style="text-align: center;">Action for ApplicationId: <?= $name ?></h3>
                                    <div class="buttons">
                                        <button class="action-btn accept-button"
                                            onclick="handleAccept(<?= $id ?>)">Accept</button>
                                        <div
                                            style="display:flex;align-items:center;border-top:1px solid black; padding-top:12px">
                                            <textarea id="decline-message-<?= $id ?>" rows="3"
                                                placeholder="Enter reason for rejection..." style="width: 100%;"></textarea>
                                            <button class="action-btn decline-button"
                                                onclick="handleDecline(<?= $id ?>,'<?= $email ?>','<?= $name ?>')">Decline</button>
                                        </div>
                                    </div>
                                    <button class="close-modal" onclick="closeActionModal(<?= $id ?>)">&times;</button>
                                </div>
                            </div>


                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="12">No pending voters to verify.</td>
                        </tr>
                        <?php
                    }
                    $conn->close();
                    ?>
                </tbody>
                <tbody id="voters">

                </tbody>
            </table>
        </div>
    </div>

    <script>

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


        function openActionModal(id) {
            document.getElementById('action-modal-' + id).style.display = 'flex';
        }

        function closeActionModal(id) {
            document.getElementById('action-modal-' + id).style.display = 'none';
        }

        function handleAccept(id) {
            // Redirect to acceptvoters.php with voter ID
            window.location.href = '../admin/acceptvoters.php?id=' + id;
        }

        function handleDecline(id, email, name) {
            // Get the decline message
            const message = document.getElementById('decline-message-' + id).value.trim();

            if (message === '') {
                alert('Please enter a reason for rejection.');
                return;
            }

            // Redirect to declinevoters.php with voter ID and message
            const encodedMessage = encodeURIComponent(message);
            window.location.href = `../admin/declinevoters.php?id=${id}&message=${encodedMessage}&email=${encodeURIComponent(email)}&name=${encodeURIComponent(name)}`;
        }// Close the modal when clicking outside of the modal content
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
            let xhr = new XMLHttpRequest();
            const pendingVotersBody = document.getElementById('pendingVoters');
            const votersBody = document.getElementById('voters');
            xhr.open('GET', `../admin/searchVoters.php?searchQuery=${encodeURIComponent(searchQuery)}&district=${encodeURIComponent(district)}&regionNo=${encodeURIComponent(regionNo)}&voterType=${encodeURIComponent(voterType)}`, true);
            xhr.onreadystatechange = function () {
                try {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        let users = JSON.parse(xhr.responseText);
                        // console.log(users);
                        if (voterType === 'pending') {
                            console.log('pending');

                            // pendingVotersBody.innerHTML = '';
                            // users.forEach(user => {
                            //     pendingVotersBody.innerHTML += `
                            // `;
                            // });
                        } else {
                            console.log('verified');

                            //     votersBody.innerHTML = '';
                            //     users.forEach(user => {
                            //         votersBody.innerHTML += `
                            // `;
                            //     });
                        }
                    }
                } catch (e) {
                    console.error(e);
                }
            }
            xhr.send();
        }
    </script>
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