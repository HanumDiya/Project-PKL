<?php
require 'functions.php';
check_login();  // Assuming session and login functions

// Database connection settings
$host = 'localhost';
$db = 'isp';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $item_name = $_POST['item_name'];
    $jenis_barang = $_POST['jenis_barang']; // ID of the selected category
    $type = $_POST['type'];
    $version = $_POST['version'];
    $brand = $_POST['brand'];
    $serial_number = $_POST['serial_number'];
    $quantity = $_POST['quantity'];
    
    // Map condition status to the values used in the database ENUM
    $condition_map = [
        "Bagus" => "good",
        "Terpakai" => "in-use",
        "Rusak" => "damaged"
    ];
    $condition_status = $condition_map[$_POST['condition_status']] ?? 'good';
    
    $current_location = $_POST['location'];
    $description = $_POST['description'] ?? '';

    // Insert data into the inventory table
    try {
        $stmt = $pdo->prepare("INSERT INTO inventory (item_name, jenis_barang, type, version, brand, serial_number, quantity, condition_status, current_location, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$item_name, $jenis_barang, $type, $version, $brand, $serial_number, $quantity, $condition_status, $current_location, $description]);

        // Redirect to the inventory page after successful submission
        header("Location: inventori.php?status=sukses");
        exit();
    } catch (\PDOException $e) {
        echo "Gagal menyimpan data inventory: " . $e->getMessage();
    }
} else {
    // Redirect if accessed directly without a POST request
    header("Location: addinv.php");
    exit();
}
?>
