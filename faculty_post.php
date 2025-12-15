<?php
// faculty_post.php
require_once 'config.php';

$basePath = getBasePath();

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file_path = getDataDirPath() . 'faculty_items.json';

    // Sanitize and retrieve form data
    $faculty_id = htmlspecialchars($_POST['faculty_id'] ?? '');
    $poster_name = htmlspecialchars($_POST['poster_name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $phone_number = htmlspecialchars($_POST['phone_number'] ?? ''); // Optional
    $item_type = htmlspecialchars($_POST['item_type'] ?? ''); // 'lost' or 'found'
    $title = htmlspecialchars($_POST['title'] ?? '');
    $description = htmlspecialchars($_POST['description'] ?? '');
    $location = htmlspecialchars($_POST['location'] ?? '');
    $date = htmlspecialchars($_POST['date'] ?? date('Y-m-d'));

    // Basic validation
    if (empty($faculty_id) || empty($poster_name) || empty($email) || empty($item_type) || empty($title) || empty($description) || empty($location)) {
        $message = 'Faculty ID, Name, Email, Item Type, Title, Description, and Location are required.';
        $messageType = 'error';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email address.';
        $messageType = 'error';
    } else if (!in_array($item_type, ['lost', 'found'])) {
        $message = 'Invalid item type selected.';
        $messageType = 'error';
    } else {
        $media_filename = null;

        if (isset($_FILES['media_file']) && $_FILES['media_file']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload_result = handleFileUpload($_FILES['media_file']);
            if (is_string($upload_result) && (strpos($upload_result, 'Failed') === 0 || strpos($upload_result, 'Error') === 0 || strpos($upload_result, 'exceeds') === 0 || strpos($upload_result, 'Invalid') === 0)) {
                $message = $upload_result;
                $messageType = 'error';
            } else {
                $media_filename = $upload_result;
            }
        }

        if ($messageType !== 'error') {
            $newItem = [
                'id' => uniqid(),
                'faculty_id' => $faculty_id,
                'poster_name' => $poster_name,
                'email' => $email,
                'phone_number' => $phone_number,
                'item_type' => $item_type, // 'lost' or 'found'
                'title' => $title,
                'description' => $description,
                'location' => $location,
                'date' => $date,
                'media_path' => $media_filename,
                'timestamp' => date('Y-m-d H:i:s')
            ];

            $existingData = [];
            if (file_exists($file_path)) {
                $jsonContent = file_get_contents($file_path);
                $existingData = json_decode($jsonContent, true);
                if (!is_array($existingData)) {
                    $existingData = [];
                }
            }

            $existingData[] = $newItem;

            if (file_put_contents($file_path, json_encode($existingData, JSON_PRETTY_PRINT))) {
                $message = 'Faculty item posted successfully!';
                $messageType = 'success';
                $_POST = array(); // Clear form fields
            } else {
                $message = 'Error saving faculty item. Please check file permissions for ' . $file_path;
                $messageType = 'error';
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
    <title>Post Faculty Item</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f0f4f8; }
        .container { max-width: 960px; margin: 0 auto; padding: 20px; }
        .card { background-color: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 24px; }
        .nav-link { color: #3b82f6; text-decoration: none; padding: 8px 12px; border-radius: 8px; transition: background-color 0.3s ease; }
        .nav-link:hover { background-color: #bfdbfe; }
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
            <h2 class="text-3xl font-bold text-indigo-700 mb-6">Post a Faculty Lost/Found Item</h2>

            <?php if ($message): ?>
                <div class="p-4 mb-4 rounded-md <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                <h3 class="text-xl font-semibold text-gray-700 mt-6 mb-4 border-b pb-2">Your Contact Information:</h3>
                <div>
                    <label for="faculty_id" class="block text-gray-700 text-sm font-bold mb-2">Your Faculty ID:</label>
                    <input type="text" id="faculty_id" name="faculty_id" required
                           class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                           value="<?php echo htmlspecialchars($_POST['faculty_id'] ?? ''); ?>">
                </div>
                <div>
                    <label for="poster_name" class="block text-gray-700 text-sm font-bold mb-2">Your Name:</label>
                    <input type="text" id="poster_name" name="poster_name" required
                           class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                           value="<?php echo htmlspecialchars($_POST['poster_name'] ?? ''); ?>">
                </div>
                <div>
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Your Email ID:</label>
                    <input type="email" id="email" name="email" required
                           class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>
                <div>
                    <label for="phone_number" class="block text-gray-700 text-sm font-bold mb-2">Your Phone Number (Optional):</label>
                    <input type="text" id="phone_number" name="phone_number"
                           class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                           value="<?php echo htmlspecialchars($_POST['phone_number'] ?? ''); ?>">
                </div>

                <h3 class="text-xl font-semibold text-gray-700 mt-6 mb-4 border-b pb-2">Item Details:</h3>
                <div>
                    <label for="item_type" class="block text-gray-700 text-sm font-bold mb-2">Item Type:</label>
                    <select id="item_type" name="item_type" required
                            class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                        <option value="">Select Item Type</option>
                        <option value="lost" <?php echo (($_POST['item_type'] ?? '') === 'lost' ? 'selected' : ''); ?>>Lost</option>
                        <option value="found" <?php echo (($_POST['item_type'] ?? '') === 'found' ? 'selected' : ''); ?>>Found</option>
                    </select>
                </div>
                <div>
                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Item Title:</label>
                    <input type="text" id="title" name="title" required
                           class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                           value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                </div>
                <div>
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                    <textarea id="description" name="description" rows="4" required
                              class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                </div>
                <div>
                    <label for="location" class="block text-gray-700 text-sm font-bold mb-2">Location (Last Seen / Found):</label>
                    <input type="text" id="location" name="location" required
                           class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                           value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>">
                </div>
                <div>
                    <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date (Lost / Found):</label>
                    <input type="date" id="date" name="date" required
                           class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                           value="<?php echo htmlspecialchars($_POST['date'] ?? date('Y-m-d')); ?>">
                </div>
                <div>
                    <label for="media_file" class="block text-gray-700 text-sm font-bold mb-2">Upload Photo or Video (Max 40MB):</label>
                    <input type="file" id="media_file" name="media_file"
                           class="block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-lg file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-blue-50 file:text-blue-700
                                  hover:file:bg-blue-100">
                    <p class="mt-1 text-xs text-gray-500">Allowed formats: JPG, PNG, GIF, WebP, MP4, WebM, Ogg.</p>
                </div>

                <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 transform hover:scale-105">
                    Submit Faculty Item
                </button>
            </form>
        </div>
    </main>

    <footer class="bg-gray-800 text-white p-4 mt-8 text-center shadow-inner">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Lost & Found Hub. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
