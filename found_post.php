<?php
session_start();
include 'stdnav.php';
?>

<?php
// Include the configuration file for shared functions and constants
require_once 'config.php';

// Get the base path for navigation
$basePath = getBasePath();

$message = ''; // To store success or error messages
$messageType = ''; // To determine the styling of the message (success/error)

// Get current page for active navigation link
$currentPage = basename($_SERVER['PHP_SELF']);

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Define file paths for storing data
    $file_path = getDataDirPath() . 'found_items.json'; // Changed to found_items.json

    // Sanitize and retrieve form data
    $roll_number = htmlspecialchars($_POST['roll_number'] ?? '');
    $poster_name = htmlspecialchars($_POST['poster_name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $phone_number = htmlspecialchars($_POST['phone_number'] ?? '');
    $title = htmlspecialchars($_POST['title'] ?? '');
    $description = htmlspecialchars($_POST['description'] ?? '');
    $location = htmlspecialchars($_POST['location'] ?? '');
    $date = htmlspecialchars($_POST['date'] ?? date('Y-m-d'));

    // Basic validation for required fields
    if (empty($roll_number) || empty($poster_name) || empty($email) || empty($title) || empty($description) || empty($location)) {
        $message = 'Roll Number, Name, Email, Title, Description, and Location Found are required.';
        $messageType = 'error';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email address.';
        $messageType = 'error';
    } else {
        $media_filename = null;

        // Handle file upload if a file was provided
        if (isset($_FILES['media_file']) && $_FILES['media_file']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload_result = handleFileUpload($_FILES['media_file']);
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
                'roll_number' => $roll_number,
                'poster_name' => $poster_name,
                'email' => $email,
                'phone_number' => $phone_number,
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
                    $existingData = [];
                }
            }

            // Add the new item to the existing data
            $existingData[] = $newItem;

            // Write the updated data back to the JSON file
            if (file_put_contents($file_path, json_encode($existingData, JSON_PRETTY_PRINT))) {
                $message = 'Found item posted successfully!';
                $messageType = 'success';
                $_POST = array();
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
    <title>Post Found Item - Lost & Found Hub</title>
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
            background: var(--gradient-hero); /* Adopted from new hero background */
            min-height: 100vh;
            overflow-x: hidden;
            scroll-behavior: smooth;
            position: relative;
            display: flex; /* Make body a flex container for footer push */
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
            width: 1000px;
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
            gap: 2.0rem; /* Adjusted gap for navigation items */
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
            z-index: 1001; /* Ensure it's above other elements */
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
            background: rgba(255, 255, 255, 0.99); /* Using the new gradient card background */
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-blue);
            box-shadow: var(--shadow-lg); /* Updated shadow */
            border-radius: 1.5rem;
            animation: fadeInScale 0.8s ease-out forwards;
            position: relative; /* Ensure z-index works */
            z-index: 1; /* Above canvas particles */
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
            background-color: var(--bg-blue); /* Lighter background for inputs */
            border-color: var(--border); /* Default border color */
            color: var(--text-primary);
        }

        .enhanced-input:focus {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md); /* Updated shadow */
            border-color: var(--primary); /* Focus border color */
            background-color: var(--bg-primary); /* White background on focus */
        }

        .floating-label {
            position: relative;
        }

        .floating-label input:focus + label,
        .floating-label input:not(:placeholder-shown) + label,
        .floating-label textarea:focus + label,
        .floating-label textarea:not(:placeholder-shown) + label {
            transform: translateY(-28px) scale(0.8);
            color: var(--primary-dark); /* Focused label color */
            font-weight: 600;
            background-color: var(--bg-primary); /* Label background on float */
            padding: 0 0.4rem;
            border-radius: 0.3rem;
        }

        .floating-label label {
            position: absolute;
            left: 1rem;
            top: 0.85rem;
            transition: all 0.3s ease;
            pointer-events: none;
            color: var(--text-light); /* Default label color */
            z-index: 1;
            transform-origin: left top;
        }

        .btn-primary {
            background: var(--gradient-primary); /* Updated to new primary gradient */
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-blue); /* Updated shadow */
            color: white; /* Ensure text is white */
            font-weight: 700; /* Bolder text */
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--shine-gradient); /* Shine effect */
            transform: skewX(-30deg);
            transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-2xl); /* More pronounced shadow on hover */
        }

        .file-upload {
            position: relative;
            border: 2px dashed var(--border); /* Updated border color */
            border-radius: 12px;
            padding: 2.5rem 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            background: var(--bg-blue); /* Blue background for upload area */
            cursor: pointer;
            color: var(--text-secondary);
        }

        .file-upload:hover {
            border-color: var(--primary-light); /* Blue border on hover */
            background: var(--primary-pale); /* Lighter blue gradient on hover */
            transform: translateY(-3px);
            box-shadow: var(--shadow-sm);
        }

        .file-upload.dragover {
            border-color: var(--success); /* Green for dragover */
            background: rgba(16, 185, 129, 0.1); /* Lighter green for dragover */
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

        .spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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

        .footer {
            background: linear-gradient(135deg, var(--text-primary) 0%, #0f172a 100%);
            color: white;
            padding: 60px 0 30px; /* Reduced padding slightly */
            position: relative;
            overflow: hidden;
            margin-top: auto; /* Push footer to bottom */
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-mesh);
            opacity: 0.05;
        }

        .footer-container {
            position: relative;
            z-index: 1;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 4rem;
            margin-bottom: 3rem; /* Reduced margin */
        }

        .footer-section h3 {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            color: white;
            position: relative;
        }

        .footer-section h3::after {
            content: '';
            position: absolute;
            bottom: -0.5rem;
            left: 0;
            width: 40px;
            height: 3px;
            background: var(--gradient-primary);
            border-radius: 2px;
        }

        .footer-section p,
        .footer-section a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            line-height: 1.8;
            display: block;
            margin-bottom: 0.8rem;
            transition: all 0.3s ease;
        }

        .footer-section a:hover {
            color: var(--primary-light);
            transform: translateX(5px);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 2rem; /* Reduced padding */
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
            font-size: 1rem;
        }


        @media (max-width: 992px) {
            .nav-container {
                flex-wrap: wrap; /* Allow wrapping on smaller screens */
                height: auto; /* Allow height to adjust */
                padding: 1rem 1.5rem; /* Add vertical padding */
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
                gap: 0; /* Remove gap when stacked */
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(25px);
                border-top: 1px solid var(--border-blue);
                padding: 1rem 0;
                box-shadow: var(--shadow-md);
                position: absolute;
                top: 85px; /* Adjust this dynamically or based on fixed height */
                left: 0;
                transform: translateY(-100%); /* Start hidden above */
                opacity: 0;
                transition: transform 0.4s ease-out, opacity 0.4s ease-out;
            }

            .nav-menu.active {
                display: flex;
                transform: translateY(0);
                opacity: 1;
            }

            .nav-link {
                padding: 0.8rem 0; /* Adjust padding for mobile links */
                justify-content: center; /* Center icons and text */
            }

            .mobile-menu-btn {
                display: flex; /* Show hamburger button */
            }
            body {
                padding-top: 75px; /* Adjust padding for mobile fixed header */
            }
            .hero-title {
                font-size: clamp(2rem, 8vw, 4rem);
            }
            .hero-subtitle {
                font-size: 1.2rem;
            }
            .hero-actions {
                flex-direction: column;
                gap: 1.5rem;
            }
            .btn {
                padding: 1rem 2rem;
                font-size: 1rem;
                width: 80%;
                max-width: 300px;
            }
            .features {
                padding: 80px 0;
            }
            .feature-card {
                padding: 2rem;
            }
            .feature-icon {
                width: 70px;
                height: 70px;
                font-size: 1.8rem;
            }
            .feature-title {
                font-size: 1.5rem;
            }
            .feature-description {
                font-size: 1rem;
            }
            .browse {
                padding: 80px 0;
            }
            .browse-btn {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
                padding: 2rem;
                min-width: unset;
                width: 80%;
                max-width: 300px;
            }
            .browse-icon {
                width: 55px;
                height: 55px;
                font-size: 1.5rem;
            }
            .browse-text .title {
                font-size: 1.2rem;
            }
            .browse-text .subtitle {
                font-size: 0.9rem;
            }
            .footer-content {
                grid-template-columns: 1fr;
                gap: 3rem;
                text-align: center;
            }
            .footer-section h3::after {
                left: 50%;
                transform: translateX(-50%);
            }
            .back-to-top {
                bottom: 1.5rem;
                right: 1.5rem;
                width: 50px;
                height: 50px;
                font-size: 1.4rem;
            }
        }

        canvas {
            display: block;
        }

    
    </style>
