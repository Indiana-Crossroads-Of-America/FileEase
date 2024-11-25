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
        echo "<p class='error'>Error: Please wait $remainingTime seconds before uploading another file.</p>";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if file size exceeds the limit
    if ($_FILES['fileToUpload']['size'] > $maxFileSize) {
        echo "<p class='error'>Error: File size exceeds the maximum limit of 70MB.</p>";
        exit;
    }

    // Restrict .bash, .sh, and .shell file uploads
    $fileExtension = pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION);
    $restrictedExtensions = ['bash', 'sh', 'shell'];
    if (in_array(strtolower($fileExtension), $restrictedExtensions)) {
        echo "<p class='error'>Error: Files with .bash, .sh, and .shell extensions are not allowed.</p>";
        exit;
    }

    // Set target directories
    $uploadDir = '/srv/mount/files/';
    $logDir = '/srv/mount/logs/';

    // Ensure directories exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    // Generate a unique filename with the original extension
    $originalFilename = basename($_FILES['fileToUpload']['name']);
    $uniqueFilename = uniqid('file_', true) . '.' . $fileExtension;
    $targetFile = $uploadDir . $uniqueFilename;

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $targetFile)) {
        // Update the last upload time in session
        $_SESSION['last_upload_time'] = time();

        // Calculate Deletion Date (24 hours from upload)
        $deletionDate = date('Y-m-d H:i:s', strtotime('+24 hours'));

        // Create a log file with the same name as the uploaded file, but as .html
        $logFile = $logDir . pathinfo($uniqueFilename, PATHINFO_FILENAME) . '.html';

        // Log file content in HTML format with "Status" as ACTIVE
        $logContent = "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Log - $originalFilename</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; color: #333; }
        h1 { font-size: 20px; margin-bottom: 10px; }
        pre { background: #f0f0f0; padding: 15px; border: 1px solid #ddd; }
        .status { color: green; font-weight: bold; }
        .deletion { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Log Details</h1>
    <pre>
    Original Filename: $originalFilename
    Uploaded Filename: $uniqueFilename
    Real IP: $userIP
    Cloudflare Ray ID: $rayID
    Upload Time: " . date('Y-m-d H:i:s') . "
    Status: <span class='status'>ACTIVE</span>
    Deletion Date: <span class='deletion'>$deletionDate</span>
    </pre>
</body>
</html>";

        file_put_contents($logFile, $logContent);

        // Generate a public URL for the uploaded file
        $fileUrl = "https://domain.tld/" . $uniqueFilename;

        // Success message with URL
        $successMessage = "<div class='success-box'>
                                <p><strong>File uploaded successfully!</strong></p>
                                <p><strong>File URL:</strong> <a href='$fileUrl' target='_blank'>$fileUrl</a></p>
                            </div>";
        echo $successMessage;
    } else {
        echo "<p class='error'>Sorry, there was an error uploading your file.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SP-RM.</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Reset some default styles */
        body, h1, p {
            margin: 0;
            padding: 0;
        }
        html {
            font-size: 16px;
            height: 100%;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background-image: url('https://mike.will-shoot-you-on.site/22dckm3c.jpg'); /* Cityscape background */
            background-size: cover;
            background-position: center;
            color: #fff;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
        }
        h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #f0f0f0;
        }
        .info-box {
            font-size: 1rem;
            margin-bottom: 20px;
            color: #ddd;
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #fff;
        }
        .form-control {
            margin-bottom: 15px;
            width: 100%;
            padding: 12px;
            border-radius: 4px;
            border: 1px solid #ddd;
            background-color: #fff;
            color: #333;
        }
        button {
            padding: 12px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            width: 100%;
            font-size: 1.1rem;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .alert {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        a {
            color: #f0f0f0;
        }
        a:hover {
            text-decoration: underline;
        }
        /* Styling for custom file input */
        .file-input-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        .file-input {
            display: none;
        }
        .file-input-label {
            display: block;
            background-color: white;
            color: #333;
            padding: 12px;
            border-radius: 4px;
            text-align: center;
            cursor: pointer;
            border: 1px solid #ddd;
            font-size: 1rem;
        }
        .file-input-label:hover {
            background-color: #f1f1f1;
        }
        /* Success message box */
        .success-box {
            background-color: white;
            color: #333;
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: center;
        }
        .success-box a {
            color: #007bff;
            text-decoration: none;
        }
        .success-box a:hover {
            text-decoration: underline;
        }

        /* File name display */
        .file-name-display {
            margin-top: 10px;
            padding: 10px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="info-box">
        <p><strong>Warning:</strong> 
    By using this tool, you confirm that the uploaded file is free of harmful content. The maximum file size allowed is 70MB, with a limit of one upload per minute. Abusive behavior may result in a ban. Files are automatically deleted after one week, and this process cannot be reversed. Uploads exceeding 100MB will trigger a "413 Request Entity Too Large" error. Malicious files may be reviewed and frozen. This tool is designed exclusively for temporary sharing of small files and is not intended for permanent storage. All file types are permitted, except for .sh, .shell, and .bash files. <strong>You are allowed up to 5 requests per 10 seconds; exceeding this limit will result in a 10-second block.</strong>
</p>
<br>
<p><strong>Abuse?</strong> <a href="https://link-to-your.abuse.portal">Report it here</a>.</p>

		  
		  </p>
        </div>

        <h1>Upload a File</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="file-input-wrapper">
                <input type="file" name="fileToUpload" id="fileToUpload" class="file-input" required>
                <label for="fileToUpload" class="file-input-label">Choose File</label>
            </div>
            <div class="file-name-display" id="fileNameDisplay">
                <!-- File name will be shown here -->
            </div>
            <button type="submit">Upload</button>
        </form>

        <div class="info-box">
            <p><strong>Your IP Address:</strong> <?= htmlspecialchars($userIP); ?></p>
            <p><strong>Cloudflare Ray ID:</strong> <?= htmlspecialchars($rayID); ?></p>
        </div>
    </div>

    <script>
        // Display the file name when the user selects a file
        const fileInput = document.getElementById('fileToUpload');
        const fileNameDisplay = document.getElementById('fileNameDisplay');

        fileInput.addEventListener('change', function() {
            const file = fileInput.files[0];
            if (file) {
                fileNameDisplay.textContent = `Selected file: ${file.name}`;
            } else {
                fileNameDisplay.textContent = '';
            }
        });
    </script>
</body>
</html>
