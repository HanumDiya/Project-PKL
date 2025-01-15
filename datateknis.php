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

// Handle deletion of data (when 'hapus' parameter is set)
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);

    // SQL query to delete the record from the database
    $conn->query("DELETE FROM datta_teknis WHERE id = $id");

    $_SESSION['success_message'] = "Data teknis berhasil dihapus.";
    header("Location: datateknis.php");
    exit();
}

// Fetch data from the database
$sql = "SELECT * FROM datta_teknis";  // Ensure the table name is correct
$result = $conn->query($sql);

// Check if the query was successful
if (!$result) {
    die("Error in query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Satrianet | Data Teknis</title>
  <link rel="stylesheet" href="css/p.css"> <!-- Corrected from invventory.css -->
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
      <li class="active">
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
          <button class="search-btn"><i class="fas fa-search search-icon"></i></button>
        </div>
      </form>
      <a href="profile.php" class="profile"><i class="fas fa-user"></i></a>
    </nav>    
    
    <main>
      <div class="head-title">
        <div class="left">
          <h1>Data Teknis</h1>
          <ul class="breadcrumb">
            <li><a href="#">Data Teknis</a></li>
            <i class="fas fa-chevron-right"></i>
            <li><a href="datateknis.php" class="active">Data Teknis</a></li>
          </ul>
        </div>
      </div>
      <a href="addteknis.php" class="add-btn">
                <i class="fas fa-plus" style="width: 19px;"></i>
              </a>
            
              <div class="table-container">
              <table id="inventory-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Metro-E</th>
            <th>Type</th>
            <th>Device</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php $counter = 1; ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $counter; ?></td>
                <td><?= htmlspecialchars($row["metro_e"]); ?></td>
                <td><?= htmlspecialchars($row["tipe"]); ?></td>
                <td><?= htmlspecialchars($row["perangkat"]); ?></td>
                <td class="btn-action">
                      <!-- Info Button -->
<button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#infoModal_<?= $row['id']; ?>">
  <i class="fas fa-info-circle"></i> Info
</button>
<a href="editteknis.php?id=<?= htmlspecialchars($row["id"]); ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>

                  <a class="btn btn-danger btn-sm delete-button" data-url="datateknis.php?hapus=<?= htmlspecialchars($row['id']); ?>" href="#"><i class="fas fa-trash"></i> Delete</a>
                </td>
              </tr>

<!-- Modal Info -->
<div class="modal fade" id="infoModal_<?= $row['id']; ?>" tabindex="-1" aria-labelledby="infoModalLabel_<?= $row['id']; ?>" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="infoModalLabel_<?= $row['id']; ?>">Detail Data Teknis</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="list-group">
          <li class="list-group-item"><strong>History:</strong> <?= htmlspecialchars($row['history']); ?></li>
          <li class="list-group-item"><strong>IP 1:</strong> <?= htmlspecialchars($row['ip1']); ?></li>
          <li class="list-group-item"><strong>IP 2:</strong> <?= htmlspecialchars($row['ip2']); ?></li>
          <li class="list-group-item"><strong>VLAN:</strong> <?= htmlspecialchars($row['vlan']); ?></li>
          <li class="list-group-item"><strong>SFP:</strong> <?= htmlspecialchars($row['sfp']); ?></li>
          <li class="list-group-item"><strong>Kapasitas (Gbps):</strong> <?= htmlspecialchars($row['kapasitas_gbps']); ?></li>
          <li class="list-group-item"><strong>Jarak:</strong> <?= htmlspecialchars($row['jarak']); ?> km</li>
          <li class="list-group-item"><strong>Port 1:</strong> <?= htmlspecialchars($row['port1']); ?></li>
          <li class="list-group-item"><strong>Port 2:</strong> <?= htmlspecialchars($row['port2']); ?></li>
          <li class="list-group-item"><strong>Port 3:</strong> <?= htmlspecialchars($row['port3']); ?></li>
          <li class="list-group-item"><strong>Dokumen:</strong> 
            <?php if (!empty($row['dokumen'])): ?>
              <a href="download.php?file=<?= urlencode($row['dokumen']); ?>" target="_blank">Download</a>
            <?php else: ?>
              Tidak ada dokumen
            <?php endif; ?>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

              <?php $counter++; ?>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="5">No data available.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </main>
  </section>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    $(document).ready(function () {
      // Initialize DataTable
      $('#inventory-table').DataTable({
        pageLength: 10,
        order: [[0, 'asc']]
      });

      // SweetAlert confirmation for logout
$('#logout-btn').on('click', function (event) {
    event.preventDefault(); // Mencegah aksi default tombol logout

    Swal.fire({
        title: 'Are you sure you want to logout?',
        text: 'You will need to login again to access this system.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, logout!',
        cancelButtonText: 'Cancel',
        reverseButtons: true // Membalikkan posisi tombol
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'logout.php'; // Redirect ke logout.php jika user konfirmasi
        }
    });
});

      // Delete confirmation using SweetAlert2
      $('.delete-button').on('click', function (event) {
        event.preventDefault(); // Prevent default action
        const deleteUrl = $(this).data('url'); // Use data-url instead of href

        Swal.fire({
          title: 'Are you sure?',
          text: "This action cannot be undone!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: deleteUrl,
              type: 'GET',
              success: function (response) {
                Swal.fire('Deleted!', 'The item has been deleted.', 'success').then(() => {
                  location.reload();
                });
              },
              error: function () {
                Swal.fire('Error', 'Failed to delete item due to server error.', 'error');
              }
            });
          }
        });
      });
    });
  </script>
</body>
</html>
