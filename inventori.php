<?php
require 'functions.php';
check_login();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log errors to a file for debugging (check the file after execution)
ini_set('log_errors', 1);
ini_set('error_log', 'php-error.log'); // Logs errors to php-error.log in the same directory

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
    die("Database connection failed: " . $e->getMessage());
}

// Handle Update Status via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'updateStatus') {
    $itemId = $_POST['itemId'] ?? null;
    $status = $_POST['status'] ?? null;
    $target_location = $_POST['location'] ?? null;
    $status_detail = $_POST['status_detail'] ?? null;
    $description = $_POST['description'] ?? null;

    if (!$itemId || !$status) {
        echo json_encode(['success' => false, 'error' => 'Item ID and Status are required']);
        exit();
    }

    try {
        // Ambil data saat ini dari inventory
        $stmt = $pdo->prepare("SELECT current_location, description FROM inventory WHERE id = ?");
        $stmt->execute([$itemId]);
        $currentData = $stmt->fetch();

        if (!$currentData) {
            echo json_encode(['success' => false, 'error' => 'Item not found']);
            exit();
        }

        $currentLocation = $currentData['current_location'];
        $currentDescription = $currentData['description'];

        // Tentukan lokasi baru berdasarkan status detail
        $newLocation = ($status_detail === 'arrived') ? $target_location : $currentLocation;

        // Jika description kosong, gunakan description lama
        $finalDescription = $description ?: $currentDescription;

        // Update inventory dengan lokasi baru dan deskripsi
        $stmt = $pdo->prepare("UPDATE inventory SET current_location = ?, description = ? WHERE id = ?");
        $stmt->execute([$newLocation, $finalDescription, $itemId]);

        // Masukkan riwayat status ke tabel inventory_history
        $historyStmt = $pdo->prepare("
            INSERT INTO inventory_history (inventory_id, status, location, status_detail, description, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $historyStmt->execute([$itemId, $status, $newLocation, $status_detail, $finalDescription]);

        echo json_encode(['success' => true, 'location' => $newLocation]);
    } catch (\PDOException $e) {
        // Catat kesalahan SQL
        error_log('SQL Error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'SQL Error: ' . $e->getMessage()]);
    }
    exit();
}

// Handle Delete Item via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'deleteItem') {
  $itemId = $_POST['itemId'] ?? null;

  if (!$itemId) {
      echo json_encode(['success' => false, 'error' => 'Invalid Item ID']);
      exit();
  }

  try {
      // Delete the item from the inventory table
      $stmt = $pdo->prepare("DELETE FROM inventory WHERE id = ?");
      $stmt->execute([$itemId]);

      // Optionally delete related history (if needed)
      $historyStmt = $pdo->prepare("DELETE FROM inventory_history WHERE inventory_id = ?");
      $historyStmt->execute([$itemId]);

      echo json_encode(['success' => true]);
  } catch (\PDOException $e) {
      error_log('SQL Error: ' . $e->getMessage());
      echo json_encode(['success' => false, 'error' => 'Failed to delete item.']);
  }
  exit();
}

// Fetch History via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['inventory_id'])) {
    $inventoryId = $_GET['inventory_id'] ?? null;

    if (!$inventoryId) {
        echo json_encode(['error' => 'Invalid Inventory ID']);
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT created_at, status, location, status_detail, description 
                               FROM inventory_history 
                               WHERE inventory_id = ? 
                               ORDER BY created_at DESC");
        $stmt->execute([$inventoryId]);
        echo json_encode($stmt->fetchAll());
    } catch (\PDOException $e) {
        error_log('Fetch History Error: ' . $e->getMessage());
        echo json_encode(['error' => 'Failed to fetch history: ' . $e->getMessage()]);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'editItem') {
    $itemId = $_POST['item_id'];
    $itemName = $_POST['item_name'];
    $version = $_POST['version'];
    $type = $_POST['type'];
    $serialNumber = $_POST['serial_number'];
    $quantity = $_POST['quantity'];
    $jenisBarang = $_POST['jenis_barang'];
    $brand = $_POST['brand'];
    $condition = $_POST['condition_status'];

    try {
        $stmt = $pdo->prepare("
            UPDATE inventory
            SET item_name = ?, version = ?, type = ?, serial_number = ?, 
                quantity = ?, jenis_barang = ?, brand = ?, condition_status = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $itemName, $version, $type, $serialNumber,
            $quantity, $jenisBarang, $brand, $condition, $itemId
        ]);

        echo json_encode(['success' => true]);
    } catch (\PDOException $e) {
        error_log('Error updating item: ' . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Failed to update item']);
    }
    exit();
}

// Ambil data untuk dropdown Jenis Barang
try {
    $categories = $pdo->query("SELECT id, nama_kategori FROM jenis_barang")->fetchAll(PDO::FETCH_ASSOC);
} catch (\PDOException $e) {
    error_log('Fetch Jenis Barang Error: ' . $e->getMessage());
    $categories = [];
}

// Ambil data untuk dropdown Brand
try {
    $brands = $pdo->query("SELECT id, brand_name, jenis_barang_id FROM brands")->fetchAll(PDO::FETCH_ASSOC);
} catch (\PDOException $e) {
    error_log('Fetch Brand Error: ' . $e->getMessage());
    $brands = [];
}

// Fetch inventory data
try {
    $inventoryItems = $pdo->query("
        SELECT inventory.*, 
               jenis_barang.nama_kategori AS jenis_barang_name, 
               brands.brand_name AS brand_name
        FROM inventory
        LEFT JOIN jenis_barang ON inventory.jenis_barang = jenis_barang.id
        LEFT JOIN brands ON inventory.brand = brands.id
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (\PDOException $e) {
    error_log('Fetch Inventory Data Error: ' . $e->getMessage());
    $inventoryItems = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Satrianet | Inventory</title>
  <link rel="stylesheet" href="css/p.css">
  
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  
  <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    .update-btn {
        pointer-events: auto;
        background-color: blue;
        color: white;
        padding: 5px 10px;
        border: none;
        cursor: pointer;
        border-radius: 4px;
    }
    #inventory-table tbody tr:hover {
        background-color: #f0f0f0;
    }
    .add-btn, .btn-primary, .update-btn {
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 5px;
        display: inline-block;
        margin: 5px;
    }
    .btn-primary {
        background-color: #007bff;
        color: white;
    }
    /* Flex container to align buttons horizontally */
.action-buttons {
    display: flex;
    gap: 5px; /* Space between each button */
}

/* Style for buttons */
.btn-info {
    background-color: #17a2b8;
    border-color: #17a2b8;
    color: white;
    width: 90px; /* Consistent width for each button */
}

.btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: black;
    width: 90px;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
    width: 90px;
}

/* Optional: Add this to center-align the text inside each button */
.btn-sm {
    display: flex;
    align-items: center;
    justify-content: center;
}
.btn-lg i {
        font-size: 22px;
    }
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    .btn-lg {
        padding: 10px 20px;
        font-size: 15px;
        border-radius: 10px;
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
      <li class="active">
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
      <a href="logout.php" id="logout-btn" class="logout">
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
      <div class="head-title">
        <div class="left">
          <h1>Inventori</h1>
          <ul class="breadcrumb">
            <li>
              <a href="inventori.php">Inventori</a>
            </li>
            <i class="fas fa-chevron-right"></i>
            <li>
              <a href="/dashboard/" class="active">Home</a>
            </li>
          </ul>
        </div>
      </div>
      

      <div class="d-flex justify-content mt-4">
    <a href="add.php" class="btn btn-primary btn-lg">
        Manage Brand
    </a>
</div>

      </div>
        <tr>
              <a href="addinv.php" class="add-btn">
                <i class="fas fa-plus" style="width: 19px;"></i>
              </a>
            </div> 
            <table id="inventory-table" class="dataTable">
            <thead>
    <tr>
        <th><input type="checkbox" id="selectAll"></th>
        <th>ID</th>
        <th>Item Name</th>
        <th>Version</th>
        <th>Type</th>
        <th>Brand</th>
        <th>Serial Number</th>
        <th>Quantity</th>
        <th>Condition</th>
        <th>Lokasi</th>
        <th>Deskripsi</th>
        <th>Jenis Barang</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
<?php
$counter = 1;
foreach ($inventoryItems as $row): ?>
    <tr>
        <td><input type='checkbox' name='selectedItems[]' value='<?= htmlspecialchars($row['id'] ?? '') ?>'></td>
        <td><?= $counter++ ?></td>
        <td><?= htmlspecialchars($row['item_name'] ?? '-') ?></td>
        <td><?= htmlspecialchars($row['version'] ?? '-') ?></td>
        <td><?= htmlspecialchars($row['type'] ?? '-') ?></td>
        <td><?= htmlspecialchars($row['brand_name'] ?? '-') ?></td>
        <td><?= htmlspecialchars($row['serial_number'] ?? '-') ?></td>
        <td><?= htmlspecialchars($row['quantity'] ?? '-') ?></td>
        <td><?= htmlspecialchars($row['condition_status'] ?? '-') ?></td>
        <td><?= htmlspecialchars($row['current_location'] ?? '-') ?></td>
        <td><?= htmlspecialchars($row['description'] ?? '-') ?></td>
        <td><?= htmlspecialchars($row['jenis_barang_name'] ?? '-') ?></td>
        <td>
    <div class="action-buttons">
        <button class="btn btn-info btn-sm" onclick="showHistory(<?= $row['id'] ?>)">
            <i class="fas fa-info-circle"></i> Info
        </button>
        <button class="btn btn-warning btn-sm" onclick="openModal(<?= $row['id'] ?>)">
            <i class="fas fa-edit"></i> Edit
        </button>
        <button class="btn btn-warning btn-sm" onclick="openEditItemModal(<?= htmlspecialchars(json_encode($row)) ?>)">
                    <i class="fas fa-edit"></i> Edit
                </button>
        <button class="btn btn-danger btn-sm" onclick="deleteItem(<?= $row['id'] ?>)">
            <i class="fas fa-trash"></i> Delete
        </button>
    </div>
</td>
    </tr>
<?php endforeach; ?>
</tbody>

</table>
</section>

<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Item Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateForm">
                    <input type="hidden" id="itemId" name="itemId">
                    <div class="mb-3">
                        <label for="statusSelect" class="form-label">Status</label>
                        <select class="form-control" id="statusSelect" name="status">
                            <option value="">Select Status</option>
                            <option value="in">In</option>
                            <option value="out">Out</option>
                        </select>
                    </div>
                    <div class="mb-3" id="locationGroup" style="display: none;">
                        <label for="locationInput" class="form-label">Location</label>
                        <input type="text" class="form-control" id="locationInput" name="location" disabled>
                    </div>
                    <div class="mb-3" id="statusDetailGroup" style="display: none;">
    <label for="statusDetailSelect" class="form-label">Status Detail</label>
    <select class="form-control" id="statusDetailSelect" name="status_detail" disabled>
        <option value="">Select</option>
        <option value="on delivery">On Delivery</option>
        <option value="on user">On User</option>
        <option value="arrived">Arrived</option>
    </select>
</div>
                    <div class="mb-3" id="descriptionGroup" style="display: none;">
                        <label for="descriptionInput" class="form-label">Description</label>
                        <textarea class="form-control" id="descriptionInput" name="description" rows="3" disabled></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitUpdate()">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historyModalLabel">History of Item Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Location</th>
                            <th>Detail Status</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody id="historyData"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Edit Item -->
<div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemModalLabel">Edit Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editItemForm">
                    <input type="hidden" id="editItemId" name="item_id">

                    <div class="mb-3">
                        <label for="editItemName" class="form-label">Item Name</label>
                        <input type="text" id="editItemName" name="item_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editVersion" class="form-label">Version</label>
                        <input type="text" id="editVersion" name="version" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="editType" class="form-label">Type</label>
                        <input type="text" id="editType" name="type" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="editSerialNumber" class="form-label">Serial Number</label>
                        <input type="text" id="editSerialNumber" name="serial_number" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="editQuantity" class="form-label">Quantity</label>
                        <input type="number" id="editQuantity" name="quantity" class="form-control">
                    </div>
                    <div class="mb-3">
    <label for="editJenisBarang" class="form-label">Jenis Barang</label>
    <select id="editJenisBarang" name="jenis_barang" class="form-control">
        <option value="">-- Pilih Jenis Barang --</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['nama_kategori']) ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div class="mb-3">
    <label for="editBrand" class="form-label">Brand</label>
    <select id="editBrand" name="brand" class="form-control">
        <option value="">-- Pilih Brand --</option>
        <?php foreach ($brands as $brand): ?>
            <option value="<?= $brand['id'] ?>" data-category="<?= $brand['jenis_barang_id'] ?>">
                <?= htmlspecialchars($brand['brand_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
                    <div class="mb-3">
                        <label for="editCondition" class="form-label">Condition</label>
                        <select id="editCondition" name="condition_status" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="Bagus">Bagus</option>
                            <option value="Terpakai">Terpakai</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitEditItem()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

     <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  
  <script>
$(document).ready(function () {
    // Inisialisasi DataTable
    $('#inventory-table').DataTable({
        pageLength: 10,
        paging: true,
        fixedHeader: true,
        order: [[0, 'asc']],
    });

    // Dinamis update pada dropdown "Brand" berdasarkan "Jenis Barang"
    $('#editJenisBarang').change(function () {
        const selectedCategory = $(this).val();
        $('#editBrand option').each(function () {
            const isMatching = $(this).data('category') == selectedCategory;
            $(this).toggle(isMatching);
        });

        // Reset pilihan brand
        $('#editBrand').val('');
    });

    // Binding event untuk konten dinamis
    $(document).on('click', '.update-btn', function () {
        const itemId = $(this).data('id');
        openModal(itemId);
    });

    $(document).on('click', '.history-btn', function () {
        const itemId = $(this).data('id');
        showHistory(itemId);
    });

    // Pilihan status dan input terkait
    $(document).on('change', '#statusSelect', function () {
        const status = $(this).val();
        $('#locationGroup, #statusDetailGroup, #descriptionGroup').hide();
        $('#locationInput, #statusDetailSelect, #descriptionInput').prop('disabled', true).val('');

        if (status === 'out') {
            $('#locationGroup').show();
            $('#locationInput').prop('disabled', false);
        }
    });

    $(document).on('input', '#locationInput', function () {
        if ($(this).val().trim() !== '') {
            $('#statusDetailGroup').show();
            $('#statusDetailSelect').prop('disabled', false);
        }
    });

    $(document).on('change', '#statusDetailSelect', function () {
        if ($(this).val() !== '') {
            $('#descriptionGroup').show();
            $('#descriptionInput').prop('disabled', false);
        }
    });

    // SweetAlert untuk logout
    $('#logout-btn').on('click', function (event) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure you want to logout?',
            text: 'You will need to login again to access this system.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, logout!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'logout.php';
            }
        });
    });
});

