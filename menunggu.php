<?php
session_start();

// Check if the user session exists; if not, redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to login if no user session
    exit();
}

// Get the user ID from the session
$userId = $_SESSION['user_id'];

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'isp');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch user status from the database (this check is only for the initial page load)
$sql = "SELECT status FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($userStatus);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Failed to prepare statement: " . $conn->error);
}

// Close the database connection
$conn->close();

// Check if the user status is active (status = 1)
if ($userStatus == 1) {
    header("Location: dashboard/index.php"); // Redirect to dashboard if active
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Waiting for Admin Activation</title>
  <style>
    body, html {
      margin: 0;
      padding: 0;
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #f3f3f3;
      font-family: Arial, sans-serif;
    }

    .loading-container {
      text-align: center;
    }

    .loading-spinner {
      width: 50px;
      height: 50px;
      border: 5px solid #ddd;
      border-top: 5px solid #3498db;
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin: 0 auto;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(360deg);
      }
    }

    .loading-text {
      margin-top: 20px;
      font-size: 18px;
      color: #555;
    }

    .loading-text span {
      display: inline-block;
      animation: blink 1.5s infinite;
    }

    .loading-text span:nth-child(2) {
      animation-delay: 0.5s;
    }

    .loading-text span:nth-child(3) {
      animation-delay: 1s;
    }

    @keyframes blink {
      0%, 100% {
        opacity: 0;
      }
      50% {
        opacity: 1;
      }
    }
  </style>
</head>
<body>
  <div class="loading-container">
    <div class="loading-spinner"></div>
    <div class="loading-text">
      Waiting for Admin to Activate<span>.</span><span>.</span><span>.</span>
    </div>
  </div>

  <script>
      // Function to check user status every 5 seconds
      function checkUserStatus() {
            fetch('check_status.php')
                .then(response => response.json())
                .then(data => {
                    console.log("Server response:", data); // Debugging line to check response
                    if (data.status === 'active') {
                        window.location.href = 'dashboard/index.php'; // Redirect to dashboard if status is active
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Poll the server every 5 seconds to check the user's status
        setInterval(checkUserStatus, 5000);
  </script>
</body>
</html>
