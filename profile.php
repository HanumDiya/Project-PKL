<?php
// Include file functions.php for login session functionality
require 'functions.php';
check_login(); // Ensure the user is logged in

// Database configuration
$servername = "localhost";
$db_user = "root";
$db_passw = "";
$dbname = "isp";

// Create a connection
$conn = new mysqli($servername, $db_user, $db_passw, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Inisialisasi variabel error dan sukses
$errorMessage = '';
$successMessage = '';
$username = $_SESSION['session_username']; // Username diambil dari sesi

// Fetch current password hash from the database
$query = $conn->prepare("SELECT password_hash FROM users WHERE username = ?");
$query->bind_param('s', $username);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

// If user exists, decrypt or display their hashed password for verification
if ($user) {
    $currentPasswordHash = $user['password_hash'];
} else {
    die("User not found");
}

// Proses formulir jika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPasswordInput = $_POST['current-password'];
    $newPassword = $_POST['new-password'];
    $confirmNewPassword = $_POST['confirm-new-password'];

    // Validate current password
    if (!password_verify($currentPasswordInput, $currentPasswordHash)) {
        $errorMessage = 'Password saat ini tidak sesuai.';
    } elseif (strlen($newPassword) < 6) {
        $errorMessage = 'Password baru harus memiliki setidaknya 6 karakter.';
    } elseif ($newPassword !== $confirmNewPassword) {
        $errorMessage = 'Konfirmasi password baru tidak cocok.';
    } else {
        // Hash the new password and save it
        $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $updateQuery = $conn->prepare("UPDATE users SET password_hash = ?, updated_at = NOW() WHERE username = ?");
        $updateQuery->bind_param('ss', $newPasswordHash, $username);

        if ($updateQuery->execute()) {
            $successMessage = 'Password berhasil diperbarui.';
            echo "<script>
                    alert('Password berhasil diperbarui!');
                    window.location.href = 'dashboard/';
                  </script>";
            exit();
        } else {
            $errorMessage = 'Terjadi kesalahan saat memperbarui password. Silakan coba lagi.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Satrianet | Profile</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="css/profile.css">
  <style>
    .form-group {
      position: relative;
      margin-bottom: 20px;
    }
    .form-group input {
      width: 100%;
      padding: 10px 40px 10px 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
    }
    .form-group .eye-icon {
      position: absolute;
      top: 72%;
      right: 10px;
      transform: translateY(-50%);
      cursor: pointer;
      color: #666;
      font-size: 16px;
    }
    .error-message {
      color: red;
      font-size: 14px;
      margin-bottom: 10px;
    }
    .success-message {
      color: green;
      font-size: 14px;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
<section class="sidebar">
    <a href="/dashboard/index.php" class="logo">
      <img src="asset/logo.png" alt="Satria Net Logo" class="logo-img">
      <span class="text">SATRIA NET</span>
    </a>
    
    <ul class="side-menu top">
      <li>
        <a href="./dashboard/index.php" class="nav-link">
          <i class="fas fa-border-all"></i>
          <span class="text">Dashboard</span>
        </a>
      </li>
      <li>
        <a href="dpelanggan.php" class="nav-link">
          <i class="fas fa-people-group"></i>
          <span class="text">Data Pelanggan</span>
        </a>
      </li>
      <li>
        <a href="datateknis.php" class="nav-link">
          <i class="fas fa-cog"></i>
          <span class="text">Data Teknis</span>
        </a>
      </li>
      <li>
        <a href="pembayaran.html" class="nav-link">
          <i class="fas fa-money-bill"></i>
          <span class="text">Pembayaran</span>
        </a>
      </li>
      <li>
        <a href="inventori.php" class="nav-link">
          <i class="fas fa-box"></i>
          <span class="text">Inventori</span>
        </a>
      </li>
      <li>
        <a href="ticketing.html" class="nav-link">
          <i class="fas fa-ticket-alt"></i>
          <span class="text">Ticketing</span>
        </a>
      </li>
      <li>
        <a href="prt.html" class="nav-link">
          <i class="fas fa-tools"></i>
          <span class="text">PRT</span>
        </a>
      </li>
    </ul>
    
    <ul class="side-menu">
      <li>
        <a href="logout.php" class="logout">
          <i class="fas fa-right-from-bracket"></i>
          <span class="text">Logout</span>
        </a>
      </li>
    </ul>
</section>
<section class="content">
    <nav>
      <i class="fas fa-bars menu-btn"></i>
      <a href="#" class="nav-link">Categories</a>
      <form action="#" method="GET">
        <div class="form-input">
          <input type="search" placeholder="search..." name="query">
          <button class="search-btn">
            <i class="fas fa-search search-icon"></i>
          </button>
        </div>
      </form>
      <a href="profile.php" class="profile">
    <i class="fas fa-user"></i>
      </a>
    </nav>
    <main>
    <div class="profile-container">
    <h2>Change Password</h2>
    <?php if ($errorMessage): ?>
        <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>
    <?php if ($successMessage): ?>
        <div class="success-message"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>
    <form action="profile.php" method="POST">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="current-password">Current Password:</label>
            <input type="password" id="current-password" name="current-password" placeholder="Enter current password" required>
            <i class="fas fa-eye eye-icon" onclick="togglePassword('current-password')"></i>
        </div>
        <div class="form-group">
            <label for="new-password">New Password:</label>
            <input type="password" id="new-password" name="new-password" placeholder="Enter new password" required>
            <i class="fas fa-eye eye-icon" onclick="togglePassword('new-password')"></i>
        </div>
        <div class="form-group">
            <label for="confirm-new-password">Confirm New Password:</label>
            <input type="password" id="confirm-new-password" name="confirm-new-password" placeholder="Confirm new password" required>
            <i class="fas fa-eye eye-icon" onclick="togglePassword('confirm-new-password')"></i>
        </div>
        <button type="submit" class="btn-save">Save changes</button>
    </form>
</div>
    </main>
</section>
<script>
  function togglePassword(fieldId) {
    const inputField = document.getElementById(fieldId);
    const eyeIcon = inputField.nextElementSibling;

    if (inputField.type === "password") {
        inputField.type = "text";
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
    } else {
        inputField.type = "password";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
    }
}
</script>
</body>
</html>
