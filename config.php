<?php
// config.php
// This file contains shared functions and constants for the Lost & Found application.

/**
 * Returns the base URL path of the application.
 * This helps in constructing correct links regardless of the server's document root.
 * @return string The relative path to the application's root directory.
 */
function getBasePath() {
    // Get the directory portion of the current script's URL path.
    // For example, if script is /app/index.php, this returns /app.
    // If script is /index.php, this returns /.
    $base_path = dirname($_SERVER['PHP_SELF']);

    // If the application is in the root directory, dirname will return '/',
    // but for relative links, an empty string is often preferred.
    // Remove trailing slash for consistency, unless it's just '/'.
    return $base_path === '/' || $base_path === '\\' ? '' : rtrim($base_path, '/\\');
}

/**
 * Returns the full server path to the data directory.
 * Creates the directory if it does not exist.
 * @return string The absolute path to the data directory.
 */
function getDataDirPath() {
    $data_dir = __DIR__ . '/lostdata/';
    if (!is_dir($data_dir)) {
        // Create the directory with read/write/execute permissions for everyone (0777).
        // 'true' allows recursive creation of nested directories.
        mkdir($data_dir, 0777, true);
    }
    return $data_dir;
}

/**
 * Returns the full server path to the uploads directory.
 * Creates the directory if it does not exist.
 * @return string The absolute path to the uploads directory.
 */
function getUploadDirPath() {
    // Changed the directory from 'uploads/' to 'store/' as per your request
    $upload_dir = getDataDirPath() . 'store/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    return $upload_dir;
}

/**
 * Returns the relative URL path for accessing uploaded files from the browser.
 * @return string The URL path to the uploads directory.
 */
function getUploadUrlPath() {
    // Changed the directory from 'uploads/' to 'store/' as per your request
    // Combines the base application path with the data/store subdirectory.
    return getBasePath() . '/lostdata/store/';
}

// Constants for file upload validation
const MAX_VIDEO_SIZE = 40 * 1024 * 1024; // 40 MB in bytes
const ALLOWED_MEDIA_TYPES = [
    'image/jpeg',
    'image/png',
    'image/gif',
    'video/mp4',
    'video/webm',
    'video/ogg', // Common video formats
    'image/webp' // Modern image format
];

/**
 * Handles the file upload process, including validation and moving the file.
 * @param array $file The $_FILES array entry for the uploaded file (e.g., $_FILES['media_file']).
 * @return string|false The unique filename (e.g., 'media_xxxx.ext') on success, or an error message string on failure.
 */
function handleFileUpload($file) {
    // Check for general upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        switch ($file['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return "Uploaded file exceeds maximum allowed size.";
            case UPLOAD_ERR_PARTIAL:
                return "File was only partially uploaded.";
            case UPLOAD_ERR_NO_FILE:
                return false; // No file uploaded, not an error if optional
            case UPLOAD_ERR_NO_TMP_DIR:
                return "Missing a temporary folder for uploads.";
            case UPLOAD_ERR_CANT_WRITE:
                return "Failed to write file to disk. Check server permissions.";
            case UPLOAD_ERR_EXTENSION:
                return "A PHP extension stopped the file upload. Check PHP configuration.";
            default:
                return "An unknown upload error occurred.";
        }
    }

    // Validate file size
    if ($file['size'] > MAX_VIDEO_SIZE) {
        return "File size exceeds " . (MAX_VIDEO_SIZE / (1024 * 1024)) . " MB.";
    }

    // Validate file type using file information
    // This is more secure than relying solely on file extension
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    if (!in_array($mimeType, ALLOWED_MEDIA_TYPES)) {
        return "Invalid file type. Only common image (JPG, PNG, GIF, WebP) and video (MP4, WebM, Ogg) formats are allowed. Detected: " . $mimeType;
    }

    // Generate a unique filename to prevent overwrites and security issues
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $unique_filename = uniqid('media_', true) . '.' . $extension; // e.g., 'media_654321abc.mp4'
    $destination_path = getUploadDirPath() . $unique_filename;

    // Move the uploaded file from temporary directory to its final destination
    if (move_uploaded_file($file['tmp_name'], $destination_path)) {
        // Return only the filename; full path can be reconstructed later
        return $unique_filename;
    } else {
        return "Failed to move uploaded file. Please check directory permissions for " . getUploadDirPath();
    }
}

/**
 * Determines if a given filename corresponds to an image or video.
 * Used for conditional rendering in display pages.
 * @param string $filename The name of the file.
 * @return string 'image', 'video', or 'unknown'.
 */
function getMediaType($filename) {
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    switch ($extension) {
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
        case 'webp':
            return 'image';
        case 'mp4':
        case 'webm':
        case 'ogg':
            return 'video';
        default:
            return 'unknown';
    }
}
?>
