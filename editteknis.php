<?php
// Koneksi ke database
include 'connect.php';

// Ambil id dari URL
$id = $_GET['id'];

// Query untuk mengambil data teknis berdasarkan id
$sql = "SELECT * FROM datta_teknis WHERE id = $id";
$result = $conn->query($sql);

// Cek jika data ditemukan
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc(); // Ambil data sebagai array
} else {
    echo "Data tidak ditemukan";
    exit;
}

// Tutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Teknis</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS File -->
    <link rel="stylesheet" href="css/teknisadd.css">
</head>
<body>
<section class="sidebar">
        <a href="dashboard.php" class="logo">
            <img src="asset/logo.png" alt="Satria Net Logo" class="logo-img">
            <span class="text">SATRIA NET</span>
        </a>
        
        <ul class="side-menu top">
            <li><a href="dashboard.php" class="nav-link"><i class="fas fa-border-all"></i><span class="text">Dashboard</span></a></li>
            <li><a href="dpelanggan.php" class="nav-link"><i class="fas fa-people-group"></i><span class="text">Data Pelanggan</span></a></li>
            <li class="active"><a href="datateknis.php" class="nav-link"><i class="fas fa-cog"></i><span class="text">Data Teknis</span></a></li>
            <li><a href="pembayaran.html" class="nav-link"><i class="fas fa-money-bill"></i><span class="text">Pembayaran</span></a></li>
            <li><a href="inventori.html" class="nav-link"><i class="fas fa-box"></i><span class="text">Inventori</span></a></li>
            <li><a href="ticketing.html" class="nav-link"><i class="fas fa-ticket-alt"></i><span class="text">Ticketing</span></a></li>
            <li><a href="prt.html" class="nav-link"><i class="fas fa-tools"></i><span class="text">PRT</span></a></li>
        </ul>
        
        <ul class="side-menu">
            <li><a href="#" class="logout" id="logout-btn"><i class="fas fa-right-from-bracket"></i><span class="text">Logout</span></a></li>
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
                        <li><a href="#" class="active">Add Teknis</a></li>
                    </ul>
                </div>
            </div>

            <div class="form-container">
                <div class="form-wrapper">
                    <!-- Form Edit Data Teknis -->
                    <form method="POST" enctype="multipart/form-data" action="update_teknis.php">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                        <div class="form-group">
                            <label for="metro_e">Metro-E</label>
                            <input type="text" name="metro_e" id="metro_e" value="<?php echo $row['metro_e']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="tipe">Tipe</label>
                            <select name="tipe" id="tipe" required>
                                <option value="" disabled>Select Type</option>
                                <option value="trunk" <?php if($row['tipe'] == 'trunk') echo 'selected'; ?>>Trunk</option>
                                <option value="access" <?php if($row['tipe'] == 'access') echo 'selected'; ?>>Access</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="kapasitas_mbps">Kapasitas (MBps)</label>
                            <input type="number" name="kapasitas_mbps" id="kapasitas_mbps" value="<?php echo $row['kapasitas_mbps']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="ip1">IP 1</label>
                            <input type="text" name="ip1" id="ip1" value="<?php echo $row['ip1']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="ip2">IP 2</label>
                            <input type="text" name="ip2" id="ip2" value="<?php echo $row['ip2']; ?>">
                        </div>

                        <div class="form-group">
                            <label for="vlan">VLAN</label>
                            <input type="number" name="vlan" id="vlan" value="<?php echo $row['vlan']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="sfp">SFP</label>
                            <select name="sfp" id="sfp" required>
                                <option value="" disabled>Select SFP</option>
                                <option value="bidi" <?php if($row['sfp'] == 'bidi') echo 'selected'; ?>>Bidi</option>
                                <option value="single_mode" <?php if($row['sfp'] == 'single_mode') echo 'selected'; ?>>Single Mode</option>
                                <option value="multimode" <?php if($row['sfp'] == 'multimode') echo 'selected'; ?>>Multimode</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="kapasitas_gbps">Kapasitas (Gbps)</label>
                            <input type="number" name="kapasitas_gbps" id="kapasitas_gbps" value="<?php echo $row['kapasitas_gbps']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="jarak">Jarak (Km)</label>
                            <input type="number" name="jarak" id="jarak" value="<?php echo $row['jarak']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="perangkat">Perangkat</label>
                            <input type="text" name="perangkat" id="perangkat" value="<?php echo $row['perangkat']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="port1">Port 1</label>
                            <input type="text" name="port1" id="port1" value="<?php echo $row['port1']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="port2">Port 2</label>
                            <input type="text" name="port2" id="port2" value="<?php echo $row['port2']; ?>">
                        </div>

                        <div class="form-group">
                            <label for="port3">Port 3</label>
                            <input type="text" name="port3" id="port3" value="<?php echo $row['port3']; ?>">
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" required>
                                <option value="" disabled>Select Status</option>
                                <option value="active" <?php if($row['status'] == 'active') echo 'selected'; ?>>Active</option>
                                <option value="inactive" <?php if($row['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_aktivasi">Tanggal Aktivasi</label>
                            <input type="date" name="tanggal_aktivasi" id="tanggal_aktivasi" value="<?php echo $row['tanggal_aktivasi']; ?>" required>
                        </div>

                        <div class="form-group">
                        <label for="dokumen">Dokumen (PDF/DOC/DOCX)</label>
                        <input type="file" name="dokumen" id="dokumen" accept=".pdf,.doc,.docx">
                        <?php if (!empty($row['dokumen'])): ?>
                            <p>Existing file: <a href="uploads/<?php echo htmlspecialchars($row['dokumen']); ?>" target="_blank"><?php echo htmlspecialchars($row['dokumen']); ?></a></p>
                        <?php else: ?>
                            <p>No existing document</p>
                        <?php endif; ?>
                    </div>

                        <div class="form-group">
                            <button type="submit">Update</button>
                            <h3><a href="datateknis.php" style="font-size: 1rem; margin-left: 0px; display: flex; align-items: center; font-family :'Times New Roman', Times, serif;">Kembali</a></h3>
                        </div>
                    </form>
                </div>
            </div>
        </main>
   
</body>
</html>
