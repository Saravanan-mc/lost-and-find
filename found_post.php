<?php
// Include the configuration file for shared functions and constants
require_once 'config.php';

// Get the base path for navigation
$basePath = getBasePath();

$message = ''; // To store success or error messages
$messageType = ''; // To determine the styling of the message (success/error)

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Define file paths for storing data
    $file_path = getDataDirPath() . 'found_items.json';

    // Sanitize and retrieve form data
    $roll_number = htmlspecialchars($_POST['roll_number'] ?? '');
    $poster_name = htmlspecialchars($_POST['poster_name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $phone_number = htmlspecialchars($_POST['phone_number'] ?? ''); // Optional, no 'required'
    $title = htmlspecialchars($_POST['title'] ?? '');
    $description = htmlspecialchars($_POST['description'] ?? '');
    $location = htmlspecialchars($_POST['location'] ?? '');
    $date = htmlspecialchars($_POST['date'] ?? date('Y-m-d')); // Default to today's date

    // Basic validation for required fields
    if (empty($roll_number) || empty($poster_name) || empty($email) || empty($title) || empty($description) || empty($location)) {
        $message = 'Roll Number, Name, Email, Title, Description, and Location Found are required.';
        $messageType = 'error';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email address.';
        $messageType = 'error';
    } else {
        $media_filename = null; // Initialize media filename

        // Handle file upload if a file was provided
        if (isset($_FILES['media_file']) && $_FILES['media_file']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload_result = handleFileUpload($_FILES['media_file']);
            if (is_string($upload_result) && (strpos($upload_result, 'Failed') === 0 || strpos($upload_result, 'Error') === 0 || strpos($upload_result, 'exceeds') === 0 || strpos($upload_result, 'Invalid') === 0)) {
                // If handleFileUpload returns an error message string
                $message = $upload_result;
                $messageType = 'error';
            } else {
                // If handleFileUpload returns the filename (success)
                $media_filename = $upload_result;
            }
        }

        // Only proceed to save if there was no file upload error (or no file was uploaded)
        if ($messageType !== 'error') {
            // Create a new item array
            $newItem = [
                'id' => uniqid(), // Generate a unique ID for the item
                'roll_number' => $roll_number,
                'poster_name' => $poster_name,
                'email' => $email,
                'phone_number' => $phone_number,
                'title' => $title,
                'description' => $description,
                'location' => $location,
                'date' => $date,
                'media_path' => $media_filename, // Store the uploaded file's relative path/filename
                'timestamp' => date('Y-m-d H:i:s') // Record creation timestamp
            ];

            // Read existing data from the JSON file
            $existingData = [];
            if (file_exists($file_path)) {
                $jsonContent = file_get_contents($file_path);
                $existingData = json_decode($jsonContent, true);
                if (!is_array($existingData)) {
                    $existingData = []; // Reset if content is not a valid array
                }
            }

            // Add the new item to the existing data
            $existingData[] = $newItem;

            // Write the updated data back to the JSON file
            if (file_put_contents($file_path, json_encode($existingData, JSON_PRETTY_PRINT))) {
                $message = 'Found item posted successfully!';
                $messageType = 'success';
                // Clear form fields after successful submission (optional)
                $_POST = array(); // This is a simple way to clear form fields
            } else {
                $message = 'Error saving found item. Please check file permissions for ' . $file_path;
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
    <title>Post Found Item</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* General body styling for font and background */
        body { font-family: 'Inter', sans-serif; background-color: #f0f4f8; }
        /* Container for content, centered and with padding */
        .container { max-width: 960px; margin: 0 auto; padding: 20px; }
        /* Card-like styling for main content blocks */
        .card { background-color: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 24px; }
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

    <!-- Main content area for posting a found item -->
    <main class="flex-grow container mt-8">
        <div class="card">
            <h2 class="text-3xl font-bold text-green-700 mb-6">Post a Found Item</h2>

            <?php if ($message): // Display success or error messages ?>
                <div class="p-4 mb-4 rounded-md <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <!-- Form for submitting found item details, with file upload support -->
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                <!-- User Information -->
                <h3 class="text-xl font-semibold text-gray-700 mt-6 mb-4 border-b pb-2">Your Contact Information:</h3>
                <div>
                    <label for="roll_number" class="block text-gray-700 text-sm font-bold mb-2">Your Roll Number:</label>
                    <input type="text" id="roll_number" name="roll_number" required
                           class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                           value="<?php echo htmlspecialchars($_POST['roll_number'] ?? ''); ?>">
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

                <!-- Item Information -->
                <h3 class="text-xl font-semibold text-gray-700 mt-6 mb-4 border-b pb-2">Found Item Details:</h3>
                <div>
                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Item Title:</label>
                    <input type="text" id="title" name="title" required
                           class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                           value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                </div>
                <div>
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description of Found Item:</label>
                    <textarea id="description" name="description" rows="4" required
                              class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                </div>
                <div>
                    <label for="location" class="block text-gray-700 text-sm font-bold mb-2">Location Found:</label>
                    <input type="text" id="location" name="location" required
                           class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                           value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>">
                </div>
                <div>
                    <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date Found:</label>
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

                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 transform hover:scale-105">
                    Submit Found Item
                </button>
            </form>
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
