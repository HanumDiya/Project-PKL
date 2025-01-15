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
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Handle new category (Jenis Barang) form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categoryName']) && isset($_POST['categoryPrefix']) && !isset($_POST['edit_category'])) {
    $categoryName = $_POST['categoryName'];
    $categoryPrefix = $_POST['categoryPrefix'];

    try {
        $stmt = $pdo->prepare("INSERT INTO jenis_barang (nama_kategori, kode_barang) VALUES (?, ?)");
        $stmt->execute([$categoryName, $categoryPrefix]);
        header("Location: add.php");
        exit();
    } catch (\PDOException $e) {
        echo "Failed to save new category: " . $e->getMessage();
    }
}

// Handle delete category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category'])) {
    $categoryId = $_POST['category_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM jenis_barang WHERE id = ?");
        $stmt->execute([$categoryId]);
        header("Location: add.php");
        exit();
    } catch (\PDOException $e) {
        echo "Failed to delete category: " . $e->getMessage();
    }
}

// Handle edit category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_category'])) {
    $categoryId = $_POST['category_id'];
    $categoryName = $_POST['categoryName'];
    $categoryPrefix = $_POST['categoryPrefix'];

    try {
        $stmt = $pdo->prepare("UPDATE jenis_barang SET nama_kategori = ?, kode_barang = ? WHERE id = ?");
        $stmt->execute([$categoryName, $categoryPrefix, $categoryId]);
        header("Location: add.php");
        exit();
    } catch (\PDOException $e) {
        echo "Failed to update category: " . $e->getMessage();
    }
}

// Handle new brand form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['brandName']) && isset($_POST['categoryId']) && !isset($_POST['edit_brand'])) {
    $brandName = $_POST['brandName'];
    $categoryId = $_POST['categoryId'];

    try {
        $stmt = $pdo->prepare("INSERT INTO brands (brand_name, jenis_barang_id) VALUES (?, ?)");
        $stmt->execute([$brandName, $categoryId]);
        header("Location: add.php");
        exit();
    } catch (\PDOException $e) {
        echo "Failed to save new brand: " . $e->getMessage();
    }
}

// Handle delete brand
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_brand'])) {
    $brandId = $_POST['brand_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM brands WHERE id = ?");
        $stmt->execute([$brandId]);
        header("Location: add.php");
        exit();
    } catch (\PDOException $e) {
        echo "Failed to delete brand: " . $e->getMessage();
    }
}

// Handle edit brand
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_brand'])) {
    $brandId = $_POST['brand_id'];
    $brandName = $_POST['brandName'];
    $categoryId = $_POST['categoryId'];

    try {
        $stmt = $pdo->prepare("UPDATE brands SET brand_name = ?, jenis_barang_id = ? WHERE id = ?");
        $stmt->execute([$brandName, $categoryId, $brandId]);
        header("Location: add.php");
        exit();
    } catch (\PDOException $e) {
        echo "Failed to update brand: " . $e->getMessage();
    }
}

