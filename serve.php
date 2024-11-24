<?php
// Directory for uploaded files
$directory = '/srv/mount/files/';
$tosFile = __DIR__ . '/tos_violations.json'; // Ensure this is correct for your setup

// Load flagged files
$flaggedFiles = [];
if (file_exists($tosFile)) {
    $data = json_decode(file_get_contents($tosFile), true);
    $flaggedFiles = $data['flagged_files'] ?? [];
}

// Get the requested file from the query parameter
$requestedFile = basename($_GET['file'] ?? ''); // Sanitize input
$filePath = $directory . $requestedFile;

// Check if the file is flagged for TOS violations
if (in_array($requestedFile, $flaggedFiles)) {
    // Log the access attempt (optional)
    $logMessage = date('Y-m-d H:i:s') . " - Flagged file accessed: $requestedFile from IP: {$_SERVER['REMOTE_ADDR']}\n";
    file_put_contents('/srv/mount/logs/flagged_access.log', $logMessage, FILE_APPEND);

    // Redirect to the TOS violation page
    header("Location: /public/removed.html");
    exit;
}

// Serve the file if it exists
if (file_exists($filePath)) {
    $mimeType = mime_content_type($filePath);
    header("Content-Type: $mimeType");
    header("Content-Disposition: inline; filename=\"$requestedFile\"");
    header("Content-Length: " . filesize($filePath));
    readfile($filePath);
    exit;
}

// File not found
http_response_code(404);
echo "File not found.";
exit;
?>
