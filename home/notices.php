<?php

date_default_timezone_set('Asia/Kathmandu');

session_start();
// Get userType from URL (default to 'guest' if not provided)
$userType = isset($_GET['userType']) ? $_GET['userType'] : 'guest';

// Define notices folder
$noticesFolder = "../notices/";

// Function to get all PDF notices
function getNotices($folderPath)
{
    $files = glob($folderPath . "*.pdf"); // Get only PDF files
    $notices = [];

    foreach ($files as $file) {
        $notices[] = [
            'name' => basename($file),
            'path' => $file,
            'modified' => date("d M Y, h:i A", filemtime($file)), // Format modified date
            'timestamp' => filemtime($file) // Add timestamp for sorting
        ];
    }

    // Sort notices by modified timestamp in descending order
    usort($notices, function ($a, $b) {
        return $b['timestamp'] - $a['timestamp'];
    });

    return $notices;
}

$notices = getNotices($noticesFolder);

// Handle delete request (admin only)
if ($userType === 'admin' && isset($_GET['delete'])) {
    $fileToDelete = $noticesFolder . basename($_GET['delete']);
    if (file_exists($fileToDelete)) {
        unlink($fileToDelete);
        echo "success";
    } else {
        echo "failure";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Notices</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef1f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            width: 70%;
            background: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background: #007bff;
            color: white;
        }
        .btn {
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            color: white;
            border: none;
            cursor: pointer;
            display: inline-block;
            margin: 5px;
        }
        .btn-view { background: #28a745; }
        .btn-download {  background:rgb(35, 118, 241);}
        .btn-delete { background: #dc3545; }
        .btn-back { background:rgb(158, 96, 182); margin-top: 20px; }
        .btn:hover { opacity: 0.8; }

        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex; align-items: center; justify-content: center;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 400px;
        }
        .close-btn {
            float: right;
            color:white;
            padding:5px 10px;
            border-radius: 5px;
            background: #dc3545;
            cursor: pointer;
        }
        .btn-delete { background: #dc3545; }
        .btn-cancel { background:rgb(31, 160, 63); }

    </style>
    <script>
        function showConfirmModal(deleteUrl, fileName) {
            const modal = document.createElement("div");
            modal.classList.add("modal-overlay");
            modal.innerHTML = `
                <div class="modal-content">
                    <span class="close-btn" onclick="closeModal(this)">&times;</span>
                    <h3>Confirm Delete</h3>
                    <p>Are you sure you want to delete "${fileName}"?</p>
                    <button class="btn btn-delete" onclick="deleteNotice('${deleteUrl}', this)">Delete</button>
                    <button class="btn btn-cancel" onclick="closeModal(this)">Cancel</button>
                </div>
            `;
            document.body.appendChild(modal);
        }

        function deleteNotice(deleteUrl, btn) {
            fetch(deleteUrl)
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === "success") {
                        showSuccessModal("Notice successfully deleted.");
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        showSuccessModal("Failed to delete notice.");
                    }
                })
                .catch(() => showSuccessModal("An error occurred."));
            closeModal(btn);
        }

        function showSuccessModal(message) {
            const modal = document.createElement("div");
            modal.classList.add("modal-overlay");
            modal.innerHTML = `
                <div class="modal-content">
                    <span class="close-btn" onclick="closeModal(this)">&times;</span>
                    <h3>Notification</h3>
                    <p>${message}</p>
                </div>
            `;
            document.body.appendChild(modal);
            setTimeout(() => closeModal(modal), 2000);
        }

        function closeModal(element) {
            let modal = element.closest(".modal-overlay");
            if (modal) {
                modal.remove();
            }
        }
    </script>
</head>
<body>
<div class="container">
    <h2>Election Notices</h2>
    <?php if (empty($notices)): ?>
        <p>No notices available.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Notice Name</th>
                <th>Last Modified</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($notices as $notice): ?>
                <tr>
                    <td><?php echo $notice['name']; ?></td>
                    <td><?php echo $notice['modified']; ?></td>
                    <td>
                        <a href="<?php echo $notice['path']; ?>" target="_blank" class="btn btn-view">View</a>
                        <a href="<?php echo $notice['path']; ?>" download class="btn btn-download">Download</a>
                        <?php if ($userType === 'admin'): ?>
                            <button class="btn btn-delete" onclick="showConfirmModal('?userType=admin&delete=<?php echo urlencode($notice['name']); ?>', '<?php echo $notice['name']; ?>')">Delete</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    <a href="<?php echo getBackUrl($userType); ?>" class="btn btn-back">Back</a>
</div>
</body>
</html>
<?php
function getBackUrl($userType) {
    switch ($userType) {
        case 'admin': return "../admin/admin_home.php";
        case 'voter': return "../home/home.php";
        default: return "../home/index.php";
    }
}
?>
