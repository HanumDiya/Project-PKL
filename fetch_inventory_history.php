<?php
// Tambahkan konfigurasi database
$host = 'localhost';
$db = 'isp';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Fetch inventory history
if (isset($_GET['inventory_id'])) {
    $inventoryId = $_GET['inventory_id'];
    $stmt = $pdo->prepare("SELECT created_at, status, location, status_detail, description FROM inventory_history WHERE inventory_id = ? ORDER BY created_at DESC");
    $stmt->execute([$inventoryId]);
    echo json_encode($stmt->fetchAll());
    exit();
}
?>
