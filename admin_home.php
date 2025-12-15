<?php
// Include the configuration file for shared functions and constants
require_once 'config.php';

// Get the base path for navigation
$basePath = getBasePath();

// No session start or login check is required here as per the user's request.
// This page is publicly accessible.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            <h1 class="text-3xl font-bold">Lost & Found Hub (Admin)</h1>
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

    <!-- Main content area for the admin dashboard -->
    <main class="flex-grow container mt-8">
        <div class="card">
            <h2 class="text-3xl font-bold text-purple-700 mb-6">Admin Dashboard</h2>
            <p class="text-gray-700 mb-8">
                Welcome to the Admin Dashboard! From here, you can manage all the lost and found posts.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Links to manage lost and found posts -->
                <a href="<?php echo $basePath; ?>/admin_manage_posts.php?type=lost" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-4 px-6 rounded-lg shadow-md text-center transition duration-300 transform hover:scale-105">
                    Manage Lost Posts
                </a>
                <a href="<?php echo $basePath; ?>/admin_manage_posts.php?type=found" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-4 px-6 rounded-lg shadow-md text-center transition duration-300 transform hover:scale-105">
                    Manage Found Posts
                </a>
                <a href="<?php echo $basePath; ?>/faculty_post.php?type=found" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-4 px-6 rounded-lg shadow-md text-center transition duration-300 transform hover:scale-105">
                    admin post
                </a>
                <a href="<?php echo $basePath; ?>/faculty_show.php?type=found" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-4 px-6 rounded-lg shadow-md text-center transition duration-300 transform hover:scale-105">
                    admin show
                </a>
            </div>
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