</head>
<body class="flex flex-col min-h-screen" id="i">
    <canvas id="particleCanvas" class="canvas-container"></canvas>

    <header class="navbar" id="navbar">
        <div class="nav-container">
            <a href="<?php echo $basePath; ?>/index.php" class="logo">
                <span class="logo-icon"><i class="fas fa-search-location"></i></span>
                Lost & Found Hub
            </a>
            <nav>
                <ul class="nav-menu" id="navMenu">
                    <li><a href="<?php echo $basePath; ?>/index.php" class="nav-link <?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="<?php echo $basePath; ?>/lost_post.php" class="nav-link <?php echo ($currentPage == 'lost_post.php') ? 'active' : ''; ?>"><i class="fas fa-exclamation-circle"></i> Post Lost Item</a></li>
                    <li><a href="<?php echo $basePath; ?>/found_post.php" class="nav-link <?php echo ($currentPage == 'found_post.php') ? 'active' : ''; ?>"><i class="fas fa-hand-holding-heart"></i> Post Found Item</a></li>
                    <li><a href="<?php echo $basePath; ?>/lost_show.php" class="nav-link <?php echo ($currentPage == 'lost_show.php') ? 'active' : ''; ?>"><i class="fas fa-eye"></i> View Lost Items</a></li>
                    <li><a href="<?php echo $basePath; ?>/found_show.php" class="nav-link <?php echo ($currentPage == 'found_show.php') ? 'active' : ''; ?>"><i class="fas fa-clipboard-list"></i> View Found Items</a></li>
                </ul>
            </nav>
            <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Toggle mobile menu">
                <i class="fas fa-bars"></i>
                <i class="fas fa-times"></i>
            </button>
        </div>
    </header>

    <main class="flex-grow container max-w-4xl mx-auto my-8 px-6 relative z-10">
        <div class="glass-card p-8 shadow-2xl">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-19">
                    <!-- Changed icon to reflect 'found' theme -->
                    <i class="fa-solid fa-hand-holding-heart text-green-600 text-4xl"></i>
                </div>
                <!-- Changed title and color to reflect 'found' theme -->
                <h2 class="text-4xl font-extrabold bg-gradient-to-r from-green-600 to-green-800 bg-clip-text text-transparent mb-3">Post a Found </h2>
                <p class="text-gray-600 text-lg">Help reunite lost items with their owners by sharing details</p>
            </div>

            <?php if ($message): ?>
                <div class="alert p-4 mb-6 <?php echo $messageType === 'success' ? 'bg-green-100 text-green-800 border-green-300' : 'bg-red-100 text-red-800 border-red-300'; ?> border">
                    <div class="flex items-center">
                        <i class="<?php echo $messageType === 'success' ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-exclamation'; ?> text-xl mr-3"></i>
                        <p class="font-medium"><?php echo $message; ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data" class="space-y-8" id="foundItemForm">

                <div class="section-divider">
                    <span><i class="fa-solid fa-user-circle mr-2 text-primary"></i>Your Details</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group floating-label">
                        <input type="text" id="roll_number" name="roll_number" required placeholder=" "
                               class="enhanced-input w-full py-3 px-4 border rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500"
                               value="<?php echo htmlspecialchars($_POST['roll_number'] ?? ''); ?>">
                        <label for="roll_number"><i class="fa-solid fa-hashtag mr-2"></i>Roll Number</label>
                    </div>

                    <div class="form-group floating-label">
                        <input type="text" id="poster_name" name="poster_name" required placeholder=" "
                               class="enhanced-input w-full py-3 px-4 border rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500"
                               value="<?php echo htmlspecialchars($_POST['poster_name'] ?? ''); ?>">
                        <label for="poster_name"><i class="fa-solid fa-user-alt mr-2"></i>Full Name</label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group floating-label">
                        <input type="email" id="email" name="email" required placeholder=" "
                               class="enhanced-input w-full py-3 px-4 border rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500"
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                        <label for="email"><i class="fa-solid fa-at mr-2"></i>Email Address</label>
                    </div>

                    <div class="form-group floating-label">
                        <input type="text" id="phone_number" name="phone_number" placeholder=" "
                               class="enhanced-input w-full py-3 px-4 border rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500"
                               value="<?php echo htmlspecialchars($_POST['phone_number'] ?? ''); ?>">
                        <label for="phone_number"><i class="fa-solid fa-phone mr-2"></i>Phone Number (Optional)</label>
                    </div>
                </div>

                <div class="section-divider">
                    <span><i class="fa-solid fa-box-open mr-2 text-primary"></i>Item Details</span>
                </div>

                <div class="form-group floating-label">
                    <input type="text" id="title" name="title" required placeholder=" "
                           class="enhanced-input w-full py-3 px-4 border rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500"
                           value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                    <label for="title"><i class="fa-solid fa-tag mr-2"></i>Item Title</label>
                </div>

                <div class="form-group floating-label">
                    <textarea id="description" name="description" rows="4" required placeholder=" "
                              class="enhanced-input w-full py-3 px-4 border rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                    <label for="description"><i class="fa-solid fa-info-circle mr-2"></i>Description of Found Item</label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group floating-label">
                        <input type="text" id="location" name="location" required placeholder=" "
                               class="enhanced-input w-full py-3 px-4 border rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500"
                               value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>">
                        <label for="location"><i class="fa-solid fa-map-marker-alt mr-2"></i>Location Found</label>
                    </div>

                    <div class="form-group floating-label">
                        <input type="date" id="date" name="date" required
                               class="enhanced-input w-full py-3 px-4 border rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500"
                               value="<?php echo htmlspecialchars($_POST['date'] ?? date('Y-m-d')); ?>">
                        <label for="date"><i class="fa-solid fa-calendar-alt mr-2"></i>Date Found</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="media_file" class="block text-gray-700 text-sm font-semibold mb-2">Upload Photo or Video (Max 40MB):</label>
                    <div class="file-upload" id="fileUploadArea">
                        <input type="file" id="media_file" name="media_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*,video/*">
                        <i class="fa-solid fa-cloud-arrow-up text-4xl text-blue-400 mb-2"></i>
                        <p class="text-lg font-medium text-gray-700">Drag & Drop files here or <span class="text-blue-600 font-bold">Click to Browse</span></p>
                        <p class="text-sm text-gray-500 mt-1" id="fileInfo">No file chosen</p>
                        <p class="text-xs text-gray-500 mt-2">Allowed formats: JPG, PNG, GIF, WebP, MP4, WebM, Ogg.</p>
                    </div>
                </div>

                <button type="submit" id="submitButton" class="btn-primary w-full py-3 px-6 rounded-xl text-lg flex items-center justify-center gap-2">
                    <span id="buttonText">Post Found Item</span>
                    <div id="spinner" class="spinner hidden"></div>
                </button>
            </form>
        </div>
    </main>

    <footer class="footer">
        <div class="footer-container container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Lost & Found Hub</h3>
                    <p>Connecting lost items with their rightful owners, building a more responsible community.</p>
                    <p class="text-sm">"Finders Keepers, Losers Weepers" no more!</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <a href="<?php echo $basePath; ?>/lost_post.php">Post Lost Item</a>
                    <a href="<?php echo $basePath; ?>/found_post.php">Post Found Item</a>
                    <a href="<?php echo $basePath; ?>/lost_show.php">View Lost Items</a>
                    <a href="<?php echo $basePath; ?>/found_show.php">View Found Items</a>
                </div>
                <div class="footer-section">
                    <h3>Contact Us</h3>
                    <p>Email: support@lostfoundhub.com</p>
                    <p>Phone: +1 (123) 456-7890</p>
                    <p>Address: University Campus, City, Country</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date("Y"); ?> Lost & Found Hub. All rights reserved.</p>
            </div>
        </div>
    </footer>

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

        mobileMenuBtn.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            mobileMenuBtn.classList.toggle('active');
        });

        // Floating labels (JavaScript to handle input focus/blur for placeholder behavior)
        document.querySelectorAll('.floating-label input, .floating-label textarea').forEach(input => {
            input.addEventListener('focus', () => {
                input.setAttribute('placeholder', '');
            });
            input.addEventListener('blur', () => {
                if (input.value === '') {
                    input.setAttribute('placeholder', ' ');
                }
            });
            // Trigger immediately in case of pre-filled values (e.g., after form submission with errors)
            if (input.value !== '') {
                input.parentNode.querySelector('label').style.transform = 'translateY(-28px) scale(0.8)';
                input.parentNode.querySelector('label').style.color = 'var(--primary-dark)';
                input.parentNode.querySelector('label').style.fontWeight = '600';
                input.parentNode.querySelector('label').style.backgroundColor = 'var(--bg-primary)';
                input.parentNode.querySelector('label').style.padding = '0 0.4rem';
                input.parentNode.querySelector('label').style.borderRadius = '0.3rem';
            }
        });

        // File upload drag & drop and preview
        const fileUploadArea = document.getElementById('fileUploadArea');
        const mediaFileInput = document.getElementById('media_file');
        const fileInfo = document.getElementById('fileInfo');

        fileUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileUploadArea.classList.add('dragover');
        });

        fileUploadArea.addEventListener('dragleave', () => {
            fileUploadArea.classList.remove('dragover');
        });

        fileUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            fileUploadArea.classList.remove('dragover');
            if (e.dataTransfer.files.length > 0) {
                mediaFileInput.files = e.dataTransfer.files;
                updateFileInfo();
            }
        });

        mediaFileInput.addEventListener('change', updateFileInfo);

        function updateFileInfo() {
            if (mediaFileInput.files.length > 0) {
                const file = mediaFileInput.files[0];
                fileInfo.textContent = `Selected file: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
            } else {
                fileInfo.textContent = 'No file chosen';
            }
        }

        // Form submission loading indicator
        const lostItemForm = document.getElementById('foundItemForm');
        const submitButton = document.getElementById('submitButton');
        const buttonText = document.getElementById('buttonText');
        const spinner = document.getElementById('spinner');

        lostItemForm.addEventListener('submit', function() {
            submitButton.disabled = true;
            buttonText.textContent = 'Submitting...';
            spinner.classList.remove('hidden');
        });

        // Particles JS (simplified for demonstration, typically this would be a separate library/file)
        const canvas = document.getElementById('particleCanvas');
        const ctx = canvas.getContext('2d');
        let particles = [];
        const numParticles = 100;
        const particleSize = 1.5;
        const particleSpeed = 0.5;

        // Resize canvas on window resize
        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            // Re-initialize particles on resize to ensure they fit the new dimensions
            particles = [];
            initParticles();
        }

        // Particle properties (using a more defined color palette)
        function initParticles() {
            for (let i = 0; i < numParticles; i++) {
                particles.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height,
                    size: Math.random() * particleSize + 0.5, // 0.5 to particleSize + 0.5
                    speedX: (Math.random() - 0.5) * 2 * particleSpeed, // -particleSpeed to particleSpeed
                    speedY: (Math.random() - 0.5) * 2 * particleSpeed,
                    color: `rgba(255, 255, 255, ${Math.random() * 0.4 + 0.1})` // White with low opacity
                });
            }
        }

        // Draw particles
        function drawParticles() {
            ctx.clearRect(0, 0, canvas.width, canvas.height); // Clear canvas
            for (let i = 0; i < particles.length; i++) {
                const p = particles[i];
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                ctx.fillStyle = p.color;
                ctx.fill();
            }
        }

        // Update particle positions
        function updateParticles() {
            for (let i = 0; i < particles.length; i++) {
                const p = particles[i];

                p.x += p.speedX;
                p.y += p.speedY;

                // Bounce off walls
                if (p.x < 0 || p.x > canvas.width) p.speedX *= -1;
                if (p.y < 0 || p.y > canvas.height) p.speedY *= -1;
            }
        }

        // Animation loop
        function animateParticles() {
            updateParticles();
            drawParticles();
            requestAnimationFrame(animateParticles);
        }

        // Initialize and start animation when window loads
        window.addEventListener('load', function() {
            resizeCanvas(); // Set initial canvas size
            animateParticles(); // Start the animation loop
        });

        // Event listener for window resize
        window.addEventListener('resize', resizeCanvas);
    </script>
</body>
</html>
