<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Satrianet | Tambah Data Teknis</title>
    
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
            <li><a href="inventori.php" class="nav-link"><i class="fas fa-box"></i><span class="text">Inventori</span></a></li>
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
                    <!-- Form Input Data Teknis -->
                    <form method="POST" enctype="multipart/form-data" action="add_teknis.php">
                        <div class="form-group">
                            <label for="metro_e">Metro-E</label>
                            <input type="text" name="metro_e" id="metro_e" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="tipe">Tipe</label>
                            <select name="tipe" id="tipe" required>
                                <option value="" disabled selected>Select Type</option>
                                <option value="trunk">Trunk</option>
                                <option value="access">Access</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="kapasitas_mbps">Kapasitas (MBps)</label>
                            <input type="number" name="kapasitas_mbps" id="kapasitas_mbps" required>
                        </div>

                        <div class="form-group">
                            <label for="ip1">IP 1</label>
                            <input type="text" name="ip1" id="ip1" required>
                        </div>

                        <div class="form-group">
                            <label for="ip2">IP 2</label>
                            <input type="text" name="ip2" id="ip2">
                        </div>

                        <div class="form-group">
                            <label for="vlan">VLAN</label>
                            <input type="number" name="vlan" id="vlan" required>
                        </div>

                        <div class="form-group">
                            <label for="sfp">SFP</label>
                            <select name="sfp" id="sfp" required>
                                <option value="" disabled selected>Select SFP</option>
                                <option value="bidi">Bidi</option>
                                <option value="single_mode">Single Mode</option>
                                <option value="multimode">Multimode</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="kapasitas_gbps">Kapasitas (Gbps)</label>
                            <input type="number" name="kapasitas_gbps" id="kapasitas_gbps" required>
                        </div>

                        <div class="form-group">
                            <label for="jarak">Jarak (Km)</label>
                            <input type="number" name="jarak" id="jarak" required>
                        </div>

                        <div class="form-group">
                            <label for="perangkat">Perangkat</label>
                            <input type="text" name="perangkat" id="perangkat" required>
                        </div>

                        <div class="form-group">
                            <label for="port1">Port 1</label>
                            <input type="text" name="port1" id="port1" required>
                        </div>

                        <div class="form-group">
                            <label for="port2">Port 2</label>
                            <input type="text" name="port2" id="port2">
                        </div>

                        <div class="form-group">
                            <label for="port3">Port 3</label>
                            <input type="text" name="port3" id="port3">
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" required>
                                <option value="" disabled selected>Select Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_aktivasi">Tanggal Aktivasi</label>
                            <input type="date" name="tanggal_aktivasi" id="tanggal_aktivasi" required>
                        </div>

                        <div class="form-group">
                            <label for="dokumen">Dokumen (PDF/DOC/DOCX)</label>
                            <input type="file" name="dokumen" id="dokumen" accept=".pdf,.doc,.docx" required>
                        </div>

                        <div class="form-group">
                            <button type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </section>

    <script src="js/app.js"></script>
</body>
</html>