<?php
session_start();
include 'stdnav.php';
?>

<?php
require_once 'config.php';
$basePath = getBasePath();
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost & Found Hub - Ocean Blue Edition</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
            background: var(--bg-gradient);
            overflow-x: hidden;
            scroll-behavior: smooth;
            position: relative;
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
            /* Increased gap for navigation items */
            gap: 3.0rem; /* Increased from 0.5rem to 3.0rem for a wider, cleaner look */
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

        .hero {
            background: var(--gradient-hero);
            padding: 140px 0 100px;
            text-align: center;
            position: relative;
            overflow: hidden;
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 300" preserveAspectRatio="none"><defs><linearGradient id="wave" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" stop-color="rgba(255,255,255,0.1)" /><stop offset="50%" stop-color="rgba(255,255,255,0.2)" /><stop offset="100%" stop-color="rgba(255,255,255,0.1)" /></linearGradient></defs><path fill="url(%23wave)" d="M0,150 C250,50 750,250 1000,150 L1000,300 L0,300 Z" opacity="0.3" /><path fill="url(%23wave)" d="M0,200 C300,100 700,300 1000,200 L1000,300 L0,300 Z" opacity="0.2" /></svg>') repeat-x;
            background-size: 100% 100%;
            animation: waveFloat 8s ease-in-out infinite;
        }

        .hero::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes waveFloat {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-20px) scale(1.02); }
        }

        @keyframes pulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.3; }
            50% { transform: translate(-50%, -50%) scale(1.1); opacity: 0.1; }
        }

        .hero-container {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: clamp(2.5rem, 6vw, 5rem);
            font-weight: 900;
            color: white;
            margin-bottom: 2rem;
            text-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transform: translateY(50px);
            animation: heroFadeIn 1.2s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            animation-delay: 0.2s;
            letter-spacing: -0.02em;
        }

        .hero-subtitle {
            font-size: 1.4rem;
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 3rem;
            max-width: 750px;
            margin-left: auto;
            margin-right: auto;
            opacity: 0;
            transform: translateY(50px);
            animation: heroFadeIn 1.2s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            animation-delay: 0.4s;
            line-height: 1.7;
            font-weight: 400;
        }

        .hero-actions {
            display: flex;
            gap: 2rem;
            justify-content: center;
            flex-wrap: wrap;
            opacity: 0;
            transform: translateY(50px);
            animation: heroFadeIn 1.2s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            animation-delay: 0.6s;
        }

        @keyframes heroFadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.8rem;
            padding: 1.2rem 2.5rem;
            border-radius: 16px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            z-index: 1;
            font-family: inherit;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--shine-gradient);
            transition: left 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: -1;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: var(--shadow-2xl);
        }

        .btn:active {
            transform: translateY(-2px) scale(1.01);
            transition: all 0.1s ease;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            border: 2px solid transparent;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-light);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.95);
            color: var(--primary);
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
            background: white;
            border-color: var(--primary-light);
            color: var(--primary-dark);
        }

        .btn-lost {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border: 2px solid transparent;
        }

        .btn-lost:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            box-shadow: 0 20px 40px -10px rgba(239, 68, 68, 0.4);
        }

        .btn-found {
            background: var(--gradient-ocean);
            color: white;
            border: 2px solid transparent;
        }

        .btn-found:hover {
            background: var(--azure);
            box-shadow: 0 20px 40px -10px rgba(2, 132, 199, 0.4);
        }

        .features {
            padding: 120px 0;
            background: var(--bg-primary);
            position: relative;
        }

        .features::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--primary-pale), transparent);
        }

        .features-title {
            text-align: center;
            font-size: clamp(2.2rem, 5vw, 3.5rem);
            font-weight: 900;
            color: var(--text-primary);
            margin-bottom: 4rem;
            position: relative;
        }

        .features-title::after {
            content: '';
            position: absolute;
            bottom: -1rem;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--gradient-primary);
            border-radius: 2px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 3rem;
        }

        .feature-card {
            background: var(--gradient-card);
            border-radius: 24px;
            padding: 3rem;
            text-align: center;
            box-shadow: var(--shadow-md);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--border-blue);
            position: relative;
            overflow: hidden;
            opacity: 0;
            transform: translateY(50px);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-card.animate {
            animation: featureFadeIn 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        .feature-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: var(--shadow-2xl);
            border-color: var(--primary-light);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        @keyframes featureFadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 2rem;
            background: var(--gradient-primary);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            box-shadow: var(--shadow-blue);
            position: relative;
            overflow: hidden;
        }

        .feature-icon::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: var(--shine-gradient);
            transform: rotate(45deg);
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-card:hover .feature-icon::before {
            transform: translateX(100%) translateY(100%) rotate(45deg);
        }

        .feature-icon i {
            position: relative;
            z-index: 1;
        }

        .feature-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 1.2rem;
        }

        .feature-description {
            color: var(--text-secondary);
            line-height: 1.8;
            font-size: 1.05rem;
        }

        .browse {
            padding: 120px 0;
            background: var(--bg-ocean);
            position: relative;
        }

        .browse::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--wave-gradient);
            opacity: 0.5;
        }

        .browse-container {
            position: relative;
            z-index: 1;
        }

        .browse-title {
            font-size: clamp(2.2rem, 5vw, 3.5rem);
            font-weight: 900;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .browse-subtitle {
            font-size: 1.3rem;
            color: var(--text-secondary);
            margin-bottom: 4rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            line-height: 1.7;
        }

        .browse-actions {
            display: flex;
            gap: 3rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .browse-btn {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 2.5rem 3rem;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px;
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 600;
            box-shadow: var(--shadow-lg);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(20px);
            min-width: 320px;
        }

        .browse-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--shine-gradient);
            transition: left 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 0;
        }

        .browse-btn:hover::before {
            left: 100%;
        }

        .browse-btn:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: var(--shadow-2xl);
            border-color: var(--primary-light);
            background: white;
        }

        .browse-icon {
            width: 65px;
            height: 65px;
            background: var(--gradient-primary);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
            box-shadow: var(--shadow-blue);
            position: relative;
            z-index: 1;
        }

        .browse-text {
            position: relative;
            z-index: 1;
        }

        .browse-text .title {
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 0.3rem;
        }

        .browse-text .subtitle {
            font-size: 1rem;
            color: var(--text-light);
        }

        .footer {
            background: linear-gradient(135deg, var(--text-primary) 0%, #0f172a 100%);
            color: white;
            padding: 80px 0 40px;
            position: relative;
            overflow: hidden;
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
            margin-bottom: 4rem;
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
            padding-top: 2.5rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
            font-size: 1rem;
        }

        .disclaimer {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.2);
            border-radius: 16px;
            padding: 1.5rem;
            margin-top: 2rem;
            color: rgba(245, 158, 11, 0.9);
            font-size: 0.95rem;
            line-height: 1.6;
            backdrop-filter: blur(10px);
        }

        .back-to-top {
            position: fixed;
            bottom: 2.5rem;
            right: 2.5rem;
            background: var(--gradient-primary);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            box-shadow: var(--shadow-xl);
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 999;
            border: none;
        }

        .back-to-top.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .back-to-top:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: var(--shadow-2xl);
        }

        .back-to-top:active {
            transform: translateY(-2px) scale(0.98);
        }

        @media (max-width: 992px) {
            .nav-menu {
                display: none;
                flex-direction: column;
                position: absolute;
                top: 85px; /* Adjust based on navbar height */
                left: 0;
                width: 100%;
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(25px);
                border-bottom: 1px solid var(--border-blue);
                box-shadow: var(--shadow-md);
                padding: 1rem 0;
                transform: translateY(-100%);
                transition: transform 0.4s ease-out, opacity 0.4s ease-out;
                opacity: 0;
            }

            .nav-menu.active {
                display: flex;
                transform: translateY(0);
                opacity: 1;
            }

            .nav-menu li {
                width: 100%;
                text-align: center;
            }

            .nav-link {
                display: block;
                padding: 1rem 0;
                width: 100%;
            }

            .mobile-menu-btn {
                display: flex;
            }

            .hero {
                padding: 100px 0 80px;
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

    </style>
</head>
<body class="l">
    <header class="navbar" id="navbar">
        <div class="nav-container">
            <a href="<?php echo $basePath; ?>lost_index.php" class="logo">
                <span class="logo-icon"><i class="fas fa-search-location"></i></span>
                Lost & Found Hub
            </a>
            <nav>
                <ul class="nav-menu" id="navMenu">
                    <li><a href="<?php echo $basePath; ?>/lost_index.php" class="nav-link <?php echo ($currentPage == 'lost_index.php') ? 'active' : ''; ?>"><i class="fas fa-home"></i> Home</a></li>
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

    <main>
        <section class="hero">
            <div class="hero-container container">
                <h2 class="hero-title">Your Community Hub for Lost & Found</h2>
                <p class="hero-subtitle">
                    Reunite lost items with their rightful owners and help make your community a better place.
                    Quickly post what you've lost or found, and connect with others.
                </p>
                <div class="hero-actions">
                    <a href="<?php echo $basePath; ?>/lost_post.php" class="btn btn-lost">
                        <i class="fas fa-frown"></i> I Lost Something!
                    </a>
                    <a href="<?php echo $basePath; ?>/found_post.php" class="btn btn-found">
                        <i class="fas fa-smile"></i> I Found Something!
                    </a>
                </div>
            </div>
        </section>

        <section class="features">
            <div class="container">
                <h2 class="features-title">How It Works</h2>
                <div class="features-grid">
                    <div class="feature-card" data-animation-delay="0s">
                        <div class="feature-icon"><i class="fas fa-bullhorn"></i></div>
                        <h3 class="feature-title">Report Lost Item</h3>
                        <p class="feature-description">
                            Quickly post details about your lost item, including description, location, and contact information.
                        </p>
                    </div>
                    <div class="feature-card" data-animation-delay="0.1s">
                        <div class="feature-icon"><i class="fas fa-hand-sparkles"></i></div>
                        <h3 class="feature-title">Report Found Item</h3>
                        <p class="feature-description">
                            Help someone out! Post what you've found and provide details to facilitate its return.
                        </p>
                    </div>
                    <div class="feature-card" data-animation-delay="0.2s">
                        <div class="feature-icon"><i class="fas fa-search"></i></div>
                        <h3 class="feature-title">Browse Listings</h3>
                        <p class="feature-description">
                            Easily search through lost and found listings to find a match for your item.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="browse">
            <div class="container browse-container">
                <h2 class="browse-title">Ready to Connect?</h2>
                <p class="browse-subtitle">
                    Whether you've lost something precious or found an item looking for its owner, our platform simplifies the process of connection.
                </p>
                <div class="browse-actions">
                    <a href="<?php echo $basePath; ?>/lost_show.php" class="browse-btn">
                        <span class="browse-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);"><i class="fas fa-boxes"></i></span>
                        <span class="browse-text">
                            <span class="title">View Lost Items</span>
                            <span class="subtitle">See what others have reported missing.</span>
                        </span>
                    </a>
                    <a href="<?php echo $basePath; ?>/found_show.php" class="browse-btn">
                        <span class="browse-icon" style="background: var(--gradient-ocean);"><i class="fas fa-box-open"></i></span>
                        <span class="browse-text">
                            <span class="title">View Found Items</span>
                            <span class="subtitle">Discover items waiting to be claimed.</span>
                        </span>
                    </a>
                </div>
                <div class="disclaimer">
                    <strong>Important:</strong> We recommend exercising caution when arranging meetups and verifying identities. This platform is for connection purposes; direct exchanges should be handled with personal safety in mind.
                </div>
            </div>
        </section>
    </main>

    <button class="back-to-top" id="backToTopBtn" aria-label="Back to top">
        <i class="fas fa-arrow-up"></i>
    </button>

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

        // Close mobile menu when a link is clicked
        navMenu.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
                mobileMenuBtn.classList.remove('active');
            });
        });

        // Feature card animation on scroll
        const featureCards = document.querySelectorAll('.feature-card');
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.2
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const delay = parseFloat(entry.target.dataset.animationDelay);
                    entry.target.style.animationDelay = delay + 's';
                    entry.target.classList.add('animate');
                    observer.unobserve(entry.target); // Stop observing once animated
                }
            });
        }, observerOptions);

        featureCards.forEach(card => {
            observer.observe(card);
        });

        // Back to top button functionality
        const backToTopBtn = document.getElementById('backToTopBtn');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTopBtn.classList.add('show');
            } else {
                backToTopBtn.classList.remove('show');
            }
        });

        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
</body>
</html>