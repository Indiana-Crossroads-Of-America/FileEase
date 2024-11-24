<?php
// Define the directory, time limit, and list of files to ignore
$directory = '/srv/mount/files/';
$timeLimit = 7 * 24 * 60 * 60; // 7 days in seconds
$ignoreFiles = [
    'litigation_file1.txt',  // Example file to ignore
    'litigation_file2.png',  // Add more filenames as needed
    'important_document.pdf'
];

// Check if the directory exists
if (is_dir($directory)) {
    $files = scandir($directory);

    foreach ($files as $file) {
        $filePath = $directory . $file;

        // Skip directories ('.', '..')
        if (in_array($file, ['.', '..'])) {
            continue;
        }

        // Skip ignored files
        if (in_array($file, $ignoreFiles)) {
            continue;
        }

        // Check if the file is older than the time limit
        if (is_file($filePath) && (time() - filemtime($filePath)) > $timeLimit) {
            // Delete the file
            unlink($filePath);
        }
    }
}
?>

