<?php
// Include the configuration file for shared functions and constants
require_once 'config.php';

// Get the base path for navigation
$basePath = getBasePath();
// Get the URL path for uploaded media
$uploadUrlPath = getUploadUrlPath();

// *** Removed: session_start(); and all authentication checks ***
// This page is now publicly accessible for management actions.

$message = ''; // To store success or error messages
$messageType = ''; // To determine the styling of the message (success/error)

$postType = htmlspecialchars($_GET['type'] ?? ''); // Get the type of posts to manage (lost or found)
$items = []; // Array to hold the items for display
$file_path = ''; // Will be set based on $postType
$top_posts_file_path = getDataDirPath() . 'top_posts.json'; // Path for top posts tracking

// Load existing top posts data
$currentTopPosts = [];
if (file_exists($top_posts_file_path)) {
    $jsonContent = file_get_contents($top_posts_file_path);
    $currentTopPosts = json_decode($jsonContent, true);
    if (!is_array($currentTopPosts)) {
        $currentTopPosts = [];
    }
}

// Determine which JSON file to read/write based on the 'type' GET parameter
if ($postType === 'lost') {
    $file_path = getDataDirPath() . 'lost_items.json';
    $pageTitle = 'Manage Lost Items';
    $headingColor = 'text-red-700';
} elseif ($postType === 'found') {
    $file_path = getDataDirPath() . 'found_items.json';
    $pageTitle = 'Manage Found Items';
    $headingColor = 'text-green-700';
} else {
    // Default to managing lost items if type is not specified or invalid
    $file_path = getDataDirPath() . 'lost_items.json';
    $postType = 'lost';
    $pageTitle = 'Manage Lost Items (Default)';
    $headingColor = 'text-red-700';
}

// Function to save top posts data
function saveTopPosts($data, $filePath) {
    return file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
}

// Handle actions (Delete, Make Top, Remove Top) - No login check here
if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Removed session check condition
    if (isset($_POST['delete_id'])) {
        $deleteId = htmlspecialchars($_POST['delete_id']);

        if (file_exists($file_path)) {
            $jsonContent = file_get_contents($file_path);
            $existingData = json_decode($jsonContent, true);

            if (is_array($existingData)) {
                $initialCount = count($existingData);
                $deleted_item_media_path = null;

                $updatedData = array_filter($existingData, function($item) use ($deleteId, &$deleted_item_media_path) {
                    if (($item['id'] ?? null) === $deleteId) {
                        $deleted_item_media_path = $item['media_path'] ?? null;
                        return false;
                    }
                    return true;
                });

                if (count($updatedData) < $initialCount) {
                    if ($deleted_item_media_path && file_exists(getUploadDirPath() . $deleted_item_media_path)) {
                        unlink(getUploadDirPath() . $deleted_item_media_path);
                    }

                    if (file_put_contents($file_path, json_encode($updatedData, JSON_PRETTY_PRINT))) {
                        $message = 'Item and associated media (if any) deleted successfully.';
                        $messageType = 'success';

                        // Also remove from top posts if it was a top post
                        if (isset($currentTopPosts[$postType]) && $currentTopPosts[$postType] === $deleteId) {
                            unset($currentTopPosts[$postType]);
                            saveTopPosts($currentTopPosts, $top_posts_file_path);
                        }
                    } else {
                        $message = 'Error deleting item. File write failed. Check permissions for ' . $file_path;
                        $messageType = 'error';
                    }
                } else {
                    $message = 'Item not found for deletion.';
                    $messageType = 'error';
                }
            } else {
                $message = 'Error: Data file is malformed or empty.';
                $messageType = 'error';
            }
        } else {
            $message = 'Data file not found at ' . $file_path;
            $messageType = 'error';
        }
    }
    // Handle "Make Top Post" action
    elseif (isset($_POST['make_top_id'])) {
        $makeTopId = htmlspecialchars($_POST['make_top_id']);
        $currentTopPosts[$postType] = $makeTopId;
        if (saveTopPosts($currentTopPosts, $top_posts_file_path)) {
            $message = 'Item successfully set as top post!';
            $messageType = 'success';
        } else {
            $message = 'Failed to set item as top post. Check file permissions for ' . $top_posts_file_path;
            $messageType = 'error';
        }
    }
    // Handle "Remove from Top" action
    elseif (isset($_POST['remove_top_id'])) {
        if (isset($currentTopPosts[$postType])) {
            unset($currentTopPosts[$postType]);
            if (saveTopPosts($currentTopPosts, $top_posts_file_path)) {
                $message = 'Item successfully removed from top posts.';
                $messageType = 'success';
            } else {
                $message = 'Failed to remove item from top posts. Check file permissions for ' . $top_posts_file_path;
                $messageType = 'error';
            }
        } else {
            $message = 'No item currently set as top post for this category.';
            $messageType = 'error';
        }
    }
}

