<?php
function start_secure_session() {
    session_start([
        'cookie_lifetime' => 86400, // Sesi bertahan selama 1 hari
        'cookie_secure' => isset($_SERVER['HTTPS']), // Hanya mengirimkan cookie melalui HTTPS
        'cookie_httponly' => true, // Mencegah akses JavaScript ke cookie sesi
        'cookie_samesite' => 'Strict', // Mencegah pengiriman cookie dalam permintaan lintas situs
    ]);
}

function check_login() {
    start_secure_session();
    if (!isset($_SESSION['session_username'])) {
        header('Location: index.php'); // Arahkan ke halaman login jika tidak login
        exit();
    }
}
?>