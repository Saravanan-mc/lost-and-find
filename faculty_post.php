<?php
// faculty_post.php
// Include the configuration file for shared functions and constants
require_once 'config.php';
include 'admin.php';
// Get the base path for navigation
$basePath = getBasePath();

$message = ''; // To store success or error messages
$messageType = ''; // To determine the styling of the message (success/error)

// Get current page for active navigation link (used for admin navigation)
$currentPage = basename($_SERVER['PHP_SELF']);

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Define file paths for storing data
    $file_path = getDataDirPath() . 'faculty_items.json';

    // Sanitize and retrieve form data
    $faculty_id = htmlspecialchars($_POST['faculty_id'] ?? '');
    $poster_name = htmlspecialchars($_POST['poster_name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $phone_number = htmlspecialchars($_POST['phone_number'] ?? '');
    $item_type = htmlspecialchars($_POST['item_type'] ?? ''); // 'lost' or 'found'
    $title = htmlspecialchars($_POST['title'] ?? '');
    $description = htmlspecialchars($_POST['description'] ?? '');
    $location = htmlspecialchars($_POST['location'] ?? '');
    $date = htmlspecialchars($_POST['date'] ?? date('Y-m-d'));

    // Basic validation for required fields
    if (empty($faculty_id) || empty($poster_name) || empty($email) || empty($item_type) || empty($title) || empty($description) || empty($location)) {
        $message = 'Faculty ID, Name, Email, Item Type, Title, Description, and Location are required.';
        $messageType = 'error';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email address.';
        $messageType = 'error';
    } else if (!in_array($item_type, ['lost', 'found'])) {
        $message = 'Invalid item type selected. Please choose either Lost or Found.';
        $messageType = 'error';
    } else {
        $media_filename = null;

        // Handle file upload if a file was provided
        if (isset($_FILES['media_file']) && $_FILES['media_file']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload_result = handleFileUpload($_FILES['media_file']); // Assume handleFileUpload is in config.php
            if (is_string($upload_result) && (strpos($upload_result, 'Failed') === 0 || strpos($upload_result, 'Error') === 0 || strpos($upload_result, 'exceeds') === 0 || strpos($upload_result, 'Invalid') === 0)) {
                $message = $upload_result;
                $messageType = 'error';
            } else {
                $media_filename = $upload_result;
            }
        }

        // Only proceed to save if there was no file upload error (or no file was uploaded)
        if ($messageType !== 'error') {
            // Create a new item array
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

            // Read existing data from the JSON file
            $existingData = [];
            if (file_exists($file_path)) {
                $jsonContent = file_get_contents($file_path);
                $existingData = json_decode($jsonContent, true);
                if (!is_array($existingData)) {
                    $existingData = []; // Ensure it's an array even if JSON is malformed
                }
            }

            // Add the new item to the existing data
            $existingData[] = $newItem;

            // Write the updated data back to the JSON file
            if (file_put_contents($file_path, json_encode($existingData, JSON_PRETTY_PRINT))) {
                $message = 'Faculty ' . ucfirst($item_type) . ' item posted successfully!';
                $messageType = 'success';
                $_POST = array(); // Clear form fields on successful submission
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
    <title>Post Faculty Item - Lost & Found Hub (Admin)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #0066ff;
            --primary-dark: #0052cc;
            --primary-darker: #003d99;
            --primary-light: #3385ff;
            --primary-lighter: #66a3ff;
            --primary-pale: #e6f2ff;

            --secondary: #1e40af;
            --accent: #0ea5e9;
            --accent-light: #38bdf8;
            --ocean: #0284c7;
            --azure: #0369a1;

            --cyan: #06b6d4;
            --teal: #0d9488;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;

            --text-primary: #0f172a;
            --text-secondary: #334155;
            --text-light: #64748b;
            --text-muted: #94a3b8;

            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-blue: #f0f9ff;
            --bg-ocean: #ecfeff;
            --bg-gradient: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);

            --border: #e2e8f0;
            --border-blue: #bfdbfe;
            --border-light: #f1f5f9;

            --shadow-sm: 0 1px 2px 0 rgba(0, 102, 255, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 102, 255, 0.1), 0 2px 4px -1px rgba(0, 102, 255, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 102, 255, 0.1), 0 4px 6px -2px rgba(0, 102, 255, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 102, 255, 0.1), 0 10px 10px -5px rgba(0, 102, 255, 0.04);
            --shadow-2xl: 0 25px 50px -12px rgba(0, 102, 255, 0.25);
            --shadow-blue: 0 10px 25px -5px rgba(0, 102, 255, 0.2);

            --gradient-primary: linear-gradient(135deg, #0066ff 0%, #0052cc 100%);
            --gradient-ocean: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            --gradient-sky: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            --gradient-hero: linear-gradient(135deg, #0066ff 0%, #0284c7 50%, #0ea5e9 100%);
            --gradient-card: linear-gradient(145deg, #ffffff 0%, #f0f9ff 100%);
            --gradient-mesh: radial-gradient(at 40% 20%, #0066ff 0px, transparent 50%),
                             radial-gradient(at 80% 0%, #0ea5e9 0px, transparent 50%),
                             radial-gradient(at 0% 50%, #0284c7 0px, transparent 50%),
                             radial-gradient(at 80% 50%, #38bdf8 0px, transparent 50%),
                             radial-gradient(at 0% 100%, #0369a1 0px, transparent 50%);

            --shine-gradient: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
            --wave-gradient: linear-gradient(45deg, rgba(0, 102, 255, 0.1), rgba(14, 165, 233, 0.1), rgba(0, 102, 255, 0.1));
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            background: var(--gradient-hero);
            min-height: 100vh;
            overflow-x: hidden;
            scroll-behavior: smooth;
            position: relative;
            display: flex;
            flex-direction: column;
            padding-top: 85px; /* Space for fixed navbar */
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient-mesh);
            opacity: 0.03;
            z-index: -1;
            pointer-events: none;
        }

        .canvas-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0; /* Particles are above mesh, below content */
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(25px) saturate(180%);
            border-bottom: 1px solid var(--border-blue);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-sm);
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.99);
            box-shadow: var(--shadow-lg);
            border-bottom-color: var(--primary-pale);
        }

        .nav-container {
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 85px;
            transition: height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .navbar.scrolled .nav-container {
            height: 75px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 1.65rem;
            font-weight: 900;
            color: var(--primary);
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .logo:hover {
            transform: scale(1.03);
        }

        .logo-icon {
            width: 45px;
            height: 45px;
            background: var(--gradient-primary);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            box-shadow: var(--shadow-blue);
            position: relative;
            overflow: hidden;
        }

        .logo-icon::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: var(--shine-gradient);
            transform: rotate(45deg);
            animation: logoShine 3s ease-in-out infinite;
        }

        @keyframes logoShine {
            0%, 100% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            50% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2.0rem;
            align-items: center;
        }

        .nav-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 600;
            padding: 0.75rem 0;
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 3px;
            background: var(--gradient-primary);
            border-radius: 2px;
            transform: translateX(-50%);
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary);
            transform: translateY(-1px);
        }

        .nav-link:hover::before,
        .nav-link.active::before {
            width: 100%;
        }

        .nav-link i {
            font-size: 1rem;
            transition: transform 0.3s ease;
        }

        .nav-link:hover i {
            transform: scale(1.1);
        }

        .mobile-menu-btn {
            display: none;
            background: var(--gradient-primary);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 12px;
            color: white;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
            z-index: 1001;
        }

        .mobile-menu-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .mobile-menu-btn i {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 1.4rem;
        }

        .mobile-menu-btn .fa-times {
            opacity: 0;
            transform: translate(-50%, -50%) rotate(180deg);
        }

        .mobile-menu-btn.active .fa-bars {
            opacity: 0;
            transform: translate(-50%, -50%) rotate(-180deg);
        }

        .mobile-menu-btn.active .fa-times {
            opacity: 1;
            transform: translate(-50%, -50%) rotate(0deg);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.99);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-blue);
            box-shadow: var(--shadow-lg);
            border-radius: 1.5rem;
            animation: fadeInScale 0.8s ease-out forwards;
            position: relative;
            z-index: 1;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .form-group {
            opacity: 0;
            transform: translateY(30px);
            animation: slideInUp 0.6s ease-out forwards;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        .form-group:nth-child(4) { animation-delay: 0.4s; }
        .form-group:nth-child(5) { animation-delay: 0.5s; }
        .form-group:nth-child(6) { animation-delay: 0.6s; }
        .form-group:nth-child(7) { animation-delay: 0.7s; }
        .form-group:nth-child(8) { animation-delay: 0.8s; }
        .form-group:nth-child(9) { animation-delay: 0.9s; }
        .form-group:last-child { animation-delay: 1.0s; }

        @keyframes slideInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .enhanced-input {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            background-color: var(--bg-blue);
            border-color: var(--border);
            color: var(--text-primary);
        }

        .enhanced-input:focus {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary);
            background-color: var(--bg-primary);
        }

        .floating-label {
            position: relative;
        }

        .floating-label input:focus + label,
        .floating-label input:not(:placeholder-shown) + label,
        .floating-label textarea:focus + label,
        .floating-label textarea:not(:placeholder-shown) + label,
        .floating-label select:focus + label,
        .floating-label select:not(:placeholder-shown) + label { /* Added select */
            transform: translateY(-28px) scale(0.8);
            color: var(--primary-dark);
            font-weight: 600;
            background-color: var(--bg-primary);
            padding: 0 0.4rem;
            border-radius: 0.3rem;
            z-index: 2; /* Ensure label is above input */
        }

        .floating-label label {
            position: absolute;
            left: 1rem;
            top: 0.85rem;
            transition: all 0.3s ease;
            pointer-events: none;
            color: var(--text-light);
            z-index: 1;
            transform-origin: left top;
        }

        .btn-primary {
            background: var(--gradient-primary);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-blue);
            color: white;
            font-weight: 700;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--shine-gradient);
            transform: skewX(-30deg);
            transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-2xl);
        }

        .file-upload {
            position: relative;
            border: 2px dashed var(--border);
            border-radius: 12px;
            padding: 2.5rem 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            background: var(--bg-blue);
            cursor: pointer;
            color: var(--text-secondary);
        }

        .file-upload:hover {
            border-color: var(--primary-light);
            background: var(--primary-pale);
            transform: translateY(-3px);
            box-shadow: var(--shadow-sm);
        }

        .file-upload.dragover {
            border-color: var(--success);
            background: rgba(16, 185, 129, 0.1);
            transform: scale(1.01);
        }

        .alert {
            animation: alertSlideIn 0.5s ease-out;
            border-radius: 1rem;
            font-weight: 500;
        }

        .alert.bg-green-100 {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border-color: rgba(16, 185, 129, 0.3);
        }

        .alert.bg-red-100 {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border-color: rgba(239, 68, 68, 0.3);
        }

        @keyframes alertSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .section-divider {
            position: relative;
            margin: 2.5rem 0;
            text-align: center;
        }

        .section-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border), transparent);
            z-index: 1;
        }

        .section-divider span {
            background: var(--bg-primary);
            padding: 0 1.5rem;
            color: var(--text-light);
            font-weight: 600;
            position: relative;
            z-index: 2;
            border-radius: 9999px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

       

        @media (max-width: 992px) {
            .nav-container {
                flex-wrap: wrap;
                height: auto;
                padding: 1rem 1.5rem;
            }
            .navbar.scrolled .nav-container {
                height: auto;
            }
            .nav-menu {
                display: none;
                flex-direction: column;
                width: 100%;
                text-align: center;
                margin-top: 1rem;
                gap: 0;
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(25px);
                border-top: 1px solid var(--border-blue);
                padding: 1rem 0;
                box-shadow: var(--shadow-md);
                position: absolute;
                top: 85px;
                left: 0;
                transform: translateY(-100%);
                opacity: 0;
                transition: transform 0.4s ease-out, opacity 0.4s ease-out;
            }

            .nav-menu.active {
                display: flex;
                transform: translateY(0);
                opacity: 1;
            }

            .nav-link {
                padding: 0.8rem 0;
                justify-content: center;
            }

            .mobile-menu-btn {
                display: flex;
            }
            body {
                padding-top: 75px;
            }
        }

        canvas {
            display: block;
        }

       
    </style>
</head>
<body class="flex flex-col min-h-screen">
    <canvas id="particleCanvas" class="canvas-container"></canvas>

    <header class="navbar" id="navbar">
        <div class="nav-container">
            <a href="<?php echo $basePath; ?>/index.php" class="logo">
                <span class="logo-icon"><i class="fas fa-search-location"></i></span>
                Lost & Found Hub
            </a>
            <nav>
                <ul class="nav-menu" id="navMenu">
                    <li><a href="<?php echo $basePath; ?>/admin_home.php" class="nav-link <?php echo ($currentPage == 'admin_home.php') ? 'active' : ''; ?>"><i class="fas fa-user-shield"></i> Admin Home</a></li>
                    <li><a href="<?php echo $basePath; ?>/admin_manage_posts.php?type=lost" class="nav-link <?php echo ($currentPage == 'admin_manage_posts.php' && ($_GET['type'] ?? '') == 'lost') ? 'active' : ''; ?>"><i class="fas fa-box-open"></i> Manage Lost</a></li>
                    <li><a href="<?php echo $basePath; ?>/admin_manage_posts.php?type=found" class="nav-link <?php echo ($currentPage == 'admin_manage_posts.php' && ($_GET['type'] ?? '') == 'found') ? 'active' : ''; ?>"><i class="fas fa-box"></i> Manage Found</a></li>
                    <li><a href="<?php echo $basePath; ?>/faculty_post.php" class="nav-link <?php echo ($currentPage == 'faculty_post.php') ? 'active' : ''; ?>"><i class="fas fa-chalkboard-teacher"></i> Post Faculty Item</a></li>
                    <li><a href="<?php echo $basePath; ?>/faculty_show.php" class="nav-link <?php echo ($currentPage == 'faculty_show.php') ? 'active' : ''; ?>"><i class="fas fa-eye"></i> View Faculty Items</a></li>
                    </ul>
            </nav>
            <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Toggle mobile menu">
                <i class="fas fa-bars"></i>
                <i class="fas fa-times"></i>
            </button>
        </div>
    </header>

    <main class="flex-grow container max-w-4xl mx-auto my-8 px-6 relative z-10" id="ad">
        <div class="glass-card p-8 shadow-2xl">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-4 shadow-md">
                    <i class="fa-solid fa-chalkboard-teacher text-blue-600 text-4xl"></i>
                </div>
                <h2 class="text-4xl font-extrabold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent mb-3">Post Faculty Lost/Found Item</h2>
                <p class="text-gray-600 text-lg">Easily submit details for items lost or found by faculty members.</p>
            </div>

            <?php if ($message): ?>
                <div class="alert p-4 mb-6 <?php echo $messageType === 'success' ? 'bg-green-100 text-green-800 border-green-300' : 'bg-red-100 text-red-800 border-red-300'; ?> border">
                    <div class="flex items-center">
                        <i class="<?php echo $messageType === 'success' ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-exclamation'; ?> text-xl mr-3"></i>
                        <p class="font-medium"><?php echo $message; ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data" class="space-y-8" id="facultyItemForm">

                <div class="section-divider">
                    <span><i class="fa-solid fa-id-badge mr-2 text-blue-600"></i>Your Details</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group floating-label">
                        <input type="text" id="faculty_id" name="faculty_id" required placeholder=" "
                               class="enhanced-input w-full py-3 px-4 border rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500"
                               value="<?php echo htmlspecialchars($_POST['faculty_id'] ?? ''); ?>">
                        <label for="faculty_id"><i class="fa-solid fa-id-card mr-2"></i>Faculty ID</label>
                    </div>

                    <div class="form-group floating-label">
                        <input type="text" id="poster_name" name="poster_name" required placeholder=" "
                               class="enhanced-input w-full py-3 px-4 border rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500"
                               value="<?php echo htmlspecialchars($_POST['poster_name'] ?? ''); ?>">
                        <label for="poster_name"><i class="fa-solid fa-user mr-2"></i>Full Name</label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group floating-label">
                        <input type="email" id="email" name="email" required placeholder=" "
                               class="enhanced-input w-full py-3 px-4 border rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500"
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                        <label for="email"><i class="fa-solid fa-envelope mr-2"></i>Email Address</label>
                    </div>

                    <div class="form-group floating-label">
                        <input type="text" id="phone_number" name="phone_number" placeholder=" "
                               class="enhanced-input w-full py-3 px-4 border rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500"
                               value="<?php echo htmlspecialchars($_POST['phone_number'] ?? ''); ?>">
                        <label for="phone_number"><i class="fa-solid fa-phone mr-2"></i>Phone Number (Optional)</label>
                    </div>
                </div>

                <div class="section-divider">
                    <span><i class="fa-solid fa-cube mr-2 text-blue-600"></i>Item Details</span>
                </div>

                <div class="form-group floating-label">
                    <select id="item_type" name="item_type" required placeholder=" "
                            class="enhanced-input w-full py-3 px-4 border rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500">
                        <option value="" disabled selected>Select Item Type</option>
                        <option value="lost" <?php echo (($_POST['item_type'] ?? '') === 'lost' ? 'selected' : ''); ?>>Lost Item</option>
                        <option value="found" <?php echo (($_POST['item_type'] ?? '') === 'found' ? 'selected' : ''); ?>>Found Item</option>
                    </select>
                    <label for="item_type"><i class="fa-solid fa-tag mr-2"></i>Type of Item</label>
                </div>

                <div class="form-group floating-label">
                    <input type="text" id="title" name="title" required placeholder=" "
                           class="enhanced-input w-full py-3 px-4 border rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500"
                           value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                    <label for="title"><i class="fa-solid fa-heading mr-2"></i>Item Title (e.g., "Blue Laptop Bag")</label>
                </div>

                <div class="form-group floating-label">
                    <textarea id="description" name="description" rows="4" required placeholder=" "
                              class="enhanced-input w-full py-3 px-4 border rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                    <label for="description"><i class="fa-solid fa-info-circle mr-2"></i>Detailed Description (color, brand, unique marks)</label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group floating-label">
                        <input type="text" id="location" name="location" required placeholder=" "
                               class="enhanced-input w-full py-3 px-4 border rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500"
                               value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>">
                        <label for="location"><i class="fa-solid fa-map-marker-alt mr-2"></i>Last Seen / Found Location</label>
                    </div>

                    <div class="form-group floating-label">
                        <input type="date" id="date" name="date" required placeholder=" "
                               class="enhanced-input w-full py-3 px-4 border rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500"
                               value="<?php echo htmlspecialchars($_POST['date'] ?? date('Y-m-d')); ?>">
                        <label for="date"><i class="fa-solid fa-calendar-alt mr-2"></i>Date Lost / Found</label>
                    </div>
                </div>

                <div class="form-group file-upload" id="fileUploadArea">
                    <input type="file" id="media_file" name="media_file" class="hidden" accept="image/*,video/*">
                    <label for="media_file" class="block cursor-pointer">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-cloud-upload-alt text-5xl text-blue-400 mb-3"></i>
                            <p class="font-semibold text-lg">Drag & Drop or <span class="text-blue-600 underline">Click to Upload</span></p>
                            <p class="text-sm text-gray-500">Max file size: 40MB. Allowed: Images (JPG, PNG, GIF, WebP), Videos (MP4, WebM, Ogg).</p>
                            <p id="fileName" class="text-sm text-blue-700 mt-2 font-medium"></p>
                        </div>
                    </label>
                </div>

                <button type="submit" class="btn-primary w-full py-3 px-4 rounded-xl text-lg flex items-center justify-center gap-3">
                    <i class="fas fa-paper-plane"></i> Submit Faculty Item
                </button>
            </form>
        </div>
    </main>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const navMenu = document.getElementById('navMenu');

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', function() {
                navMenu.classList.toggle('active');
                mobileMenuBtn.classList.toggle('active');
            });
        }

        // Handle file upload preview/name display
        const fileInput = document.getElementById('media_file');
        const fileNameDisplay = document.getElementById('fileName');
        const fileUploadArea = document.getElementById('fileUploadArea');

        if (fileInput && fileNameDisplay && fileUploadArea) {
            fileInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    fileNameDisplay.textContent = this.files[0].name;
                } else {
                    fileNameDisplay.textContent = '';
                }
            });

            // Drag and drop functionality
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                fileUploadArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                fileUploadArea.addEventListener(eventName, () => fileUploadArea.classList.add('dragover'), false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                fileUploadArea.addEventListener(eventName, () => fileUploadArea.classList.remove('dragover'), false);
            });

            fileUploadArea.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                fileInput.files = files; // Assign files to input
                if (files.length > 0) {
                    fileNameDisplay.textContent = files[0].name;
                }
            }
        }

        // Particle Canvas Animation
        const canvas = document.getElementById('particleCanvas');
        const ctx = canvas.getContext('2d');
        let particles = [];
        const numParticles = 100;
        const maxRadius = 2.5; // Max particle size
        const minRadius = 0.5; // Min particle size
        const connectionDistance = 120; // Max distance for lines
        const particleSpeed = 0.3; // Speed multiplier

        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }

        class Particle {
            constructor(x, y, radius, color, velocity) {
                this.x = x;
                this.y = y;
                this.radius = radius;
                this.color = color;
                this.velocity = velocity;
                this.alpha = 0; // Start with 0 alpha for fade-in
                this.fadeSpeed = Math.random() * 0.02 + 0.005; // Different fade speeds
            }

            draw() {
                ctx.save();
                ctx.globalAlpha = this.alpha;
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2, false);
                ctx.fillStyle = this.color;
                ctx.fill();
                ctx.restore();
            }

            update() {
                this.x += this.velocity.x * particleSpeed;
                this.y += this.velocity.y * particleSpeed;

                // Fade in
                if (this.alpha < 0.6) { // Max alpha for subtle effect
                    this.alpha += this.fadeSpeed;
                }

                // Bounce off walls
                if (this.x + this.radius > canvas.width || this.x - this.radius < 0) {
                    this.velocity.x = -this.velocity.x;
                }
                if (this.y + this.radius > canvas.height || this.y - this.radius < 0) {
                    this.velocity.y = -this.velocity.y;
                }

                // Wrap around (alternative to bounce)
                // if (this.x - this.radius > canvas.width) this.x = -this.radius;
                // if (this.x + this.radius < 0) this.x = canvas.width + this.radius;
                // if (this.y - this.radius > canvas.height) this.y = -this.radius;
                // if (this.y + this.radius < 0) this.y = canvas.height + this.radius;

                this.draw();
            }
        }

        function initParticles() {
            particles = [];
            for (let i = 0; i < numParticles; i++) {
                const radius = Math.random() * (maxRadius - minRadius) + minRadius;
                const x = Math.random() * (canvas.width - radius * 2) + radius;
                const y = Math.random() * (canvas.height - radius * 2) + radius;
                const angle = Math.random() * Math.PI * 2;
                const speed = Math.random() * 0.7 + 0.3; // Slower particles
                const velocity = {
                    x: Math.cos(angle) * speed,
                    y: Math.sin(angle) * speed
                };
                const colors = ['rgba(0,102,255,0.8)', 'rgba(14,165,233,0.8)', 'rgba(2,132,199,0.8)'];
                const color = colors[Math.floor(Math.random() * colors.length)];
                particles.push(new Particle(x, y, radius, color, velocity));
            }
        }

        function animateParticles() {
            requestAnimationFrame(animateParticles);
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            for (let i = 0; i < particles.length; i++) {
                particles[i].update();

                for (let j = i; j < particles.length; j++) {
                    const p1 = particles[i];
                    const p2 = particles[j];
                    const dist = Math.hypot(p1.x - p2.x, p1.y - p2.y);

                    if (dist < connectionDistance) {
                        ctx.save();
                        ctx.globalAlpha = ((connectionDistance - dist) / connectionDistance) * 0.2; // Softer lines
                        ctx.beginPath();
                        ctx.moveTo(p1.x, p1.y);
                        ctx.lineTo(p2.x, p2.y);
                        ctx.strokeStyle = 'rgba(0, 102, 255, 0.7)'; // Primary color for lines
                        ctx.lineWidth = 1;
                        ctx.stroke();
                        ctx.restore();
                    }
                }
            }
        }

        window.addEventListener('load', () => {
            resizeCanvas();
            initParticles();
            animateParticles();
        });

        window.addEventListener('resize', () => {
            resizeCanvas();
            initParticles(); // Re-initialize particles to fit new canvas size
        });
    </script>
</body>
</html>