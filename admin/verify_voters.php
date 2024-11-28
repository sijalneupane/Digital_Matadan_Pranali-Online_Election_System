<?php
session_start();
$errorMessage = isset($_SESSION['errorMsg']) ? $_SESSION['errorMsg'] : '';
unset($_SESSION['errorMsg']); 
session_destroy();// Clear the message
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Voters</title>
    <link rel="stylesheet" href="admin_home.css">
    <link rel="stylesheet" href="../register_and_login/modal.css">
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table thead {
            background-color: #00799e;
            color: #fff;
        }

        table th,
        table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        
        table td:nth-child(2)
        {
            text-align: left;
            width: 40%;
            min-width:270px ;
        }
        table td:nth-child(5)
        {
            text-align: left;
            width: 35%;
            min-width:150px ;
        }
        table thead:nth-child(6),
        table thead:nth-child(7),
        table thead:nth-child(8)
        {
            /* text-align: left; */
            width: 25%;
            min-width:50px ;
        }

        table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        table img:hover {
            transform: scale(1.1);
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

        .modal-content {
            background-color: white;
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 90%;
            overflow-x: auto;
            padding: 3%;
        }

        .modal img {
            object-fit: contain;
            max-width: 400px;
            max-height: 80%;
            border-radius: 10px;
            border: 5px solid #fff;
        }

        .close-modal {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 2rem;
            color: #fff;
            cursor: pointer;
            background: none;
            border: none;
        }

        .buttons{
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center;
        }
        .action-btn {
            padding: 8px 15px;
            font-size: 0.9rem;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            margin: 0 5px;
        }

        .accept-btn {
            background-color: #28a745;
        }

        .accept-btn:hover {
            background-color: #218838;
        }

        .decline-btn {
            background-color: #dc3545;
        }

        .decline-btn:hover {
            background-color: #c82333;
        }

        textarea {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            table {
                font-size: 0.9rem;
            }

            table img {
                width: 50px;
                height: 50px;
            }

            .action-btn {
                font-size: 0.8rem;
                padding: 5px 10px;
            }

            .modal img {
                max-width: 90%;
            }
        }
    </style>
</head>

<body>
    <?php require 'admin_navbar.php'; ?>
    <div id="modal1" class="modal-overlay1">
        <div class="modal-content1">
            <p id="modalMessage1"></p>
            <button onclick="closeModal1()">Close</button>
        </div>
    </div>
    <div class="container">
        <h3><?php echo $_SESSION['errorMsg'] ??''?></h3>
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Database connection
                $conn = new mysqli("localhost", "root", "", "online_election");

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // SQL Query
                $query = "SELECT * 
                    FROM pendingstatus P 
                    INNER JOIN localaddress la ON P.addressId = la.lid
                    INNER JOIN district D ON D.dId = la.dId
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
                        $localAddress = htmlspecialchars($row['local_address']) ;
                         $district= htmlspecialchars($row['district']) ;
                         $regionNo= htmlspecialchars($row['regionNo']);
                        $frontPhoto = htmlspecialchars($row['citizenshipFrontPhoto']);
                        $backPhoto = htmlspecialchars($row['citizenshipBackPhoto']);
                        $userPhoto = htmlspecialchars($row['userPhoto']);
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
                            <td><img src="../uploads/<?= $frontPhoto ?>" onclick="openModal(<?= $id ?>,'<?= $frontPhoto ?>')"></td>
                            <td><img src="../uploads/<?= $backPhoto ?>" onclick="openModal(<?= $id ?>,'<?= $backPhoto ?>')"></td>
                            <td><img src="../uploads/<?= $userPhoto ?>" onclick="openModal(<?= $id ?>,'<?= $userPhoto ?>')"></td>
                            <!-- <td>
                                <button class="action-btn accept-btn">Accept</button>
                                <button class="action-btn decline-btn">Decline</button>
                            </td> -->
                            <td><button class="action-btn accept-btn" onclick="openActionModal(<?= $id ?>)">Actions</button></td>
                        </tr>
                        <div id="modal-<?= $id ?>" class="modal">
                            <button class="close-modal" onclick="closeModal(<?= $id ?>)">&times;</button>
                            <div class="modal-content">
                                <h3 id="modal-title-<?= $id ?>" style="color: Black; text-align: center;"></h3>
                                <img id="modal-img-<?= $id ?>" src="" alt="Selected Image">
                            </div>
                        </div>
                        <!-- Accept/Decline Modal -->
                        <div id="action-modal-<?= $id ?>" class="modal">
                            <div class="modal-content  action-modal" style="align-items: center; gap: 20px;">
                                <h3 style="text-align: center;">Action for ApplicationId: <?= $name ?></h3>
                                <div class="buttons">
                                    <button class="action-btn accept-btn" onclick="handleAccept(<?= $id ?>)">Accept</button>
                                    <div style="display:flex;align-items:center;border-top:1px solid black; padding-top:12px">
                                        <textarea id="decline-message-<?= $id ?>" rows="3" placeholder="Enter reason for rejection..." style="width: 100%;"></textarea>
                                        <button class="action-btn decline-btn" onclick="handleDecline(<?= $id ?>,'<?= $email ?>','<?= $name ?>')">Decline</button>
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
        </table>
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
            window.location.href = 'acceptvoters.php?id=' + id;
        }

        function handleDecline(id,email,name) {
            // Get the decline message
            const message = document.getElementById('decline-message-' + id).value.trim();

            if (message === '') {
                alert('Please enter a reason for rejection.');
                return;
            }

            // Redirect to declinevoters.php with voter ID and message
            const encodedMessage = encodeURIComponent(message);
            window.location.href = `declinevoters.php?id=${id}&message=${encodedMessage}&email=${encodeURIComponent(email)}&name=${encodeURIComponent(name)}`;
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