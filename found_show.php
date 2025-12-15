<?php
session_start();
include 'stdnav.php';
?>
<?php
// Include the configuration file for shared functions and constants
require_once 'config.php';

// Get the base path for navigation
$basePath = getBasePath();
// Get the URL path for uploaded media
$uploadUrlPath = getUploadUrlPath();

$foundItems = []; // Array to store found items fetched from the JSON file
$filePath = getDataDirPath() . 'found_items.json'; // Changed variable name for consistency

// Get current page for active navigation link
$currentPage = basename($_SERVER['PHP_SELF']);

// Check if the found_items.json file exists and read its content
if (file_exists($filePath)) {
    $jsonContent = file_get_contents($filePath);
    $foundItems = json_decode($jsonContent, true); // Decode JSON into a PHP associative array

    // Ensure that $foundItems is an array, even if the file is empty or malformed
    if (!is_array($foundItems)) {
        $foundItems = [];
    }
    // Sort items by timestamp in descending order (most recent first)
    usort($foundItems, function($a, $b) {
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
    <title>View Found Items - Lost & Found Hub</title>
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
            --success: #10b981; /* This is a good green for "found" */
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
            font-family: 'Inter', -apple-system, BlinkMacMacSystemFont, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            background: var(--gradient-hero);
            min-height: 100vh;
            overflow-x: hidden;
            scroll-behavior: smooth;
            position: relative;
            display: flex;
            flex-direction: column;
            padding-top: 85px;
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
            z-index: 0;
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
            0%, 100% {
                transform: translateX(-100%) translateY(-100%) rotate(45deg);
            }
            50% {
                transform: translateX(100%) translateY(100%) rotate(45deg);
            }
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
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .item-card {
            background: var(--gradient-card);
            border-radius: 1.25rem;
            box-shadow: var(--shadow-md);
            padding: 1.5rem;
            transition: all 0.3s ease-in-out;
            border: 1px solid var(--border-light);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .item-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            border-color: var(--success); /* Changed border color on hover for found items */
        }

        .item-card h3 {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
        }

        .item-card p {
            font-size: 0.95rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .item-card p strong {
            color: var(--text-primary);
            font-weight: 600;
        }

        .item-card a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .item-card a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .item-card .contact-info {
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--border);
        }

        .item-card .contact-info h4 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .item-card .media-preview {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-light);
        }

        .item-card video {
            background-color: #000;
        }

        .footer {
            background: linear-gradient(135deg, var(--text-primary) 0%, #0f172a 100%);
            color: white;
            padding: 60px 0 30px;
            position: relative;
            overflow: hidden;
            margin-top: auto;
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
            margin-bottom: 3rem;
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
            padding-top: 2rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
            font-size: 1rem;
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
                    <li><a href="<?php echo $basePath; ?>/lost_index.php" class="nav-link <?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>"><i class="fas fa-home"></i> Home</a></li>
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
                    <i class="fa-solid fa-hand-holding-heart text-green-600 text-4xl"></i> </div>
                <h2 class="text-4xl font-extrabold bg-gradient-to-r from-green-600 to-green-800 bg-clip-text text-transparent mb-3">Found Items Reported</h2> <p class="text-gray-600 text-lg">Browse through items that have been reported found by others.</p>
            </div>

            <?php if (empty($foundItems)): ?>
                <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-xl flex items-center justify-center gap-3"> <i class="fas fa-info-circle text-2xl"></i>
                    <p class="font-medium text-lg">No found items posted yet. Be the first to report one!</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-6"> <?php foreach ($foundItems as $item): ?>
                        <div class="item-card flex flex-col justify-between">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($item['title'] ?? 'N/A'); ?></h3>
                                <p class="text-gray-700 text-sm mb-1"><strong>Description:</strong> <?php echo htmlspecialchars($item['description'] ?? 'N/A'); ?></p>
                                <p class="text-gray-600 text-sm mb-1"><strong>Found At:</strong> <?php echo htmlspecialchars($item['location'] ?? 'N/A'); ?></p>
                                <p class="text-gray-600 text-sm mb-2"><strong>Date Found:</strong> <?php echo htmlspecialchars($item['date'] ?? 'N/A'); ?></p>

                                <div class="contact-info">
                                    <h4><i class="fa-solid fa-address-card mr-2 text-success"></i> Finder Contact:</h4> <p class="text-gray-700 text-sm mb-1"><strong>Roll Number:</strong> <?php echo htmlspecialchars($item['roll_number'] ?? 'N/A'); ?></p>
                                    <p class="text-gray-700 text-sm mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($item['poster_name'] ?? 'N/A'); ?></p>
                                    <p class="text-blue-700 text-sm mb-1"><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($item['email'] ?? ''); ?>"><?php echo htmlspecialchars($item['email'] ?? 'N/A'); ?></a></p>
                                    <?php if (!empty($item['phone_number'])): ?>
                                        <p class="text-blue-700 text-sm mb-1"><strong>Phone:</strong> <a href="tel:<?php echo htmlspecialchars($item['phone_number']); ?>"><?php echo htmlspecialchars($item['phone_number']); ?></a></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <?php if (!empty($item['media_path'])): ?>
                                    <?php
                                    $mediaUrl = $uploadUrlPath . htmlspecialchars($item['media_path']);
                                    $mediaType = getMediaType($item['media_path']);
                                    ?>
                                    <div class="mb-4">
                                        <?php if ($mediaType === 'image'): ?>
                                            <img src="<?php echo $mediaUrl; ?>" alt="Found Item Image" class="media-preview">
                                        <?php elseif ($mediaType === 'video'): ?>
                                            <video controls class="media-preview">
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
        window.addEventListener('scroll', function () {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const navMenu = document.getElementById('navMenu');

        mobileMenuBtn.addEventListener('click', function () {
            navMenu.classList.toggle('active');
            mobileMenuBtn.classList.toggle('active');
        });

        const canvas = document.getElementById('particleCanvas');
        const ctx = canvas.getContext('2d');
        let particles = [];
        const numParticles = 100;
        const particleSize = 1.5;
        const particleSpeed = 0.5;

        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            particles = [];
            initParticles();
        }

        function initParticles() {
            for (let i = 0; i < numParticles; i++) {
                particles.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height,
                    size: Math.random() * particleSize + 0.5,
                    speedX: (Math.random() - 0.5) * 2 * particleSpeed,
                    speedY: (Math.random() - 0.5) * 2 * particleSpeed,
                    color: `rgba(255, 255, 255, ${Math.random() * 0.4 + 0.1})`
                });
            }
        }

        function drawParticles() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            for (let i = 0; i < particles.length; i++) {
                const p = particles[i];
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                ctx.fillStyle = p.color;
                ctx.fill();
            }
        }

        function updateParticles() {
            for (let i = 0; i < particles.length; i++) {
                const p = particles[i];

                p.x += p.speedX;
                p.y += p.speedY;

                if (p.x < 0 || p.x > canvas.width) p.speedX *= -1;
                if (p.y < 0 || p.y > canvas.height) p.speedY *= -1;
            }
        }

        function animateParticles() {
            updateParticles();
            drawParticles();
            requestAnimationFrame(animateParticles);
        }

        window.addEventListener('load', () => {
            resizeCanvas();
            animateParticles();
        });
        window.addEventListener('resize', resizeCanvas);
    </script>
</body>
</html>