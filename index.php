<?php
// Start the session to track the user's login status
session_start();

// Redirect to login page if the user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

// --- Database Configuration ---
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'lost_and_found_db');

// Attempt to connect to the database
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Fetch user data from the database
$user_data = [];
$sql = "SELECT id, email, role FROM users WHERE id = ?";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $param_id);
    $param_id = $_SESSION['user_id'];

    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) == 1) {
            $user_data = mysqli_fetch_assoc($result);
        }
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard</title>
  <!-- Tailwind CSS CDN for styling -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Google Font for a modern look -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: #f0f4f8;
    }
    .card {
      background: white;
      border-radius: 1.5rem;
      box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }
    .sidebar {
      background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
      color: white;
    }
  </style>
</head>
<body class="flex h-screen">

  <!-- Sidebar -->
  <aside class="sidebar w-64 p-6 flex flex-col items-center justify-between">
    <div class="text-center">
      <div class="text-4xl font-extrabold mb-4">LF</div>
      <p class="text-sm opacity-80">Lost & Found</p>
    </div>
    <nav class="space-y-4">
      <a href="#" class="block p-3 rounded-lg hover:bg-white/20 transition-colors">
        <span class="flex items-center space-x-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2.5a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0V3a.5.5 0 0 1 .5-.5ZM13.5 13a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 1 0v-1a.5.5 0 0 0-.5-.5ZM8.5 13a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 1 0v-1a.5.5 0 0 0-.5-.5ZM12 18.5a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0v-1a.5.5 0 0 1 .5-.5ZM12 8.5a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0v-1a.5.5 0 0 1 .5-.5ZM17.5 13a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 1 0v-1a.5.5 0 0 0-.5-.5ZM6 8.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 1 0v-1a.5.5 0 0 0-.5-.5ZM18 8.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 1 0v-1a.5.5 0 0 0-.5-.5Z"/>
            <path fill-rule="evenodd" d="M1.983 23.23a.5.5 0 0 0 .584.45L12 21.23l9.433 2.45a.5.5 0 0 0 .584-.45l.983-16.147a.5.5 0 0 0-.323-.526L12 .77L1.323 6.557a.5.5 0 0 0-.323.526l.983 16.147Z"/>
          </svg>
          <span>Dashboard</span>
        </span>
      </a>
      <a href="#" class="block p-3 rounded-lg hover:bg-white/20 transition-colors">
        <span class="flex items-center space-x-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M4 4v.001C4 2.896 4.896 2 6 2h12c1.104 0 2 .896 2 2v16c0 1.104-.896 2-2 2H6c-1.104 0-2-.896-2-2V4Zm2 0v16h12V4H6Z"/><path d="M9 13.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3ZM15 13.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3ZM9 19a1 1 0 1 1 0-2 1 1 0 0 1 0 2ZM15 19a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/></svg>
          <span>My Items</span>
        </span>
      </a>
      <a href="#" class="block p-3 rounded-lg hover:bg-white/20 transition-colors">
        <span class="flex items-center space-x-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M17 19.5H7a4.5 4.5 0 0 1 0-9h1.5a.5.5 0 0 0 0-1H7A5.5 5.5 0 0 0 7 21h10a5.5 5.5 0 0 0 0-11h-1.5a.5.5 0 0 0 0 1H17a4.5 4.5 0 0 1 0 9Z"/><path d="M11 11.5a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-2Z"/></svg>
          <span>Search</span>
        </span>
      </a>
    </nav>
    <button id="logout-btn" class="w-full mt-auto p-3 rounded-lg bg-white/20 hover:bg-white/30 transition-colors flex items-center justify-center space-x-2">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M14 6h-2a1 1 0 0 0-1 1v2a1 1 0 0 0 2 0V7h1a1 1 0 0 0 0-2Z"/><path d="M17 21a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v2a1 1 0 0 0 2 0v-1h1a1 1 0 0 0 0-2Z"/><path d="M16 11h-2a1 1 0 0 0-1 1v2a1 1 0 0 0 2 0v-1h1a1 1 0 0 0 0-2Z"/><path fill-rule="evenodd" d="M14 2h-4a2 2 0 0 0-2 2v2h2V4h4v2h2V4a2 2 0 0 0-2-2Zm-7 7a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1H7Zm10 0a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1h-2ZM6 18a2 2 0 0 0-2 2v2h2v-2h2v-2H6Zm8 2a2 2 0 0 0-2 2v2h2v-2h2v-2h-2Zm-8-9h-2v-2h2v2Zm-2-2V7h2V5h-2V4h4V2h-4a2 2 0 0 0-2 2v3H2v2h2v2H2v2h2v2H2v2h2v2H2v2h4a2 2 0 0 0 2-2v-3h2v-2H8v-2h2V9H8V7h2V5H8V4h-2Zm10-2h4v2h-4V2Zm-2 9v2h2v-2h-2Zm-2-2V7h2V5h-2V4h2v2h2V4h-2a2 2 0 0 0-2-2h-2V0h2v2h2V0h-2V2Z"/></svg>
      <span>Logout</span>
    </button>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 p-8 overflow-y-auto">
    <header class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
      <div class="flex items-center space-x-4">
        <span class="text-gray-600 font-medium">Hello, <span id="user-email-display"></span>!</span>
        <div class="h-10 w-10 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold">
          <span id="user-initials"></span>
        </div>
      </div>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="card p-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Your Recent Activity</h2>
        <p class="text-gray-500">No recent activity to show yet. Start by reporting a lost item or helping others!</p>
      </div>
      <div class="card p-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Found Items</h2>
        <p class="text-gray-500">Browse through items that have been found by other users.</p>
      </div>
      <div class="card p-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Report an Item</h2>
        <p class="text-gray-500">Quickly report a lost item to get the word out to the community.</p>
      </div>
    </div>
  </main>

  <script>
    // This is the user data fetched by PHP and injected into a JavaScript variable
    const userData = <?php echo json_encode($user_data); ?>;

    function handleLogout() {
      // Clear the session from sessionStorage
      sessionStorage.removeItem('lf_session');
      // Redirect to the login page
      window.location.href = 'index.html';
    }

    document.getElementById('logout-btn').addEventListener('click', handleLogout);

    // Display user data on the page
    document.addEventListener('DOMContentLoaded', () => {
      if (userData && userData.email) {
        document.getElementById('user-email-display').textContent = userData.email;
        document.getElementById('user-initials').textContent = userData.email.charAt(0).toUpperCase();
      } else {
        // If data is not available, redirect to login
        handleLogout();
      }
    });
  </script>
</body>
</html>