// Fetch categories and brands to display in the dropdowns and tables
try {
    $categories = $pdo->query("SELECT * FROM jenis_barang")->fetchAll();
    $brands = $pdo->query("
        SELECT brands.*, jenis_barang.nama_kategori AS jenis_barang_name
        FROM brands
        LEFT JOIN jenis_barang ON brands.jenis_barang_id = jenis_barang.id
    ")->fetchAll();
} catch (\PDOException $e) {
    echo "Failed to fetch data: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Satrianet | Inventory</title>
  <link rel="stylesheet" href="css/p.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
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
      <li>
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
        <!-- Brands Table -->
        <h2>Brands</h2>
        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#brandModal">Tambah Brand</button>
        <table id="inventory-table" class="table table-bordered">
      </div>
    <table id="inventory-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Brand</th>  
                    <th>Jenis Barang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $counter = 1; foreach ($brands as $brand): ?>
                    <tr>
                        <td><?= $counter++ ?></td>
                        <td><?= htmlspecialchars($brand['brand_name']) ?></td>
                        <td><?= htmlspecialchars($brand['jenis_barang_name']) ?></td>
                        <td>
                            <button class="btn btn-primary edit-brand-btn" data-id="<?= $brand['id'] ?>" data-name="<?= $brand['brand_name'] ?>" data-category="<?= $brand['jenis_barang_id'] ?>" data-bs-toggle="modal" data-bs-target="#editBrandModal"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-danger delete-brand-btn" data-id="<?= $brand['id'] ?>"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Categories Table -->
        <h2>Jenis Barang</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">Tambah Jenis</button>
        <table id="inventory-table" class="table table-bordered">
      </div>
    <table id="inventory-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Jenis</th>
                    <th>Kode Barang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $counter = 1; foreach ($categories as $category): ?>
                    <tr>
                        <td><?= $counter++ ?></td>
                        <td><?= htmlspecialchars($category['nama_kategori']) ?></td>
                        <td><?= htmlspecialchars($category['kode_barang']) ?></td>
                        <td>
                            <button class="btn btn-primary edit-category-btn" data-id="<?= $category['id'] ?>" data-name="<?= $category['nama_kategori'] ?>" data-prefix="<?= $category['kode_barang'] ?>" data-bs-toggle="modal" data-bs-target="#editCategoryModal"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-danger delete-category-btn" data-id="<?= $category['id'] ?>"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</section>

<!-- Modals for Adding, Editing, and Deleting -->
<!-- Add Brand Modal -->
<div class="modal fade" id="brandModal" tabindex="-1" aria-labelledby="brandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="add.php" method="POST">
                <div class="modal-header"><h5 class="modal-title" id="brandModalLabel">Tambah Brand</h5></div>
                <div class="modal-body">
                    <input type="text" class="form-control" name="brandName" placeholder="Nama Brand" required>
                    <select class="form-control mt-2" name="categoryId" required>
                        <option value="">Pilih Jenis Barang</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['nama_kategori']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Brand Modal -->
<div class="modal fade" id="editBrandModal" tabindex="-1" aria-labelledby="editBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="add.php" method="POST">
                <input type="hidden" name="edit_brand" value="1">
                <input type="hidden" name="brand_id" id="edit-brand-id">
                <div class="modal-header"><h5 class="modal-title">Edit Brand</h5></div>
                <div class="modal-body">
                    <input type="text" class="form-control" id="editBrandName" name="brandName" required>
                    <select class="form-control mt-2" id="editCategoryId" name="categoryId" required>
                        <option value="">Pilih Jenis Barang</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['nama_kategori']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan Perubahan</button></div>
            </form>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="add.php" method="POST">
                <div class="modal-header"><h5 class="modal-title">Tambah Jenis Barang</h5></div>
                <div class="modal-body">
                    <input type="text" class="form-control" name="categoryName" placeholder="Nama Jenis Barang" required>
                    <input type="text" class="form-control mt-2" name="categoryPrefix" placeholder="Prefix Kode" required>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="add.php" method="POST">
                <input type="hidden" name="edit_category" value="1">
                <input type="hidden" name="category_id" id="edit-category-id">
                <div class="modal-header"><h5 class="modal-title">Edit Jenis Barang</h5></div>
                <div class="modal-body">
                    <input type="text" class="form-control" id="editCategoryName" name="categoryName" required>
                    <input type="text" class="form-control mt-2" id="editCategoryPrefix" name="categoryPrefix" required>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan Perubahan</button></div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
    // Initialize DataTables for all tables with the class 'inventory-table'
    $('.inventory-table').DataTable();

        $(document).on('click', '.edit-brand-btn', function() {
            $('#edit-brand-id').val($(this).data('id'));
            $('#editBrandName').val($(this).data('name'));
            $('#editCategoryId').val($(this).data('category'));
        });

        $(document).on('click', '.delete-brand-btn', function() {
            if (confirm('Are you sure you want to delete this brand?')) {
                $('<form method="POST"><input type="hidden" name="delete_brand" value="1"><input type="hidden" name="brand_id" value="' + $(this).data('id') + '"></form>').appendTo('body').submit();
            }
        });

        $(document).on('click', '.edit-category-btn', function() {
            $('#edit-category-id').val($(this).data('id'));
            $('#editCategoryName').val($(this).data('name'));
            $('#editCategoryPrefix').val($(this).data('prefix'));
        });

        $(document).on('click', '.delete-category-btn', function() {
            if (confirm('Are you sure you want to delete this category?')) {
                $('<form method="POST"><input type="hidden" name="delete_category" value="1"><input type="hidden" name="category_id" value="' + $(this).data('id') + '"></form>').appendTo('body').submit();
            }
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
