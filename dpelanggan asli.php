<?php
require 'functions.php';
check_login();  // Assuming session and login functions

$servername = "localhost";
$db_user = "root";
$db_passw = "";
$dbname = "isp";

// Create connection
$conn = new mysqli($servername, $db_user, $db_passw, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Menangani aksi hapus
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    
    // Menghapus data pelanggan dan nomor telepon terkait dari database
    $conn->query("DELETE FROM nomor_telepon WHERE pelanggan_id = $id");
    $conn->query("DELETE FROM data_pelanggan WHERE id = $id");

    $_SESSION['success_message'] = "Data pelanggan berhasil dihapus.";
    header("Location: dpelanggan.php");
    exit();
}

// Menampilkan notifikasi jika ada
if (isset($_SESSION['success_message'])) {
    echo "<script>
            Swal.fire({
                title: '" . $_SESSION['success_message'] . "',
                icon: 'success',
                timer: 2000,
                timerProgressBar: true,
            });
          </script>";
    unset($_SESSION['success_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Satrianet | Data Pelanggan</title>
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- CSS File -->
  <link rel="stylesheet" href="css/dpelanggan.css">

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
</head>
<body>
  <section class="sidebar">
    <a href="dashboard.php" class="logo">
      <img src="asset/logo.png" alt="Satria Net Logo" class="logo-img">
      <span class="text">SATRIA NET</span>
    </a>
    
    <ul class="side-menu top">
      <li>
        <a href="dashboard/index.php" class="nav-link">
          <i class="fas fa-border-all"></i>
          <span class="text">Dashboard</span>
        </a>
      </li>
      <li class="active">
        <a href="/dashboard/index.php" class="nav-link">
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
        <a href="ticketing.php" class="nav-link">
          <i class="fas fa-ticket-alt"></i>
          <span class="text">Ticketing</span>
        </a>
      </li>
      <li>
        <a href="prt.php" class="nav-link">
          <i class="fas fa-tools"></i>
          <span class="text">PRT</span>
        </a>
      </li>
    </ul>
    
    <ul class="side-menu">
      <li>
        <a href="#" class="logout" id="logout-btn">
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
          <h1>Data Pelanggan</h1>
          <ul class="breadcrumb">
            <li>
              <a href="#">Data Pelanggan</a>
            </li>
            <i class="fas fa-chevron-right"></i>
            <li>
              <a href="#" class="active">Home</a>
            </li>
          </ul>
        </div>
      </div>
      
      
      <div class="table-data">
        <div class="order">
          <div class="head">
          <h3>Data Pelanggan</h3>
            <a href="add_pelanggan.php" class="button-add">
              <i class="fas fa-plus"></i>
            </a>
          </div>
          <table id="dataPelangganTable" class="dataTable">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Akta</th>
                <th>NPWP</th>
                <th>KTP</th>
                <th>Domisili</th>
                <th>NIB</th>
                <th>KemenKumham</th>
                <th>Phones</th>
                <th>Biaya Total</th>
                <th>Edit</th>
                <th>Hapus</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $query = "SELECT dp.id, dp.nama, dp.Akta, dp.NPWP, dp.KTP, dp.DOMISILI, dp.NIB, dp.KEMEN_KAMHAM, dp.BIAYA_TOTAL,
                            GROUP_CONCAT(nt.phone_number SEPARATOR ', ') as phones
                        FROM data_pelanggan dp
                        LEFT JOIN nomor_telepon nt ON dp.id = nt.pelanggan_id
                        GROUP BY dp.id";

              $result = mysqli_query($conn, $query);

              if (mysqli_num_rows($result) > 0) {
                  $counter = 1; // Nomor urut manual
                  while ($row = mysqli_fetch_assoc($result)) {
                      echo "<tr>";
                      echo "<td>" . $counter . "</td>";  // Menampilkan nomor urut manual
                      // Gunakan fungsi basename untuk menampilkan nama file tanpa path lengkap
                      $akta_file = htmlspecialchars(basename($row["Akta"]));
                      $npwp_file = htmlspecialchars(basename($row["NPWP"]));
                      $ktp_file = htmlspecialchars(basename($row["KTP"]));

                      // Pastikan file download link berfungsi dengan benar
                      echo "<td>" . htmlspecialchars($row["nama"]) . "</td>";
                      echo "<td><a href='uploads/AKTA/" . htmlspecialchars($row["Akta"]) . "' target='_blank'> Download </a></td>";
                      echo "<td><a href='uploads/NPWP/" . htmlspecialchars($row["NPWP"]) . "' target='_blank'> Download </a></td>";
                      echo "<td><a href='uploads/KTP/" . htmlspecialchars($row["KTP"]) . "' target='_blank'> Download </a></td>";
                      echo "<td>" . htmlspecialchars($row["DOMISILI"]) . "</td>";
                      echo "<td>" . htmlspecialchars($row["NIB"]) . "</td>";
                      echo "<td>" . htmlspecialchars($row["KEMEN_KAMHAM"]) . "</td>";
                      echo "<td>" . $row['phones'] . "</td>";
                      echo "<td>" . htmlspecialchars($row["BIAYA_TOTAL"]) . "</td>";
                      echo "<td><a href='edit.php?id=" . $row["id"] . "' class='button-edit'><i class='fas fa-edit'></i></a></td>";
                      echo "<td><a href='dpelanggan.php?hapus=" . $row["id"] . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\");' class='button-delete'><i class='fas fa-trash'></i></a></td>";
                      echo "</tr>";
                      $counter++;
                  }
              } else {
                  echo "<tr><td colspan='12'>No data found</td></tr>";
              }
              ?>
            </tbody>
          </table>
          
        </div>
      </div>
    </main>
  </section>

  <!-- Add jQuery, DataTables, and SweetAlert JavaScript files -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="js/dpelanggan.js"

<script>
    $(document).ready(function() {
      $('#dataPelangganTable').DataTable({
        "pageLength": 10,
        "order": [[0, 'asc']],  // Sorting berdasarkan kolom pertama (ID)
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/Indonesian.json"
        }
      });
    });

    document.querySelector('.logout').addEventListener('click', function(event) {
      event.preventDefault();
      Swal.fire({
        title: 'Anda berhasil logout!',
        icon: 'success',
        timer: 2000,
        timerProgressBar: true,
        didClose: () => {
          window.location.href = '../logout.php';
        }
      });
    });
  </script>
</body>
</html>
