<?php
// faculty_show.php
require_once 'config.php';

$basePath = getBasePath();
$uploadUrlPath = getUploadUrlPath();

$file_path = getDataDirPath() . 'faculty_items.json';
$top_posts_file_path = getDataDirPath() . 'top_posts.json';

$items = [];
$currentTopPosts = [];

// Load existing top posts data
if (file_exists($top_posts_file_path)) {
    $jsonContent = file_get_contents($top_posts_file_path);
    $currentTopPosts = json_decode($jsonContent, true);
    if (!is_array($currentTopPosts)) {
        $currentTopPosts = [];
    }
}

if (file_exists($file_path)) {
    $jsonContent = file_get_contents($file_path);
    $items = json_decode($jsonContent, true);

    if (!is_array($items)) {
        $items = [];
    }

    // Sort items by timestamp in descending order (most recent first)
    usort($items, function($a, $b) {
        $timestampA = isset($a['timestamp']) ? strtotime($a['timestamp']) : 0;
        $timestampB = isset($b['timestamp']) ? strtotime($b['timestamp']) : 0;
        return $timestampB - $timestampA;
    });

    // Move the designated top post to the very top of the list for display
    if (isset($currentTopPosts['faculty']) && !empty($items)) {
        $topPostId = $currentTopPosts['faculty'];
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
    <title>Faculty Items</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f0f4f8; }
        .container { max-width: 960px; margin: 0 auto; padding: 20px; }
        .card { background-color: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 24px; }
        .nav-link { color: #3b82f6; text-decoration: none; padding: 8px 12px; border-radius: 8px; transition: background-color 0.3s ease; }
        .nav-link:hover { background-color: #bfdbfe; }
        .item-card { background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); padding: 16px; margin-bottom: 16px; border: 1px solid #e2e8f0; }
        .item-card.top-post { border-color: #38a169; background-color: #ecfdf5; box-shadow: 0 4px 8px rgba(56, 161, 105, 0.2); }
    </style>
</head>
<body class="flex flex-col min-h-screen">
    <header class="bg-blue-600 text-white p-4 shadow-md">
        <div class="container flex justify-between items-center">
            <h1 class="text-3xl font-bold">Lost & Found Hub</h1>
            <nav>
                 <ul class="flex space-x-4">
                    <li><a href="<?php echo $basePath; ?>/admin_home.php" class="nav-link text-white hover:bg-blue-700">Admin Home</a></li>
                    <li><a href="<?php echo $basePath; ?>/admin_manage_posts.php?type=lost" class="nav-link text-white hover:bg-blue-700">Manage Lost</a></li>
                    <li><a href="<?php echo $basePath; ?>/admin_manage_posts.php?type=found" class="nav-link text-white hover:bg-blue-700">Manage Found</a></li>
                    <!-- No logout button needed as there's no login session -->
                </ul>
            </nav>
        </div>
    </header>

    <main class="flex-grow container mt-8">
        <div class="card">
            <h2 class="text-3xl font-bold text-indigo-700 mb-6">Faculty Posted Items</h2>

            <?php if (empty($items)): ?>
                <p class="text-gray-600">No faculty items have been posted yet.</p>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    $topPostId = $currentTopPosts['faculty'] ?? null;
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
                                <p class="text-gray-700 text-sm mb-1"><strong>Type:</strong> <?php echo ucfirst(htmlspecialchars($item['item_type'] ?? 'N/A')); ?></p>
                                <p class="text-gray-700 text-sm mb-1"><strong>Description:</strong> <?php echo htmlspecialchars($item['description'] ?? 'N/A'); ?></p>
                                <p class="text-gray-600 text-sm mb-1"><strong>Location:</strong> <?php echo htmlspecialchars($item['location'] ?? 'N/A'); ?></p>
                                <p class="text-gray-600 text-sm mb-2"><strong>Date:</strong> <?php echo htmlspecialchars($item['date'] ?? 'N/A'); ?></p>
                                <?php if (!empty($item['media_path'])): ?>
                                    <?php
                                        $mediaUrl = $uploadUrlPath . htmlspecialchars($item['media_path']);
                                        $mediaType = getMediaType($item['media_path']);
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
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <h4 class="text-lg font-semibold text-gray-700 mb-2">Contact Poster:</h4>
                                <p class="text-gray-700 text-sm mb-1"><strong>Faculty ID:</strong> <?php echo htmlspecialchars($item['faculty_id'] ?? 'N/A'); ?></p>
                                <p class="text-gray-700 text-sm mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($item['poster_name'] ?? 'N/A'); ?></p>
                                <p class="text-blue-700 text-sm mb-1"><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($item['email'] ?? ''); ?>" class="hover:underline"><?php echo htmlspecialchars($item['email'] ?? 'N/A'); ?></a></p>
                                <?php if (!empty($item['phone_number'])): ?>
                                    <p class="text-blue-700 text-sm mb-1"><strong>Phone:</strong> <a href="tel:<?php echo htmlspecialchars($item['phone_number']); ?>" class="hover:underline"><?php echo htmlspecialchars($item['phone_number']); ?></a></p>
                                <?php endif; ?>
                                <p class="text-gray-500 text-xs mt-2">Posted on: <?php echo htmlspecialchars($item['timestamp'] ?? 'N/A'); ?></p>
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
