<?php
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
            'modified' => date("d M Y, H:i", filemtime($file)) // Format modified date
        ];
    }

    return $notices;
}

$notices = getNotices($noticesFolder);

// Handle delete request (admin only)
if ($userType === 'admin' && isset($_GET['delete'])) {
    $fileToDelete = $noticesFolder . basename($_GET['delete']);
    if (file_exists($fileToDelete)) {
        unlink($fileToDelete);
        header("Location: ../home/notices.php?userType=admin");
        exit;
    }
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
            display: inline-block;
            margin: 5px;
        }
        .btn-view { background: #28a745; }
        .btn-download {  background:rgb(32, 142, 231);}
        .btn-delete { background: #dc3545; }
        .btn-back { background:rgb(102, 124, 143); margin-top: 20px; }
        .btn:hover { opacity: 0.8; }
    </style>
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
                            <a href="?userType=admin&delete=<?php echo urlencode($notice['name']); ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this notice?');">Delete</a>
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
