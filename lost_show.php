<?php
// Include the configuration file for shared functions and constants
require_once 'config.php';

// Get the base path for navigation
$basePath = getBasePath();
// Get the URL path for uploaded media
$uploadUrlPath = getUploadUrlPath();

$lostItems = []; // Array to store lost items fetched from the JSON file
$file_path = getDataDirPath() . 'lost_items.json';

// Check if the lost_items.json file exists and read its content
if (file_exists($file_path)) {
    $jsonContent = file_get_contents($file_path);
    $lostItems = json_decode($jsonContent, true); // Decode JSON into a PHP associative array

    // Ensure that $lostItems is an array, even if the file is empty or malformed
    if (!is_array($lostItems)) {
        $lostItems = [];
    }
    // Sort items by timestamp in descending order (most recent first)
    usort($lostItems, function($a, $b) {
        // Compare timestamps; if 'timestamp' is missing, treat as older
        $timestampA = isset($a['timestamp']) ? strtotime($a['timestamp']) : 0;
        $timestampB = isset($b['timestamp']) ? strtotime($b['timestamp']) : 0;
        return $timestampB - $timestampA;
    });
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Lost Items</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* General body styling for font and background */
        body { font-family: 'Inter', sans-serif; background-color: #f0f4f8; }
        /* Container for content, centered and with padding */
        .container { max-width: 960px; margin: 0 auto; padding: 20px; }
        /* Card-like styling for main content blocks */
        .card { background-color: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 24px; }
        /* Styling for individual item cards */
        .item-card { background-color: #f8f8f8; border-radius: 8px; padding: 16px; margin-bottom: 16px; border: 1px solid #e2e8f0; }
        /* Navigation link styling */
        .nav-link { color: #3b82f6; text-decoration: none; padding: 8px 12px; border-radius: 8px; transition: background-color 0.3s ease; }
        .nav-link:hover { background-color: #bfdbfe; }
    </style>
</head>
<body class="flex flex-col min-h-screen">
    <!-- Header section with site title and main navigation -->
    <header class="bg-blue-600 text-white p-4 shadow-md">
        <div class="container flex justify-between items-center">
            <h1 class="text-3xl font-bold">Lost & Found Hub</h1>
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="<?php echo $basePath; ?>/index.php" class="nav-link text-white hover:bg-blue-700">Home</a></li>
                    <li><a href="<?php echo $basePath; ?>/lost_post.php" class="nav-link text-white hover:bg-blue-700">Post Lost Item</a></li>
                    <li><a href="<?php echo $basePath; ?>/found_post.php" class="nav-link text-white hover:bg-blue-700">Post Found Item</a></li>
                    <li><a href="<?php echo $basePath; ?>/lost_show.php" class="nav-link text-white hover:bg-blue-700">View Lost Items</a></li>
                    <li><a href="<?php echo $basePath; ?>/found_show.php" class="nav-link text-white hover:bg-blue-700">View Found Items</a></li>
                    <li><a href="<?php echo $basePath; ?>/admin_login.php" class="nav-link text-white hover:bg-blue-700">Admin</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main content area for displaying lost items -->
    <main class="flex-grow container mt-8">
        <div class="card">
            <h2 class="text-3xl font-bold text-red-700 mb-6">Lost Items</h2>

            <?php if (empty($lostItems)): // Display message if no items are found ?>
                <p class="text-gray-600">No lost items posted yet.</p>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($lostItems as $item): // Loop through each lost item ?>
                        <div class="item-card flex flex-col justify-between">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($item['title'] ?? 'N/A'); ?></h3>
                                <p class="text-gray-700 text-sm mb-1"><strong>Description:</strong> <?php echo htmlspecialchars($item['description'] ?? 'N/A'); ?></p>
                                <p class="text-gray-600 text-sm mb-1"><strong>Last Seen:</strong> <?php echo htmlspecialchars($item['location'] ?? 'N/A'); ?></p>
                                <p class="text-gray-600 text-sm mb-2"><strong>Date Lost:</strong> <?php echo htmlspecialchars($item['date'] ?? 'N/A'); ?></p>

                                <h4 class="text-lg font-semibold text-gray-700 mt-4 border-t pt-4">Owner Contact:</h4>
                                <p class="text-gray-700 text-sm mb-1"><strong>Roll Number:</strong> <?php echo htmlspecialchars($item['roll_number'] ?? 'N/A'); ?></p>
                                <p class="text-gray-700 text-sm mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($item['poster_name'] ?? 'N/A'); ?></p>
                                <p class="text-blue-700 text-sm mb-1"><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($item['email'] ?? ''); ?>"><?php echo htmlspecialchars($item['email'] ?? 'N/A'); ?></a></p>
                                <?php if (!empty($item['phone_number'])): ?>
                                    <p class="text-blue-700 text-sm mb-1"><strong>Phone:</strong> <a href="tel:<?php echo htmlspecialchars($item['phone_number']); ?>"><?php echo htmlspecialchars($item['phone_number']); ?></a></p>
                                <?php endif; ?>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <?php if (!empty($item['media_path'])): // Display uploaded media if available ?>
                                    <?php
                                        // Construct the full URL for the media file
                                        $mediaUrl = $uploadUrlPath . htmlspecialchars($item['media_path']);
                                        // Determine if it's an image or video for proper rendering
                                        $mediaType = getMediaType($item['media_path']);
                                    ?>
                                    <div class="mb-4">
                                        <?php if ($mediaType === 'image'): ?>
                                            <img src="<?php echo $mediaUrl; ?>" alt="Lost Item Image" class="w-full h-48 object-cover rounded-lg shadow-sm">
                                        <?php elseif ($mediaType === 'video'): ?>
                                            <video controls class="w-full h-48 object-cover rounded-lg shadow-sm">
                                                <source src="<?php echo $mediaUrl; ?>" type="<?php echo (strpos($item['media_path'], '.mp4') !== false) ? 'video/mp4' : ((strpos($item['media_path'], '.webm') !== false) ? 'video/webm' : 'video/ogg'); ?>">
                                                Your browser does not support the video tag.
                                            </video>
                                        <?php else: ?>
                                            <p class="text-gray-500 text-xs">Unsupported media type for: <?php echo htmlspecialchars($item['media_path']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <p class="text-gray-500 text-xs mt-1">Posted: <?php echo htmlspecialchars($item['timestamp'] ?? 'N/A'); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer section -->
    <footer class="bg-gray-800 text-white p-4 mt-8 text-center shadow-inner">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Lost & Found Hub. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
