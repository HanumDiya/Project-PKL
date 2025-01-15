<?php
// Include file functions.php untuk fungsi session login
require 'functions.php';
check_login(); // Memastikan pengguna sudah login

// Konfigurasi database
$servername = "localhost";
$db_user = "root";
$db_passw = "";
$dbname = "isp";

// Membuat koneksi
$conn = new mysqli($servername, $db_user, $db_passw, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Menghapus data teknis berdasarkan ID
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    
    // Menghapus record dari database
    $conn->query("DELETE FROM datta_teknis WHERE id = $id");

    $_SESSION['success_message'] = "Data teknis berhasil dihapus.";
    header("Location: datateknis.php");
    exit();
}

// Mengambil data dari database
$sql = "SELECT * FROM datta_teknis";  // Pastikan nama tabel benar
$result = $conn->query($sql);

// Cek apakah query berhasil
if (!$result) {
    die("Error query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Satrianet | Data Teknis</title>
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/invventory.css">

  <!-- Tambahkan CSS untuk pengguliran tabel -->
  <style>
    .table-wrapper {
      overflow-x: auto; /* Tambahkan overflow-x untuk scroll horizontal */
    }
  </style>

</head>
<body>

  <!-- Sidebar -->     
  <section class="sidebar">
    <a href="dashboard/index.php" class="logo">
      <img src="asset/logo.png" alt="Satria Net Logo" class="logo-img">
      <span class="text">SATRIA NET</span>
    </a>
    
    <ul class="side-menu top">
      <li>
        <a href="dashboard.php" class="nav-link">
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
  
  <!-- Konten -->
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
          <ul class="breadcrumb">
            <li>
              <a href="#">Data Teknis</a>
            </li>
            <i class="fas fa-chevron-right"></i>
            <li>
              <a href="#" class="active">Home</a>
            </li>
          </ul>
        </div>
      </div>

      <!-- Table Wrapper -->
      <div class="table-wrapper">
        <div class="table-data">
          <div class="order">
            <div class="head">
              <h3>Data Teknis</h3>
              <a href="addteknis.php" class="add-btn">
                <i class="fas fa-plus" style="width: 19px;"></i>
              </a>
            </div>
            
            <!-- Data Table -->
            <table id="dataTeknisTable" class="display nowrap" style="width:100%">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Metro-E</th>
                  <th>Tipe</th>
                  <th>Perangkat</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                    if (mysqli_num_rows($result) > 0) {
                      $counter = 1; // Nomor urut manual
                      while ($row = mysqli_fetch_assoc($result)) {
                          echo "<tr>";
                          echo "<td>" . $counter . "</td>";  // Menampilkan nomor urut manual
                          echo "<td>" . htmlspecialchars($row["metro_e"]) . "</td>";  // Metro-E
                          echo "<td>" . htmlspecialchars($row["tipe"]) . "</td>";  // Tipe
                          echo "<td>" . htmlspecialchars($row["perangkat"]) . "</td>";  // Perangkat
                          // Aksi buttons (Info, Edit, Hapus)
                          echo "<td>
                                  <a href='#' class='btn btn-info' data-toggle='modal' data-target='#infoModal' 
                                      data-id='" . $row["id"] . "' 
                                      data-history='" . $row["history"] . "' 
                                      data-ip1='" . $row["ip1"] . "' 
                                      data-ip2='" . $row["ip2"] . "' 
                                      data-vlan='" . $row["vlan"] . "' 
                                      data-sfp='" . $row["sfp"] . "' 
                                      data-kapasitas_gbps='" . $row["kapasitas_gbps"] . "' 
                                      data-jarak='" . $row["jarak"] . "'
                                      data-port1='" . $row["port1"] . "' 
                                      data-port2='" . $row["port2"] . "' 
                                      data-port3='" . $row["port3"] . "' 
                                      data-dokumen='" . $row["dokumen"] . "'>
                                      <i class='fas fa-info-circle'></i> Info
                                  </a>
                                  <a href='editteknis.php?id=" . $row["id"] . "' class='btn btn-warning'>
                                      <i class='fas fa-edit'></i> Edit
                                  </a>
                                  <a href='datateknis.php?hapus=" . $row["id"] . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\");' class='btn btn-danger'>
                                      <i class='fas fa-trash'></i> Hapus
                                  </a>
                                </td>";
                          echo "</tr>";
                          $counter++; // Increment nomor urut
                      }
                    } else {
                      echo "<tr><td colspan='5'>Tidak ada data.</td></tr>";
                    }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </section>

  <!-- Modal Info -->
  <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="infoModalLabel">Detail Data Teknis</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <ul class="list-group">
            <li class="list-group-item"><strong>History:</strong> <span id="historyDetail"></span></li>
            <li class="list-group-item"><strong>IP 1:</strong> <span id="ip1Detail"></span></li>
            <li class="list-group-item"><strong>IP 2:</strong> <span id="ip2Detail"></span></li>
            <li class="list-group-item"><strong>VLAN:</strong> <span id="vlanDetail"></span></li>
            <li class="list-group-item"><strong>SFP:</strong> <span id="sfpDetail"></span></li>
            <li class="list-group-item"><strong>Kapasitas (Gbps):</strong> <span id="kapasitasDetail"></span></li>
            <li class="list-group-item"><strong>Jarak:</strong> <span id="jarakDetail"></span></li>
            <li class="list-group-item"><strong>Port 1:</strong> <span id="port1Detail"></span></li>
            <li class="list-group-item"><strong>Port 2:</strong> <span id="port2Detail"></span></li>
            <li class="list-group-item"><strong>Port 3:</strong> <span id="port3Detail"></span></li>
            <li class="list-group-item"><strong>Dokumen:</strong> <a id="dokumenDetail" href="#" target="_blank">Download</a></li>
          </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Bootstrap JS, Popper.js, jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  
  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  
  <script>
$(document).ready(function() {
    // Initialize DataTable
    $('#dataTeknisTable').DataTable({
        responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.11.5/language/id.json' // Menggunakan bahasa Indonesia
        }
    });
    
    // Modal Info
    $('#infoModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var modal = $(this);
        
        // Set detail informasi dari atribut data di tombol
        modal.find('#historyDetail').text(button.data('history'));
        modal.find('#ip1Detail').text(button.data('ip1'));
        modal.find('#ip2Detail').text(button.data('ip2'));
        modal.find('#vlanDetail').text(button.data('vlan'));
        modal.find('#sfpDetail').text(button.data('sfp'));
        modal.find('#kapasitasDetail').text(button.data('kapasitas_gbps'));
        modal.find('#jarakDetail').text(button.data('jarak'));
        modal.find('#port1Detail').text(button.data('port1'));
        modal.find('#port2Detail').text(button.data('port2'));
        modal.find('#port3Detail').text(button.data('port3'));
        
        // Set link download dokumen
        var dokumenFile = button.data('dokumen');
        if (dokumenFile) {
          modal.find('#dokumenDetail')
    .attr('href', 'download.php?file=' + dokumenFile) // Pointing to the download script
    .attr('target', '_blank') // Opens in a new tab
    .text('Download'); // Link text
        } else {
            modal.find('#dokumenDetail').attr('href', '#').text('Tidak ada dokumen');
        }
    });
});
</script>
</body>
</html>
