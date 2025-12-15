<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost & Found Hub - Reconnecting Communities</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb; /* Deeper, more vibrant blue */
            --primary-dark: #1e40af;
            --primary-light: #eff6ff; /* Lighter, subtle blue for backgrounds */
            --secondary: #10b981;
            --accent: #f59e0b;
            --danger: #ef4444;
            --text-primary: #1a202c; /* Slightly darker text for better contrast */
            --text-secondary: #4a5568;
            --text-light: #a0aec0;
            --bg-primary: #ffffff;
            --bg-secondary: #f7fafc; /* Very light, almost white background */
            --bg-accent: #e2e8f0; /* Soft gray-blue for section backgrounds */
            --border: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --gradient-primary: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); /* Slightly adjusted blue gradient */
            --gradient-hero: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #6366f1 100%); /* Blue-purple hero gradient */
            --gradient-card: linear-gradient(145deg, #ffffff 0%, #f0f4f8 100%); /* Subtle off-white card gradient */
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
            background: var(--bg-secondary);
            overflow-x: hidden;
        }

        /* Utility Classes */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Navigation Styles */
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
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 80px;
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
            transform: scale(1.05);
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
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: var(--primary);
            background: var(--primary-light);
            transform: translateY(-1px);
        }

        .nav-link.active {
            color: var(--primary);
            background: var(--primary-light);
            font-weight: 600;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-primary);
            cursor: pointer;
        }

        /* Hero Section */
        .hero {
            background: var(--gradient-hero);
            padding: 120px 0 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><polygon points="0,0 1000,0 1000,100 0,100"/></svg>');
            animation: float 6s ease-in-out infinite;
            opacity: 0.8; /* Slightly less opaque */
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            color: white;
            margin-bottom: 1.5rem;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            opacity: 0; /* Initial state for animation */
            transform: translateY(30px); /* Initial state for animation */
            animation: fadeInUp 1s ease-out forwards;
            animation-delay: 0.1s;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2.5rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            opacity: 0; /* Initial state for animation */
            transform: translateY(30px); /* Initial state for animation */
            animation: fadeInUp 1s ease-out forwards;
            animation-delay: 0.3s;
        }

        .hero-actions {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
            opacity: 0; /* Initial state for animation */
            transform: translateY(30px); /* Initial state for animation */
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

        /* Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease-in-out;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            border: 2px solid var(--primary-dark);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: white;
            color: var(--primary);
            border: 2px solid var(--primary-light);
        }

        .btn-secondary:hover {
            background: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-lost {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border: none;
        }

        .btn-lost:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-found {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
        }

        .btn-found:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Feature Section */
        .features {
            padding: 80px 0;
            background: var(--bg-primary);
        }

        .features-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 3rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: var(--gradient-card);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            border: 1px solid var(--border);
            opacity: 0; /* Initial state for animation */
            transform: translateY(30px); /* Initial state for animation */
        }

        .feature-card.animate { /* Class added by JS to trigger animation */
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: var(--gradient-primary);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .feature-description {
            color: var(--text-secondary);
            line-height: 1.6;
        }

        /* Browse Section */
        .browse {
            padding: 80px 0;
            background: var(--bg-accent);
        }

        .browse-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 1rem;
            text-align: center;
        }

        .browse-subtitle {
            font-size: 1.125rem;
            color: var(--text-secondary);
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }

        .browse-actions {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .browse-btn {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem 2rem;
            background: white;
            border-radius: 16px;
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 600;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .browse-btn:hover {
            transform: translateY(-3px) scale(1.02); /* Slight scale on hover */
            box-shadow: var(--shadow-xl);
            border-color: var(--primary);
        }

        .browse-icon {
            width: 50px;
            height: 50px;
            background: var(--gradient-primary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        /* Footer */
        .footer {
            background: var(--text-primary);
            color: white;
            padding: 60px 0 30px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-section h3 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }

        .footer-section p,
        .footer-section a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            line-height: 1.6;
        }

        .footer-section a:hover {
            color: var(--primary-light);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 2rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
        }

        .disclaimer {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.2);
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1rem;
            color: rgba(245, 158, 11, 0.9);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }

            .mobile-menu-btn {
                display: block;
            }

            .hero-actions {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }

            .browse-actions {
                flex-direction: column;
                align-items: center;
            }

            .browse-btn {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="#" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-search"></i>
                </div>
                Lost & Found Hub
            </a>
            <ul class="nav-menu">
                <li><a href="index.php" class="nav-link active">Home</a></li>
                <li><a href="lost_post.php" class="nav-link">Post Lost</a></li>
                <li><a href="found_post.php" class="nav-link">Post Found</a></li>
                <li><a href="lost_show.php" class="nav-link">Lost Items</a></li>
                <li><a href="found_show.php" class="nav-link">Found Items</a></li>
                <li><a href="admin_login.php" class="nav-link">Admin</a></li>
            </ul>
            <button class="mobile-menu-btn" aria-label="Toggle mobile menu">
                <i class="fas fa-bars"></i>
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
                    <i class="fas fa-exclamation-triangle"></i>
                    I Lost Something
                </a>
                <a href="found_post.php" class="btn btn-found">
                    <i class="fas fa-hand-holding-heart"></i>
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
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <h3 class="feature-title">Post Items</h3>
                    <p class="feature-description">
                        Quickly post details about lost or found items with photos and descriptions.
                        Our intuitive form makes it easy to provide all the necessary details.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="feature-title">Smart Search</h3>
                    <p class="feature-description">
                        Advanced search and filtering options help you find exactly what you're looking for.
                        Search by category, location, date, and more.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3 class="feature-title">Safe Reunions</h3>
                    <p class="feature-description">
                        Connect safely with item owners through our platform.
                        Built-in verification helps ensure secure and authentic reunions.
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
                        <i class="fas fa-search-minus"></i>
                    </div>
                    <div>
                        <div style="font-size: 1.1rem; font-weight: 700;">Lost Items</div>
                        <div style="font-size: 0.9rem; color: var(--text-secondary);">Items people are looking for</div>
                    </div>
                </a>
                <a href="found_show.php" class="browse-btn">
                    <div class="browse-icon">
                        <i class="fas fa-search-plus"></i>
                    </div>
                    <div>
                        <div style="font-size: 1.1rem; font-weight: 700;">Found Items</div>
                        <div style="font-size: 0.9rem; color: var(--text-secondary);">Items waiting for owners</div>
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

        // Intersection Observer for feature card fade-in animations
        const featureCards = document.querySelectorAll('.feature-card');

        const observerOptions = {
            threshold: 0.1, // Trigger when 10% of the element is visible
            rootMargin: '0px 0px -50px 0px' // Adjust to trigger slightly before element is fully in view
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Add the 'animate' class and set a delay based on the card's position
                    const delay = Array.from(featureCards).indexOf(entry.target) * 0.2; // 0.2s delay between cards
                    entry.target.style.animationDelay = `${delay}s`;
                    entry.target.classList.add('animate');
                    observer.unobserve(entry.target); // Stop observing once animated
                }
            });
        }, observerOptions);

        featureCards.forEach(card => {
            observer.observe(card);
        });

        // Mobile menu toggle (placeholder for future implementation)
        document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
            // In a real application, you'd toggle a class on the nav-menu to show/hide it
            const navMenu = document.querySelector('.nav-menu');
            navMenu.classList.toggle('mobile-active'); // Example class to show/hide
            console.log('Mobile menu clicked');
        });
    </script>
</body>
</html>