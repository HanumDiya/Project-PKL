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
    die("Database connection failed: " . $e->getMessage());
}

// Fetch all categories and brands
try {
    $categories = $pdo->query("SELECT * FROM jenis_barang")->fetchAll();
    $brands = $pdo->query("SELECT * FROM brands")->fetchAll();
} catch (\PDOException $e) {
    echo "Failed to fetch data: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Inventory</title>
    <link rel="stylesheet" href="css/addteknis.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<section class="sidebar">
    <a href="dashboard/index.php" class="logo">
      <img src="asset/logo.png" alt="Satria Net Logo" class="logo-img">
      <span class="sidebar-text">SATRIA NET</span>
    </a>
    
    <ul class="side-menu top">
      <li>
        <a href="dashboard/index.php" class="nav-link">
          <i class="fas fa-border-all"></i>
          <span class="sidebar-text">Dashboard</span>
        </a>
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
        <a href="pembayaran.php" class="nav-link">
          <i class="fas fa-money-bill"></i>
          <span class="sidebar-text">Pembayaran</span>
        </a>
      </li>
      <li  class="active">
        <a href="inventori.php" class="nav-link">
          <i class="fas fa-box"></i>
          <span class="sidebar-text">Inventori</span>
        </a>
      </li>
      <li>
        <a href="ticketing.php" class="nav-link">
          <i class="fas fa-ticket-alt"></i>
          <span class="sidebar-text">Ticketing</span>
        </a>
      </li>
      <li>
        <a href="prt.php" class="nav-link">
          <i class="fas fa-tools"></i>
          <span class="sidebar-text">PRT</span>
        </a>
      </li>
    </ul>
    
    <ul class="side-menu">
      <li>
        <a href="#" class="logout" id="logout-btn">
          <i class="fas fa-right-from-bracket"></i>
          <span class="sidebar-text">Logout</span>
        </a>
      </li>
    </ul>
  </section> 
      
<section class="content">
    <div class="container">
        <h2>Tambah Data Inventory</h2>
        <form action="addinven.php" method="POST">
            <div class="form-group">
                <label for="item_name">Item Name</label>
                <input type="text" id="item_name" name="item_name" placeholder="Masukkan Nama Item" required>
            </div>
            <div class="form-group">
                <label for="version">Version</label>
                <input type="text" id="version" name="version" placeholder="Masukkan Versi" required>
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <input type="text" id="type" name="type" placeholder="Masukkan Tipe" required>
            </div>
            <div class="form-group">
                <label for="serial_number">Serial Number</label>
                <input type="text" id="serial_number" name="serial_number" placeholder="Masukkan Serial Number" required>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" id="quantity" name="quantity" placeholder="Masukkan Jumlah" required>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" placeholder="Masukkan Lokasi" required>
            </div>

            <div class="form-group">
                <label for="jenis_barang">Jenis Barang</label>
                <select id="jenis_barang" name="jenis_barang" required>
                    <option value="">-- Pilih Jenis Barang --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" id="brandContainer" style="display: none;">
    <label for="brand">Brand</label>
    <select id="brand" name="brand" required>
        <option value="">-- Pilih Brand --</option>
        <option value="not_available" class="not-available" style="display: none;">Not Available</option>
        <?php foreach ($brands as $brand): ?>
            <option value="<?= $brand['id'] ?>" data-category="<?= $brand['jenis_barang_id'] ?>">
                <?= htmlspecialchars($brand['brand_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

            <div class="form-group">
                <label for="condition_status">Condition Status</label>
                <select id="condition_status" name="condition_status" required>
                    <option value="">-- Pilih Kondisi --</option>
                    <option value="Bagus">Bagus</option>
                    <option value="Terpakai">Terpakai</option>
                    <option value="Rusak">Rusak</option>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Masukkan Deskripsi"></textarea>
            </div>
            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </div>
</section>

<script>$(document).ready(function() {
    // Initially hide the Brand dropdown and "Not Available" option
    $('#brandContainer').hide();
    $('#brand .not-available').hide();

    // Show and filter the Brand dropdown based on selected category
    $('#jenis_barang').change(function() {
        var selectedCategory = $(this).val();
        var brandAvailable = false;

        // Reset visibility of all brand options
        $('#brand option').hide();
        $('#brand .not-available').hide(); // Hide "Not Available" by default

        if (selectedCategory) {
            // Check if there are brands for the selected category
            $('#brand option').each(function() {
                if ($(this).data('category') == selectedCategory) {
                    $(this).show(); // Show brands matching the selected category
                    brandAvailable = true;
                }
            });

            // Show "Not Available" only if no brands match the selected category
            if (!brandAvailable) {
                $('#brand .not-available').show();
            }

            // Show the Brand dropdown if a category is selected
            $('#brandContainer').show();
            $('#brand').val(''); // Reset brand selection
        } else {
            // Hide the Brand dropdown if no category selected
            $('#brandContainer').hide();
            $('#brand').val('');
        }
    });
});

    $(document).ready(function() {
        // Filter brands based on selected category
        $('#jenis_barang').change(function() {
            var selectedCategory = $(this).val();
            $('#brand option').each(function() {
                $(this).toggle($(this).data('category') == selectedCategory || !$(this).data('category'));
            });
            $('#brand').val(''); // Reset the brand selection
        });
    });
    $(document).ready(function() {
        // Initially hide the Brand dropdown
        $('#brandContainer').hide();

        // Show and filter the Brand dropdown based on selected category
        $('#jenis_barang').change(function() {
            var selectedCategory = $(this).val();

            // Check if a category is selected
            if (selectedCategory) {
                // Filter brands by selected category
                $('#brand option').each(function() {
                    $(this).toggle($(this).data('category') == selectedCategory || !$(this).data('category'));
                });
                
                // Show the Brand dropdown and reset selection
                $('#brandContainer').show();
                $('#brand').val('');
            } else {
                // Hide the Brand dropdown if no category selected
                $('#brandContainer').hide();
                $('#brand').val('');
            }
        });
    });
</script>
</body>
</html>
