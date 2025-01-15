<?php
session_start();

// Aktifkan error logging untuk debugging
ini_set('log_errors', 1);
ini_set('error_log', 'php-error.log'); 
error_reporting(E_ALL);

$servername = "localhost";
$db_user = "root";
$db_passw = "";
$dbname = "isp";

$conn = new mysqli($servername, $db_user, $db_passw, $dbname);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $response = ['status' => 'error', 'message' => ''];

    if (isset($_POST['username'], $_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            $response['message'] = "Silahkan masukkan username dan juga password.";
        } else {
            $sql1 = "SELECT * FROM users WHERE BINARY username = ?";
            $stmt = $conn->prepare($sql1);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $r1 = $result->fetch_assoc();

            if ($r1 === null) {
                $response['message'] = "Username salah.";
            } elseif ($r1['status'] != 1) { // Cek apakah akun aktif
                $response['message'] = "Akun belum aktif.";
            } elseif (!password_verify($password, $r1['password_hash'])) {
                // Jika hash lama dengan md5()
                if ($r1['password_hash'] === md5($password)) {
                    // Migrasi ke password_hash()
                    $new_hash = password_hash($password, PASSWORD_BCRYPT);
                    $update_sql = "UPDATE users SET password_hash = ? WHERE id = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("si", $new_hash, $r1['id']);
                    $update_stmt->execute();

                    $_SESSION['session_username'] = $username;
                    $response['status'] = 'success';
                    $response['message'] = "Password telah diperbarui dan login berhasil!";
                    $response['redirect'] = 'dashboard/';
                } else {
                    $response['message'] = "Password salah.";
                }
            } else {
                $_SESSION['session_username'] = $username;
                $_SESSION['session_password'] = $r1['password_hash'];
                $response['status'] = 'success';
                $response['message'] = "Kamu berhasil login!";
                $response['redirect'] = 'dashboard/'; // Redirect ke dashboard
            }
        }
    }

    echo json_encode($response);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css"> 
</head>
<body>
<style>
.input-group input {
    width: calc(100% - 24px); /* Mengurangi total padding dari lebar input */
    padding: 12px;
    border: 1px solid #007bff;
    border-radius: 6px;
    transition: border-color 0.3s;
    margin: 0 auto; /* Untuk meratakan text box di tengah */
}

/* Animasi flip */
@keyframes flip {
    from {
        transform: rotateY(0);
    }
    to {
        transform: rotateY(180deg);
    }
}

/* Menambahkan animasi flip pada container */
.container.flip {
    animation: flip 1s ease-in-out forwards;
}
</style>

    <div class="background">
        <img src="asset/Untitled design.png" alt="">
    </div>
    <div class="container">
    <h1>Login</h1>
    <!-- Isi Form Register -->
</div>
    <div class="login-container container"> <!-- Tambahkan class container jika perlu -->
        <h1>Login</h1>
        <form id="loginForm" method="post">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <p id="error-message" class="error-message"></p>
            <button type="submit">Login</button>
        </form>
        <p>Belum punya akun? <a href="register.php">Register</a></p>
        <p id="success-message" class="success-message"></p>
    </div>
    </div>
    </div>

    <script>
    document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(event.target);

    fetch('index.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Hapus pesan error dan tampilkan pesan sukses
            document.getElementById('error-message').textContent = '';
            document.getElementById('success-message').textContent = data.message;
            // Redirect ke dashboard setelah beberapa saat
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 2000);
        } else {
            // Tampilkan pesan error
            document.getElementById('error-message').textContent = data.message;
            // Kosongkan pesan sukses
            document.getElementById('success-message').textContent = '';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('error-message').textContent = 'Terjadi kesalahan';
    });
});
    </script>
</body>
</html>
