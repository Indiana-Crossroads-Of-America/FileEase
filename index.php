<?php
session_start(); // Start session to track user activity

// Define maximum file size (in bytes)
$maxFileSize = 70 * 1024 * 1024; // 70MB

// Define upload cooldown time (in seconds)
$uploadCooldown = 60; // 1 minute

// Retrieve the user's real IP and Cloudflare Ray ID
$userIP = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR'];
$rayID = $_SERVER['HTTP_CF_RAY'] ?? 'Unavailable';

// Check if the user is under cooldown
if (isset($_SESSION['last_upload_time'])) {
    $timeSinceLastUpload = time() - $_SESSION['last_upload_time'];
    if ($timeSinceLastUpload < $uploadCooldown) {
        $remainingTime = $uploadCooldown - $timeSinceLastUpload;
        $error = "Error: Please wait $remainingTime seconds before uploading another file.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($error)) {
    // Check if file size exceeds the limit
    if ($_FILES['fileToUpload']['size'] > $maxFileSize) {
        $error = "Error: File size exceeds the maximum limit of 70MB.";
    } else {
        // Restrict .bash, .sh, and .shell file uploads
        $fileExtension = pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION);
        $restrictedExtensions = ['bash', 'sh', 'shell', 'html', 'php'];
        if (in_array(strtolower($fileExtension), $restrictedExtensions)) {
            $error = "Error: Files with .bash, .sh, .shell, .html, and .php extensions are not allowed.";
        } else {
            // Set target directories
            $uploadDir = '/srv/mount/files/';
            $uniqueFilename = uniqid('file_', true) . '.' . $fileExtension;
            $targetFile = $uploadDir . $uniqueFilename;

            // Ensure directories exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $targetFile)) {
                $_SESSION['last_upload_time'] = time();
                $fileUrl = "domain.tld" . $uniqueFilename;
                $successMessage = "File uploaded successfully! <a href='$fileUrl' target='_blank'>access here</a>";
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Uploader - RM Solutions</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }
        h1 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #444;
            text-align: center;
        }
        .info-box {
            background: #f8f8f8;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            color: #555;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input[type="file"] {
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 10px;
            font-size: 0.9rem;
            background: #fff;
            color: #333;
        }
        button {
            padding: 12px;
            border: none;
            border-radius: 4px;
            background-color: #6c757d;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #5a6268;
        }
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        .alert-success {
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }
        a {
            color: #0d6efd;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>File Uploader</h1>
       <p><strong>Warning:</strong> 
    By using this tool, you confirm that the uploaded file is free of harmful content. The maximum file size allowed is 70MB, with a limit of one upload per minute. Abusive behavior may result in a ban. Files are automatically deleted after one week, and this process cannot be reversed. Uploads exceeding 100MB will trigger a "413 Request Entity Too Large" error. Malicious files may be reviewed and frozen. This tool is designed exclusively for temporary sharing of small files and is not intended for permanent storage. All file types are permitted, except for .sh, .shell, and .bash files. <strong>You are allowed up to 5 requests per 10 seconds; exceeding this limit will result in a 10-second block.</strong>
</p>
<br>
<p><strong>Abuse?</strong> <a href="https://help-point.rmsolutions.tech">Report it here</a>.</p>

        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php elseif (isset($successMessage)): ?>
            <div class="alert alert-success"><?= $successMessage ?></div>
        <?php endif; ?>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="fileToUpload" required>
            <button type="submit">Upload File</button>
        </form>
    </div>
</body>
</html>