// Fungsi untuk membuka modal update status
function openModal(itemId) {
    $('#itemId').val(itemId);
    $('#statusSelect').val('');
    $('#locationInput').val('').prop('disabled', true).parent().hide();
    $('#statusDetailSelect').val('').prop('disabled', true).parent().hide();
    $('#descriptionInput').val('').prop('disabled', true).parent().hide();
    $('#updateModal').modal('show');
}

// Fungsi untuk membuka modal edit item
function openEditItemModal(item) {
    $('#editItemId').val(item.id);
    $('#editItemName').val(item.item_name);
    $('#editVersion').val(item.version);
    $('#editType').val(item.type);
    $('#editSerialNumber').val(item.serial_number);
    $('#editQuantity').val(item.quantity);
    $('#editJenisBarang').val(item.jenis_barang);
    $('#editCondition').val(item.condition_status);

    // Memperbarui brand yang tersedia
    $('#editJenisBarang').trigger('change');

    // Pilih brand
    $('#editBrand').val(item.brand);

    $('#editItemModal').modal('show');
}

// Fungsi untuk submit update
function submitUpdate() {
    const formData = {
        action: 'updateStatus',
        itemId: $('#itemId').val(),
        status: $('#statusSelect').val(),
        location: $('#locationInput').val(),
        status_detail: $('#statusDetailSelect').val(),
        description: $('#descriptionInput').val(),
    };

    $.ajax({
        url: 'inventori.php',
        type: 'POST',
        data: formData,
        success: function (response) {
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    Swal.fire('Success', `Item updated successfully! New Location: ${result.location}`, 'success').then(() => {
                        $('#updateModal').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', result.error || 'Failed to update item.', 'error');
                }
            } catch (e) {
                console.error('Error parsing response:', e, response);
                Swal.fire('Error', 'Invalid server response.', 'error');
            }
        },
        error: function () {
            Swal.fire('Error', 'Failed to update item due to server error.', 'error');
        },
    });
}