// Load items for display (re-load after potential modifications)
if (file_exists($file_path)) {
    $jsonContent = file_get_contents($file_path);
    $items = json_decode($jsonContent, true);
    if (!is_array($items)) {
        $items = []; // Ensure it's an array
    }
    // Sort items by timestamp in descending order (most recent first)
    usort($items, function($a, $b) {
        $timestampA = isset($a['timestamp']) ? strtotime($a['timestamp']) : 0;
        $timestampB = isset($b['timestamp']) ? strtotime($b['timestamp']) : 0;
        return $timestampB - $timestampA;
    });

    // Move the designated top post to the very top of the list for display
    if (isset($currentTopPosts[$postType]) && !empty($items)) {
        $topPostId = $currentTopPosts[$postType];
        $topItem = null;
        $filteredItems = [];

        foreach ($items as $item) {
            if (($item['id'] ?? null) === $topPostId) {
                $topItem = $item;
            } else {
                $filteredItems[] = $item;
            }
        }
        if ($topItem) {
            array_unshift($filteredItems, $topItem); // Place top item at the beginning
            $items = $filteredItems;
        }
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* General body styling for font and background */
        body { font-family: 'Inter', sans-serif; background-color: #f0f4f8; }
        /* Container for content, centered and with padding */
        .container { max-width: 960px; margin: 0 auto; padding: 20px; }
        /* Card-like styling for main content blocks */
        .card { background-color: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 24px; }
        /* Styling for individual item cards in admin view (distinct color) */
        .item-card { background-color: #fefcbf; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); padding: 16px; margin-bottom: 16px; border: 1px solid #fbd38d; }
        /* Highlight for the top post */
        .item-card.top-post { border-color: #38a169; background-color: #ecfdf5; box-shadow: 0 4px 8px rgba(56, 161, 105, 0.2); }
        /* Navigation link styling */
        .nav-link { color: #ffffff; text-decoration: none; padding: 8px 12px; border-radius: 8px; transition: background-color 0.3s ease; }
        .nav-link:hover { background-color: #2563eb; }
    </style>
</head>
<body class="flex flex-col min-h-screen">
    <header class="bg-blue-600 text-white p-4 shadow-md">
        <div class="container flex justify-between items-center">
            <h1 class="text-3xl font-bold">Lost & Found Hub <span class="text-blue-200 text-xl">- Admin</span></h1>
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="<?php echo $basePath; ?>/index.php" class="nav-link">Home</a></li>
                    <li><a href="<?php echo $basePath; ?>/admin_manage_posts.php?type=lost" class="nav-link <?php echo ($postType === 'lost' ? 'bg-blue-700' : ''); ?>">Manage Lost</a></li>
                    <li><a href="<?php echo $basePath; ?>/admin_manage_posts.php?type=found" class="nav-link <?php echo ($postType === 'found' ? 'bg-blue-700' : ''); ?>">Manage Found</a></li>
                    </ul>
            </nav>
        </div>
    </header>

    <main class="flex-grow container mt-8">
        <div class="card">
            <h2 class="text-3xl font-bold <?php echo $headingColor; ?> mb-6"><?php echo $pageTitle; ?></h2>

            <?php if ($message): // Display success or error messages ?>
                <div class="p-4 mb-4 rounded-md <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if (empty($items)): // Display message if no items are found to manage ?>
                <p class="text-gray-600">No <?php echo htmlspecialchars($postType); ?> items to manage yet.</p>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    $topPostId = $currentTopPosts[$postType] ?? null;
                    foreach ($items as $item):
                        $isTopPost = (($item['id'] ?? null) === $topPostId);
                        $cardClass = $isTopPost ? 'item-card top-post' : 'item-card';
                    ?>
                        <div class="<?php echo $cardClass; ?> flex flex-col justify-between">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">
                                    <?php echo htmlspecialchars($item['title'] ?? 'N/A'); ?>
                                    <?php if ($isTopPost): ?>
                                        <span class="ml-2 px-2 py-0.5 bg-green-500 text-white text-xs font-bold rounded-full">TOP POST</span>
                                    <?php endif; ?>
                                </h3>
                                <p class="text-gray-700 text-sm mb-1"><strong>Description:</strong> <?php echo htmlspecialchars($item['description'] ?? 'N/A'); ?></p>
                                <p class="text-gray-600 text-sm mb-1"><strong>Location:</strong> <?php echo htmlspecialchars($item['location'] ?? 'N/A'); ?></p>
                                <p class="text-gray-600 text-sm mb-2"><strong>Date:</strong> <?php echo htmlspecialchars($item['date'] ?? 'N/A'); ?></p>

                                <h4 class="text-lg font-semibold text-gray-700 mt-4 border-t pt-4">Contact:</h4>
                                <p class="text-gray-700 text-sm mb-1"><strong>Roll Number:</strong> <?php echo htmlspecialchars($item['roll_number'] ?? 'N/A'); ?></p>
                                <p class="text-gray-700 text-sm mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($item['poster_name'] ?? 'N/A'); ?></p>
                                <p class="text-blue-700 text-sm mb-1"><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($item['email'] ?? ''); ?>" class="hover:underline"><?php echo htmlspecialchars($item['email'] ?? 'N/A'); ?></a></p>
                                <?php if (!empty($item['phone_number'])): ?>
                                    <p class="text-blue-700 text-sm mb-1"><strong>Phone:</strong> <a href="tel:<?php echo htmlspecialchars($item['phone_number']); ?>" class="hover:underline"><?php echo htmlspecialchars($item['phone_number']); ?></a></p>
                                <?php endif; ?>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <?php if (!empty($item['media_path'])): // Display uploaded media if available ?>
                                    <?php
                                        // Construct the full URL for the media file
                                        $mediaUrl = $uploadUrlPath . htmlspecialchars($item['media_path']);
                                        // Determine if it's an image or video for proper rendering
                                        $mediaType = getMediaType($item['media_path']); // Assuming getMediaType function exists in config.php
                                    ?>
                                    <div class="mb-4">
                                        <?php if ($mediaType === 'image'): ?>
                                            <img src="<?php echo $mediaUrl; ?>" alt="Item Image" class="w-full h-48 object-cover rounded-lg shadow-sm">
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

                                <div class="flex flex-wrap gap-2 mt-4">
                                    <?php if ($isTopPost): ?>
                                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?type=' . $postType); ?>" method="POST" class="inline-block">
                                            <input type="hidden" name="remove_top_id" value="<?php echo htmlspecialchars($item['id'] ?? ''); ?>">
                                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300">
                                                Remove from Top
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?type=' . $postType); ?>" method="POST" class="inline-block">
                                            <input type="hidden" name="make_top_id" value="<?php echo htmlspecialchars($item['id'] ?? ''); ?>">
                                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300">
                                                Make Top Post
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?type=' . $postType); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this item? This action cannot be undone.');" class="inline-block">
                                        <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($item['id'] ?? ''); ?>">
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-sm font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-gray-800 text-white p-4 mt-8 text-center shadow-inner">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Lost & Found Hub. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>