<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$message = "";
$allowed_extensions = ['jpg', 'png', 'pdf', 'docx'];
$max_size = 5 * 1024 * 1024;
function cleanFileName($name) {
    return preg_replace("/[^A-Za-z0-9._-]/", "_", $name);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        $message = "Upload failed!";
    } else {
        $file = $_FILES['file'];

        $original = cleanFileName($file['name']);
        $size = $file['size'];
        $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $ip = $_SERVER['REMOTE_ADDR'];
        $user_id = $_SESSION['user_id'];
        if (!in_array($ext, $allowed_extensions)) {

            $log = $conn->prepare("INSERT INTO logs (user_id, ip_address, file_name, action, reason)
                                   VALUES (?, ?, ?, 'REJECTED', 'Invalid file type')");
            $log->bind_param("iss", $user_id, $ip, $original);
            $log->execute();

            $message = "Invalid file type!";
        }
        elseif ($size > $max_size) {

            $log = $conn->prepare("INSERT INTO logs (user_id, ip_address, file_name, action, reason)
                                   VALUES (?, ?, ?, 'REJECTED', 'File too large')");
            $log->bind_param("iss", $user_id, $ip, $original);
            $log->execute();

            $message = "File is too large! Maximum 5MB allowed.";
        }

        else {
            $stored = uniqid("file_", true) . "." . $ext;
            $uploadDir = __DIR__ . "/uploads/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            if (move_uploaded_file($file['tmp_name'], $uploadDir . $stored)) {
                $stmt = $conn->prepare("
                    INSERT INTO uploads (user_id, original_name, stored_name, mime, size_bytes)
                    VALUES (?, ?, ?, ?, ?)
                ");

                $mime = $file['type'];
                $stmt->bind_param("isssi", $user_id, $original, $stored, $mime, $size);
                $stmt->execute();
                $log = $conn->prepare("INSERT INTO logs (user_id, ip_address, file_name, action)
                                       VALUES (?, ?, ?, 'SUCCESS')");
                $log->bind_param("iss", $user_id, $ip, $original);
                $log->execute();

                $message = "File uploaded successfully!";
            } else {
                $message = "Upload error!";
            }
        }
    }
}
?>
<html>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="box">
    <h2>Upload File</h2>

    <?php if ($message): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <button type="submit">Upload</button>
    </form>
</div>

</body>
</html>

