<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost & Found Hub - Reconnecting Communities</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* --- CSS Variables --- */
        :root {
            --primary: #4f46e5; /* Deeper, more vibrant indigo */
            --primary-dark: #4338ca;
            --primary-light: #eef2ff; /* Lighter, subtle indigo for backgrounds */
            --secondary: #14b8a6; /* Teal */
            --accent: #f97316; /* Orange */
            --danger: #ef4444; /* Red */

            --text-primary: #1e293b; /* Darker slate for better contrast */
            --text-secondary: #475569; /* Medium slate */
            --text-light: #94a3b8; /* Light slate */

            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc; /* Very light, almost white background */
            --bg-accent: #f0f4f8; /* Soft gray-blue for section backgrounds */
            --border: #e2e8f0;

            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 8px -2px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);

            --gradient-primary: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); /* Adjusted indigo gradient */
            --gradient-hero: linear-gradient(135deg, #6366f1 0%, #4f46e5 50%, #8b5cf6 100%); /* Indigo-purple hero gradient */
            --gradient-card: linear-gradient(145deg, #ffffff 0%, #f0f4f8 100%); /* Subtle off-white card gradient */
            
            --btn-primary-gradient: linear-gradient(135deg, #4f46e5, #4338ca);
            --btn-lost-gradient: linear-gradient(135deg, #ef4444, #dc2626);
            --btn-found-gradient: linear-gradient(135deg, #14b8a6, #0d9488);
            --shine-gradient: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        }

        /* --- Base Styles --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            background: radial-gradient(at top left, var(--primary-light) 5%, transparent 20%), 
                        radial-gradient(at bottom right, var(--bg-accent) 5%, transparent 20%), 
                        var(--bg-secondary);
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        /* --- Utility Classes --- */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* --- Navigation Styles --- */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: var(--shadow-md);
            height: 70px; /* Slightly shrink on scroll */
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 80px;
            transition: height 0.3s ease;
        }

        .navbar.scrolled .nav-container {
            height: 70px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.02);
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: var(--gradient-primary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            box-shadow: var(--shadow-sm);
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        .nav-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 0; /* No horizontal padding for underline effect */
            position: relative;
            transition: all 0.3s ease;
            display: flex; /* Added for icon alignment */
            align-items: center; /* Added for icon alignment */
            gap: 0.5rem; /* Space between icon and text */
        }

        .nav-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -5px; /* Position of the underline */
            width: 0%;
            height: 2px;
            background-color: var(--primary);
            transition: width 0.3s ease-out;
        }

        .nav-link:hover {
            color: var(--primary);
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        .nav-link.active {
            color: var(--primary);
            font-weight: 600;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.75rem;
            color: var(--text-primary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: background 0.3s ease, transform 0.3s ease;
            position: relative; /* For absolute positioning of icons */
            width: 44px; /* Ensure consistent size */
            height: 44px; /* Ensure consistent size */
        }

        .mobile-menu-btn:hover {
            background: var(--primary-light);
        }

        .mobile-menu-btn i {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            transition: all 0.3s ease;
        }

        .mobile-menu-btn .fa-times {
            opacity: 0;
            transform: translate(-50%, -50%) rotate(-90deg);
        }

        .mobile-menu-btn.active .fa-bars {
            opacity: 0;
            transform: translate(-50%, -50%) rotate(90deg);
        }

        .mobile-menu-btn.active .fa-times {
            opacity: 1;
            transform: translate(-50%, -50%) rotate(0deg);
        }


        /* --- Hero Section --- */
        .hero {
            background: var(--gradient-hero);
            padding: 120px 0 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 60vh;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><defs><linearGradient id="g1" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" stop-color="rgba(255,255,255,0.05)" /><stop offset="50%" stop-color="rgba(255,255,255,0.15)" /><stop offset="100%" stop-color="rgba(255,255,255,0.05)" /></linearGradient></defs><path fill="url(%23g1)" d="M0,0 L1000,0 C800,50 200,50 0,100 Z" /></svg>') repeat-x;
            background-size: 100% 100%;
            animation: float 12s ease-in-out infinite;
            opacity: 0.8;
            transform-origin: bottom;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-15px) scale(1.01); }
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4.2rem);
            font-weight: 800;
            color: white;
            margin-bottom: 1.5rem;
            text-shadow: 0 5px 25px rgba(0, 0, 0, 0.15);
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 1s ease-out forwards;
            animation-delay: 0.1s;
        }

        .hero-subtitle {
            font-size: 1.35rem;
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 2.5rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 1s ease-out forwards;
            animation-delay: 0.3s;
        }

        .hero-actions {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 1s ease-out forwards;
            animation-delay: 0.5s;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* --- Button Styles --- */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1.1rem 2.2rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1.05rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            z-index: 1; /* For shine effect */
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--shine-gradient);
            transition: left 0.5s ease-in-out;
            z-index: -1; /* Behind button content */
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:active { /* Press down effect */
            transform: translateY(1px);
            box-shadow: var(--shadow-sm);
        }

        .btn-primary {
            background: var(--btn-primary-gradient);
            color: white;
            border: 2px solid var(--primary-dark);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: white;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-secondary:hover {
            background: var(--primary-light);
            color: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .btn-lost {
            background: var(--btn-lost-gradient);
            color: white;
            border: none;
        }

        .btn-lost:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .btn-found {
            background: var(--btn-found-gradient);
            color: white;
            border: none;
        }

        .btn-found:hover {
            background: linear-gradient(135deg, #0d9488, #0f766e);
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        /* --- Feature Section --- */
        .features {
            padding: 80px 0;
            background: var(--bg-primary);
        }

        .features-title {
            text-align: center;
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 3.5rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2.5rem;
        }

        .feature-card {
            background: var(--gradient-card);
            border-radius: 20px;
            padding: 2.5rem;
            text-align: center;
            box-shadow: var(--shadow-md);
            transition: all 0.4s ease, border-color 0.3s ease;
            border: 1px solid var(--border);
            opacity: 0;
            transform: translateY(30px);
        }

        .feature-card.animate {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary); /* Highlight border on hover */
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 1.5rem;
            background: var(--gradient-primary);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
        }

        .feature-icon::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--shine-gradient);
            transition: left 0.5s ease-in-out;
            z-index: 0;
        }

        .feature-card:hover .feature-icon::before {
            left: 100%;
        }

        .feature-icon i {
            z-index: 1; /* Ensure icon is above shine effect */
        }

        .feature-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .feature-description {
            color: var(--text-secondary);
            line-height: 1.7;
        }

        /* --- Browse Section --- */
        .browse {
            padding: 80px 0;
            background: var(--bg-accent);
        }

        .browse-title {
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 1rem;
            text-align: center;
        }

        .browse-subtitle {
            font-size: 1.2rem;
            color: var(--text-secondary);
            margin-bottom: 3.5rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }

        .browse-actions {
            display: flex;
            gap: 2rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .browse-btn {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            padding: 1.8rem 2.5rem;
            background: white;
            border-radius: 18px;
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 600;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            border: 2px solid transparent; /* Ensure border for smooth transition */
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .browse-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--shine-gradient);
            transition: left 0.5s ease-in-out;
            z-index: -1;
        }

        .browse-btn:hover::before {
            left: 100%;
        }

        .browse-btn:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary);
        }

        .browse-icon {
            width: 55px;
            height: 55px;
            background: var(--gradient-primary);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.6rem;
            box-shadow: var(--shadow-sm);
        }

        /* --- Footer --- */
        .footer {
            background: var(--text-primary);
            color: white;
            padding: 60px 0 30px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-section h3 {
            font-size: 1.35rem;
            font-weight: 700;
            margin-bottom: 1.2rem;
            color: white;
        }

        .footer-section p,
        .footer-section a {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            line-height: 1.7;
            display: block;
            margin-bottom: 0.5rem;
        }

        .footer-section a:hover {
            color: var(--primary-light);
            text-decoration: underline;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            padding-top: 2rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
        }

        .disclaimer {
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid rgba(245, 158, 11, 0.3);
            border-radius: 12px;
            padding: 1.2rem;
            margin-top: 1.5rem;
            color: rgba(245, 158, 11, 0.95);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        /* --- Scroll-to-Top Button --- */
        .back-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: var(--primary);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: var(--shadow-lg);
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease, transform 0.3s ease;
            z-index: 999;
        }

        .back-to-top.show {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
        }

        .back-to-top:active {
            transform: translateY(1px);
        }

        /* --- Responsive Design --- */
        @media (max-width: 992px) {
            .nav-menu {
                gap: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .nav-menu {
                display: none;
                flex-direction: column;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100vh; /* Full viewport height */
                background: var(--bg-primary); /* White overlay */
                padding-top: 100px; /* Space for fixed navbar */
                z-index: 990; /* Below navbar, above content */
                transform: translateY(-100%); /* Start off-screen */
                opacity: 0;
                transition: transform 0.4s ease-out, opacity 0.4s ease-out;
                overflow-y: auto; /* Allow scrolling if menu is long */
            }

            .nav-menu.mobile-active {
                display: flex;
                transform: translateY(0); /* Slide in */
                opacity: 1;
            }

            .nav-menu li {
                width: 100%;
                text-align: center;
                margin-bottom: 1rem;
            }

            .nav-link {
                padding: 1rem 0;
                display: block; /* Make links fill the width */
                font-size: 1.2rem;
                border-radius: 0; /* Remove border-radius */
            }

            .nav-link::after {
                height: 3px;
                bottom: 0;
            }

            .mobile-menu-btn {
                display: block;
                z-index: 1001; /* Ensure button is above overlay */
            }

            .hero {
                padding: 100px 0 60px;
                min-height: unset;
            }

            .hero-title {
                font-size: clamp(2rem, 7vw, 3rem);
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .hero-actions {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 320px;
                justify-content: center;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .browse-actions {
                flex-direction: column;
                align-items: center;
            }

            .browse-btn {
                width: 100%;
                max-width: 320px;
            }

            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .footer-section p,
            .footer-section a {
                text-align: center;
                display: inline-block;
                margin: 0.2rem 0.5rem;
            }

            .back-to-top {
                bottom: 1.5rem;
                right: 1.5rem;
                width: 45px;
                height: 45px;
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="#" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-hand-sparkles"></i>
                </div>
                Lost & Found Hub
            </a>
            <ul class="nav-menu">
                <li><a href="index.php" class="nav-link active"><i class="fas fa-house-chimney"></i> Home</a></li>
                <li><a href="lost_post.php" class="nav-link"><i class="fas fa-magnifying-glass-minus"></i> Post Lost</a></li>
                <li><a href="found_post.php" class="nav-link"><i class="fas fa-box-open"></i> Post Found</a></li>
                <li><a href="lost_show.php" class="nav-link"><i class="fas fa-magnifying-glass-plus"></i> Lost Items</a></li>
                <li><a href="found_show.php" class="nav-link"><i class="fas fa-hand-holding-dollar"></i> Found Items</a></li>
                <li><a href="admin_login.php" class="nav-link"><i class="fas fa-user-shield"></i> Admin</a></li>
            </ul>
            <button class="mobile-menu-btn" aria-label="Toggle mobile menu">
                <i class="fas fa-bars"></i>
                <i class="fas fa-times"></i>
            </button>
        </div>
    </nav>

    <section class="hero">
        <div class="container hero-container">
            <h1 class="hero-title">Reunite Lost Items with Their Owners</h1>
            <p class="hero-subtitle">
                A modern platform connecting communities to help lost items find their way home.
                Fast, secure, and designed with care for every reunion story.
            </p>
            <div class="hero-actions">
                <a href="lost_post.php" class="btn btn-lost">
                    <i class="fas fa-compass-drafting"></i>
                    I Lost Something
                </a>
                <a href="found_post.php" class="btn btn-found">
                    <i class="fas fa-hand-holding-droplet"></i>
                    I Found Something
                </a>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="container features-container">
            <h2 class="features-title">How It Works</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <h3 class="feature-title">Post Items</h3>
                    <p class="feature-description">
                        Quickly post details about lost or found items with photos and descriptions.
                        Our intuitive form makes it easy to provide all the necessary details.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-filter"></i>
                    </div>
                    <h3 class="feature-title">Smart Search</h3>
                    <p class="feature-description">
                        Advanced search and filtering options help you find exactly what you're looking for.
                        Search by category, location, date, and more for precise results.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-people-arrows"></i>
                    </div>
                    <h3 class="feature-title">Safe Reunions</h3>
                    <p class="feature-description">
                        Connect safely with item owners through our platform.
                        Built-in verification and communication tools ensure secure and authentic reunions.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="browse">
        <div class="container browse-container">
            <h2 class="browse-title">Browse Current Listings</h2>
            <p class="browse-subtitle">
                Check out items that are currently waiting to be reunited with their owners.
                Your missing item might already be here!
            </p>
            <div class="browse-actions">
                <a href="lost_show.php" class="browse-btn">
                    <div class="browse-icon">
                        <i class="fas fa-map-pin"></i>
                    </div>
                    <div>
                        <div style="font-size: 1.2rem; font-weight: 700;">Lost Items</div>
                        <div style="font-size: 0.95rem; color: var(--text-secondary);">Items people are actively looking for</div>
                    </div>
                </a>
                <a href="found_show.php" class="browse-btn">
                    <div class="browse-icon">
                        <i class="fas fa-location-crosshairs"></i>
                    </div>
                    <div>
                        <div style="font-size: 1.2rem; font-weight: 700;">Found Items</div>
                        <div style="font-size: 0.95rem; color: var(--text-secondary);">Items waiting to be claimed by their owners</div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container footer-container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Lost & Found Hub</h3>
                    <p>
                        Connecting communities and reuniting people with their lost belongings.
                        Every item has a story, and every reunion matters.
                    </p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <p><a href="lost_post.php">Post Lost Item</a></p>
                    <p><a href="found_post.php">Post Found Item</a></p>
                    <p><a href="lost_show.php">Browse Lost Items</a></p>
                    <p><a href="found_show.php">Browse Found Items</a></p>
                </div>
                <div class="footer-section">
                    <h3>Support</h3>
                    <p><a href="#">Help Center</a></p>
                    <p><a href="#">Contact Us</a></p>
                    <p><a href="admin_login.php">Admin Portal</a></p>
                    <p><a href="#">Privacy Policy</a></p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Lost & Found Hub. All rights reserved.</p>
                <div class="disclaimer">
                    <strong>Development Notice:</strong> This application uses file-based storage for demonstration purposes.
                    Not recommended for production use due to security and scalability considerations.
                </div>
            </div>
        </div>
    </footer>

    <button class="back-to-top" id="backToTopBtn" aria-label="Back to Top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Navbar scroll effect
            const navbar = document.getElementById('navbar');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });

            // Intersection Observer for feature card fade-in animations
            const featureCards = document.querySelectorAll('.feature-card');

            const observerOptions = {
                threshold: 0.1, /* Trigger when 10% of the element is visible */
                rootMargin: '0px 0px -50px 0px' /* Adjust to trigger slightly before element is fully in view */
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const delay = Array.from(featureCards).indexOf(entry.target) * 0.2; /* 0.2s delay between cards */
                        entry.target.style.animationDelay = `${delay}s`;
                        entry.target.classList.add('animate');
                        observer.unobserve(entry.target); /* Stop observing once animated */
                    }
                });
            }, observerOptions);

            featureCards.forEach(card => {
                observer.observe(card);
            });

            // Mobile menu toggle
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const navMenu = document.querySelector('.nav-menu');
            const barsIcon = mobileMenuBtn.querySelector('.fa-bars');
            const timesIcon = mobileMenuBtn.querySelector('.fa-times');

            mobileMenuBtn.addEventListener('click', function() {
                navMenu.classList.toggle('mobile-active');
                mobileMenuBtn.classList.toggle('active'); // Toggle class on button for icon animation

                if (navMenu.classList.contains('mobile-active')) {
                    document.body.style.overflow = 'hidden'; // Prevent scrolling when menu is open
                } else {
                    document.body.style.overflow = ''; // Restore scrolling
                }
            });

            // Close mobile menu when a link is clicked
            navMenu.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', () => {
                    if (navMenu.classList.contains('mobile-active')) {
                        navMenu.classList.remove('mobile-active');
                        mobileMenuBtn.classList.remove('active');
                        document.body.style.overflow = ''; // Restore scrolling
                    }
                });
            });

            // Back to top button functionality
            const backToTopBtn = document.getElementById('backToTopBtn');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 300) {
                    backToTopBtn.classList.add('show');
                } else {
                    backToTopBtn.classList.remove('show');
                }
            });

            backToTopBtn.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>