<?php
session_start();

// Aktifkan error logging untuk debugging
ini_set('log_errors', 1);
ini_set('error_log', 'php-error.log'); 
error_reporting(E_ALL);

$response = ['status' => 'error', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username'], $_POST['password'], $_POST['password_confirm'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $password_confirm = trim($_POST['password_confirm']);

        if (empty($username) || empty($password) || empty($password_confirm)) {
            $response['message'] = "Semua field harus diisi.";
        } elseif ($password !== $password_confirm) {
            $response['message'] = "Password dan Konfirmasi Password tidak cocok.";
        } else {
            try {
                // Koneksi ke database
                $conn = new mysqli('localhost', 'root', '', 'isp');
                if ($conn->connect_error) {
                    throw new Exception("Koneksi database gagal: " . $conn->connect_error);
                }

                // Cek apakah username sudah ada
                $sql1 = "SELECT * FROM users WHERE BINARY username = ?";
                $stmt = $conn->prepare($sql1);
                if (!$stmt) {
                    throw new Exception("Error pada query SELECT: " . $conn->error);
                }
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $response['message'] = "Username sudah digunakan.";
                } else {
                    // Masukkan user baru ke database
                    $password_hash = password_hash($password, PASSWORD_BCRYPT); // Hash password
                    $sql2 = "INSERT INTO users (username, password_hash, status, role_id) VALUES (?, ?, 0, NULL)";
                    $stmt = $conn->prepare($sql2);
                    if (!$stmt) {
                        throw new Exception("Error pada query INSERT: " . $conn->error);
                    }
                    $stmt->bind_param("ss", $username, $password_hash);
                    if ($stmt->execute()) {
                        $_SESSION['user_id'] = $conn->insert_id;
                        $response['status'] = 'success';
                        $response['message'] = "Akun berhasil dibuat, silakan menunggu aktivasi oleh admin.";
                        $response['redirect'] = 'menunggu.php';
                    } else {
                        throw new Exception("Gagal membuat akun: " . $stmt->error);
                    }
                }
            } catch (Exception $e) {
                $response['message'] = $e->getMessage();
            }
        }
    } else {
        $response['message'] = "Data tidak lengkap.";
    }

    // Kirim JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/register.css"> 
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
    <h1>Register</h1>
    <!-- Isi Form Register -->
</div>
    <div class="register-container container"> <!-- Tambahkan class container jika perlu -->
        <h1>Register</h1>
        <form id="registerForm" method="post">
            <div class="input-group">
                <label for="reg-username">Username</label>
                <input type="text" id="reg-username" name="username" required>
            </div>
            <div class="input-group">
                <label for="reg-password">Password</label>
                <input type="password" id="reg-password" name="password" required>
            </div>
            <div class="input-group">
                <label for="reg-password-confirm">Confirm Password</label>
                <input type="password" id="reg-password-confirm" name="password_confirm" required>
            </div>
            <p id="reg-error-message" class="error-message"></p>
            <p id="reg-success-message" class="success-message"></p>
            <button type="submit">Register</button>
        </form>
        <p>Sudah punya akun? <a href="index.php">Login</a></p>
    </div>
    

    <script>
   document.getElementById('registerForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Mencegah reload halaman

    const formData = new FormData(event.target); // Ambil data dari form

    fetch('register.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Server Error'); // Menangkap kesalahan server
        }
        return response.json(); // Parsing response menjadi JSON
    })
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('reg-error-message').textContent = ''; // Kosongkan pesan error
            document.getElementById('reg-success-message').textContent = data.message; // Tampilkan pesan sukses
            setTimeout(() => {
                window.location.href = data.redirect; // Redirect setelah sukses
            }, 2000);
        } else {
            document.getElementById('reg-error-message').textContent = data.message; // Tampilkan pesan error
            document.getElementById('reg-success-message').textContent = ''; // Kosongkan pesan sukses
        }
    })
    .catch(error => {
        console.error('Error:', error); // Log error ke console
        document.getElementById('reg-error-message').textContent = 'Terjadi kesalahan pada server.'; // Pesan error umum
    });
});

    </script>
</body>
</html>