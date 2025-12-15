<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Horizon Authentication</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif']
                    },
                    colors: {
                        'deep-blue': '#1e3a8a',
                        'ocean-blue': '#3b82f6',
                        'sky-blue': '#93c5fd',
                        'light-blue': '#bfdbfe'
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'glow-pulse': 'glowPulse 3s ease-in-out infinite',
                        'slide-in-left': 'slideInLeft 0.8s ease-out',
                        'slide-in-right': 'slideInRight 0.8s ease-out',
                        'fade-in-up': 'fadeInUp 0.6s ease-out'
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) }
            50% { transform: translateY(-10px) }
        }
        
        @keyframes glowPulse {
            0%, 100% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.3) }
            50% { box-shadow: 0 0 30px rgba(59, 130, 246, 0.6) }
        }
        
        @keyframes slideInLeft {
            from { transform: translateX(-80px); opacity: 0 }
            to { transform: translateX(0); opacity: 1 }
        }
        
        @keyframes slideInRight {
            from { transform: translateX(80px); opacity: 0 }
            to { transform: translateX(0); opacity: 1 }
        }
        
        @keyframes fadeInUp {
            from { transform: translateY(20px); opacity: 0 }
            to { transform: translateY(0); opacity: 1 }
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #93c5fd 100%);
            background-size: 200% 200%;
            animation: gradientShift 12s ease infinite;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50% }
            50% { background-position: 100% 50% }
            100% { background-position: 0% 50% }
        }
        
        .glass-morphism {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
        
        .input-blue {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 10px;
            box-shadow: inset 4px 4px 8px rgba(0, 0, 0, 0.05), 
                        inset -4px -4px 8px rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            padding: 14px 16px;
        }
        
        .input-blue:focus {
            box-shadow: inset 4px 4px 8px rgba(59, 130, 246, 0.1), 
                        inset -4px -4px 8px rgba(255, 255, 255, 0.95);
            outline: none;
            background: #fff;
        }
        
        .btn-blue {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-blue:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }
        
        .floating-label {
            position: relative;
            margin-bottom: 20px;
        }
        
        .floating-label-text {
            position: absolute;
            top: 50%;
            left: 16px;
            transform: translateY(-50%);
            padding: 0 6px;
            color: #6b7280;
            font-size: 14px;
            transition: all 0.3s ease;
            pointer-events: none;
            background: transparent;
        }
        
        .input-blue:focus + .floating-label-text,
        .input-blue:not(:placeholder-shown) + .floating-label-text {
            top: 0;
            font-size: 11px;
            color: #3b82f6;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 6px;
        }
        
        .success-message {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 14px;
        }
        
        .error-message {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 14px;
        }
        
        .password-strength {
            height: 3px;
            border-radius: 2px;
            background: #e5e7eb;
            margin-top: 6px;
        }
        
        .password-strength-bar {
            height: 100%;
            transition: all 0.3s ease;
        }
        
        .strength-weak { background: #ef4444; width: 25% }
        .strength-fair { background: #f59e0b; width: 50% }
        .strength-good { background: #3b82f6; width: 75% }
        .strength-strong { background: #10b981; width: 100% }
        
        .form-container {
            perspective: 1000px;
            min-height: 500px;
        }
        
        .form-card {
            transform-style: preserve-3d;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .form-card.flipped {
            transform: rotateY(180deg);
        }
        
        .card-face {
            position: absolute;
            width: 100%;
            backface-visibility: hidden;
            padding: 30px;
        }
        
        .card-back {
            transform: rotateY(180deg);
        }
        
        .select-arrow {
            pointer-events: none;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="fixed top-8 left-8 w-24 h-24 bg-blue-400/20 rounded-full blur-xl animate-float"></div>
    <div class="fixed bottom-16 right-12 w-20 h-20 bg-blue-500/20 rounded-full blur-lg animate-float" style="animation-delay: -2s"></div>
    
    <div class="flex flex-col lg:flex-row w-full max-w-4xl glass-morphism rounded-2xl shadow-xl overflow-hidden animate-glow-pulse">
        <!-- Left Side - Branding -->
        <div class="w-full lg:w-2/5 bg-gradient-to-br from-deep-blue to-ocean-blue text-white p-8 flex flex-col justify-center animate-slide-in-left">
            <div class="text-center lg:text-left">
                <div class="flex justify-center lg:justify-start mb-6">
                    <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-lock text-2xl text-white"></i>
                    </div>
                </div>
                <h1 class="text-3xl lg:text-4xl font-bold mb-4 animate-fade-in-up">
                    Secure <span class="text-sky-blue">Access</span>
                </h1>
                <p class="text-lg mb-6 text-white/90 animate-fade-in-up" style="animation-delay: .2s">
                    Premium authentication with military-grade security
                </p>
                <div class="flex space-x-4 justify-center lg:justify-start animate-fade-in-up" style="animation-delay: .4s">
                    <div class="flex items-center">
                        <i class="fas fa-shield-alt text-sm text-sky-blue mr-2"></i>
                        <span class="text-sm">256-bit Encryption</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-fingerprint text-sm text-sky-blue mr-2"></i>
                        <span class="text-sm">Biometric Ready</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Forms -->
        <div class="w-full lg:w-3/5 p-6 lg:p-8 bg-white/70 animate-slide-in-right">
            <div class="form-container">
                <div class="form-card" id="form-card">
                    <!-- Login Form -->
                    <div class="card-face card-front">
                        <div class="text-center mb-6">
                            <h2 class="text-3xl font-bold text-deep-blue">Welcome Back</h2>
                            <p class="text-gray-600">Sign in to your account</p>
                        </div>
                        <form id="login-form" class="space-y-4">
                            <div class="floating-label">
                                <input type="email" id="login-email" class="w-full input-blue" placeholder=" " required>
                                <label for="login-email" class="floating-label-text">
                                    <i class="fas fa-envelope text-xs mr-1"></i> Email
                                </label>
                            </div>
                            <div class="floating-label">
                                <input type="password" id="login-password" class="w-full input-blue" placeholder=" " required>
                                <label for="login-password" class="floating-label-text">
                                    <i class="fas fa-key text-xs mr-1"></i> Password
                                </label>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <input type="checkbox" id="remember-me" class="rounded text-ocean-blue focus:ring-ocean-blue">
                                    <label for="remember-me" class="ml-2 text-sm text-gray-600">Remember me</label>
                                </div>
                                <a href="#" class="text-sm text-ocean-blue hover:underline">Forgot password?</a>
                            </div>
                            <button type="submit" class="w-full py-3 btn-blue text-white font-semibold">
                                <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                            </button>
                        </form>
                        <div id="login-message" class="mt-3 text-center hidden"></div>
                        <div class="mt-6 text-center">
                            <span class="text-gray-600 text-sm">Don't have an account? </span>
                            <button id="show-register" class="text-ocean-blue hover:text-deep-blue font-semibold text-sm">
                                Sign up now
                            </button>
                        </div>
                    </div>
                    
                    <!-- Register Form -->
                    <div class="card-face card-back">
                        <div class="text-center mb-6">
                            <h2 class="text-3xl font-bold text-deep-blue">Create Account</h2>
                            <p class="text-gray-600">Join our secure platform</p>
                        </div>
                        <form id="register-form" class="space-y-3">
                            <div class="floating-label">
                                <input type="email" id="register-email" class="w-full input-blue" placeholder=" " required>
                                <label for="register-email" class="floating-label-text">
                                    <i class="fas fa-envelope text-xs mr-1"></i> Email
                                </label>
                            </div>
                            <div class="floating-label">
                                <input type="password" id="register-password" class="w-full input-blue" placeholder=" " required>
                                <label for="register-password" class="floating-label-text">
                                    <i class="fas fa-lock text-xs mr-1"></i> Password
                                </label>
                                <div class="password-strength">
                                    <div class="password-strength-bar" id="password-strength-bar"></div>
                                </div>
                            </div>
                            <div class="floating-label">
                                <input type="password" id="register-confirm-password" class="w-full input-blue" placeholder=" " required>
                                <label for="register-confirm-password" class="floating-label-text">
                                    <i class="fas fa-check-circle text-xs mr-1"></i> Confirm Password
                                </label>
                            </div>
                            <div class="floating-label">
                                <select id="register-role" class="w-full input-blue appearance-none" required>
                                    <option value="" disabled selected></option>
                                    <option value="student">Student</option>
                                    <option value="admin">Administrator</option>
                                    <option value="premium">Premium Member</option>
                                </select>
                                <label for="register-role" class="floating-label-text">
                                    <i class="fas fa-user-tag text-xs mr-1"></i> Select Role
                                </label>
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 select-arrow">
                                    <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                                </div>
                            </div>
                            <button type="submit" class="w-full py-3 btn-blue text-white font-semibold">
                                <i class="fas fa-user-plus mr-2"></i> Create Account
                            </button>
                        </form>
                        <div id="register-message" class="mt-3 text-center hidden"></div>
                        <div class="mt-6 text-center">
                            <span class="text-gray-600 text-sm">Already have an account? </span>
                            <button id="show-login" class="text-ocean-blue hover:text-deep-blue font-semibold text-sm">
                                Sign in here
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // DOM Elements
        const formCard = document.getElementById('form-card');
        const showLoginBtn = document.getElementById('show-login');
        const showRegisterBtn = document.getElementById('show-register');
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        const loginMessage = document.getElementById('login-message');
        const registerMessage = document.getElementById('register-message');
        const registerPasswordInput = document.getElementById('register-password');
        const strengthBar = document.getElementById('password-strength-bar');
        
        // Constants
        const STORAGE_KEY = 'blue_horizon_user_data';
        
        // User data functions
        const loadUsers = () => {
            const users = localStorage.getItem(STORAGE_KEY);
            return users ? JSON.parse(users) : {};
        };
        
        const saveUsers = (users) => {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(users));
        };
        
        // Form toggle
        showRegisterBtn.addEventListener('click', () => {
            formCard.classList.add('flipped');
            registerMessage.classList.add('hidden');
        });
        
        showLoginBtn.addEventListener('click', () => {
            formCard.classList.remove('flipped');
            loginMessage.classList.add('hidden');
        });
        
        // Message display
        const showMessage = (element, message, isSuccess) => {
            element.textContent = message;
            element.classList.remove('hidden');
            element.className = 'mt-3 text-center ' + (isSuccess ? 'success-message' : 'error-message');
        };
        
        // Password strength meter
        registerPasswordInput.addEventListener('input', () => {
            const password = registerPasswordInput.value;
            let score = 0;
            
            // Criteria checks
            if (password.length >= 8) score++;
            if (/[a-z]/.test(password)) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[^A-Za-z0-9]/.test(password)) score++;
            
            // Update strength bar
            strengthBar.className = 'password-strength-bar';
            
            switch(score) {
                case 1:
                    strengthBar.classList.add('strength-weak');
                    break;
                case 2:
                    strengthBar.classList.add('strength-fair');
                    break;
                case 3:
                    strengthBar.classList.add('strength-good');
                    break;
                case 4:
                case 5:
                    strengthBar.classList.add('strength-strong');
                    break;
            }
        });
        
        // Register form submission
        registerForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Get form values
            const email = document.getElementById('register-email').value.trim();
            const password = document.getElementById('register-password').value;
            const confirm = document.getElementById('register-confirm-password').value;
            const role = document.getElementById('register-role').value;
            
            // Load existing users
            const users = loadUsers();
            
            // Validation
            if (password !== confirm) {
                showMessage(registerMessage, 'Passwords do not match.', false);
                return;
            }
            
            if (users[email]) {
                showMessage(registerMessage, 'An account with this email already exists.', false);
                return;
            }
            
            if (!role) {
                showMessage(registerMessage, 'Please select a role.', false);
                return;
            }
            
            // Save new user
            users[email] = { password, role };
            saveUsers(users);
            
            // Show success message
            showMessage(registerMessage, 'Registration successful! Redirecting to login...', true);
            
            // Reset form and flip back to login
            registerForm.reset();
            strengthBar.className = 'password-strength-bar';
            
            setTimeout(() => {
                formCard.classList.remove('flipped');
                showMessage(loginMessage, 'Please sign in with your new account.', true);
            }, 2000);
        });
        
        // Login form submission
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Get form values
            const email = document.getElementById('login-email').value.trim();
            const password = document.getElementById('login-password').value;
            
            // Load users
            const users = loadUsers();
            const user = users[email];
            
            // Validation
            if (!user || user.password !== password) {
                showMessage(loginMessage, 'Invalid email or password.', false);
                return;
            }
            
            // Show success message
            showMessage(loginMessage, `Login successful! Welcome, ${user.role}.`, true);
            
            // Reset form and simulate redirect
            loginForm.reset();
            
            setTimeout(() => {
                alert(`Redirecting to ${user.role} dashboard...`);
                // In a real app, you would redirect here
                // window.location.href = `${user.role}-dashboard.html`;
            }, 1500);
        });
    </script>
</body>
</html>