// Fungsi untuk submit edit item
function submitEditItem() {
    const formData = {
        action: 'editItem',
        item_id: $('#editItemId').val(),
        item_name: $('#editItemName').val(),
        version: $('#editVersion').val(),
        type: $('#editType').val(),
        serial_number: $('#editSerialNumber').val(),
        quantity: $('#editQuantity').val(),
        jenis_barang: $('#editJenisBarang').val(),
        brand: $('#editBrand').val(),
        condition_status: $('#editCondition').val(),
    };

    $.ajax({
        url: 'inventori.php',
        type: 'POST',
        data: formData,
        success: function (response) {
            const result = JSON.parse(response);
            if (result.success) {
                Swal.fire('Success', 'Item updated successfully!', 'success').then(() => {
                    $('#editItemModal').modal('hide');
                    location.reload();
                });
            } else {
                Swal.fire('Error', result.error || 'Failed to update item.', 'error');
            }
        },
        error: function () {
            Swal.fire('Error', 'Server error occurred. Please try again.', 'error');
        },
    });
}

// Fungsi untuk menampilkan history
function showHistory(itemId) {
    $.ajax({
        url: 'inventori.php',
        type: 'GET',
        data: { inventory_id: itemId },
        success: function (response) {
            let historyData;
            try {
                historyData = JSON.parse(response);
            } catch (e) {
                console.error('Failed to parse history data', response);
                Swal.fire('Error', 'Failed to fetch history data.', 'error');
                return;
            }

            const historyTableBody = $('#historyData');
            historyTableBody.empty();

            if (historyData.error) {
                Swal.fire('Error', historyData.error, 'error');
            } else if (historyData.length > 0) {
                historyData.forEach((item) => {
                    historyTableBody.append(`
                        <tr>
                            <td>${item.created_at}</td>
                            <td>${item.status}</td>
                            <td>${item.location || '-'}</td>
                            <td>${item.status_detail || '-'}</td>
                            <td>${item.description || '-'}</td>
                        </tr>
                    `);
                });
            } else {
                historyTableBody.append('<tr><td colspan="5">No history available</td></tr>');
            }

            $('#historyModal').modal('show');
        },
        error: function () {
            Swal.fire('Error', 'Failed to fetch history data.', 'error');
        },
    });
}

// Fungsi untuk menghapus item
function deleteItem(itemId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This action cannot be undone!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'inventori.php',
                type: 'POST',
                data: { action: 'deleteItem', itemId: itemId },
                success: function (response) {
                    try {
                        const result = JSON.parse(response);
                        if (result.success) {
                            Swal.fire('Deleted!', 'The item has been deleted.', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', result.error || 'Failed to delete item.', 'error');
                        }
                    } catch (e) {
                        console.error('Parsing error:', e, 'Response text:', response);
                        Swal.fire('Error', 'Invalid server response.', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Failed to delete item due to a server error.', 'error');
                },
            });
        }
    });
}
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
