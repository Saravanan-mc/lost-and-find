<?php
// Include the configuration file for shared functions and constants
require_once 'config.php';

// Get the base path for navigation
$basePath = getBasePath();

// Since login is not required as per the request, this page serves as an entry point
// or an informational page for the admin section. It immediately directs to the admin home.
// In a real application, this would contain the login form.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Section</title>
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

    <!-- Main content area for admin login/entry -->
    <main class="flex-grow container mt-8 flex items-center justify-center">
        <div class="card w-full max-w-md text-center">
            <h2 class="text-3xl font-bold text-blue-800 mb-6">Admin Section</h2>
            <p class="text-gray-700 mb-4">
                As per your request, there is no login required for the admin section in this demonstration.
                You can directly proceed to the admin dashboard.
            </p>
            <a href="<?php echo $basePath; ?>/admin_home.php" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 transform hover:scale-105">
                Go to Admin Dashboard
            </a>
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
