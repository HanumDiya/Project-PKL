<?php
require 'functions.php';

// Periksa apakah ID dikirim melalui request
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $servername = "localhost";
    $db_user = "root";
    $db_passw = "";
    $dbname = "isp";

    // Buat koneksi
    $conn = new mysqli($servername, $db_user, $db_passw, $dbname);

    // Periksa koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Ambil data berdasarkan ID
    $sql = "SELECT * FROM datta_teknis WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Tampilkan data tambahan yang tidak ada di tabel utama
        echo "<p><strong>Metro-E:</strong> " . htmlspecialchars($row['metro_e']) . "</p>";
        echo "<p><strong>Tipe:</strong> " . htmlspecialchars($row['tipe']) . "</p>";
        echo "<p><strong>Kapasitas (MBps):</strong> " . htmlspecialchars($row['kapasitas_mbps']) . "</p>";
        echo "<p><strong>History:</strong> " . htmlspecialchars($row['history']) . "</p>";
        echo "<p><strong>IP 1:</strong> " . htmlspecialchars($row['ip1']) . "</p>";
        echo "<p><strong>IP 2:</strong> " . htmlspecialchars($row['ip2']) . "</p>";
        echo "<p><strong>VLAN:</strong> " . htmlspecialchars($row['vlan']) . "</p>";
        echo "<p><strong>SFP:</strong> " . htmlspecialchars($row['sfp']) . "</p>";
        echo "<p><strong>Kapasitas (Gbps):</strong> " . htmlspecialchars($row['kapasitas_gbps']) . "</p>";
        echo "<p><strong>Jarak (km):</strong> " . htmlspecialchars($row['jarak']) . "</p>";
        echo "<p><strong>Perangkat:</strong> " . htmlspecialchars($row['perangkat']) . "</p>";
        echo "<p><strong>Port 1:</strong> " . htmlspecialchars($row['port1']) . "</p>";
        echo "<p><strong>Port 2:</strong> " . htmlspecialchars($row['port2']) . "</p>";
        echo "<p><strong>Port 3:</strong> " . htmlspecialchars($row['port3']) . "</p>";
        echo "<p><strong>Status:</strong> " . htmlspecialchars($row['status']) . "</p>";
        echo "<p><strong>Tanggal Aktivasi:</strong> " . htmlspecialchars($row['tanggal_aktivasi']) . "</p>";
        echo "<p><strong>Dokumen:</strong> <a href='uploads/dokumen/" . htmlspecialchars($row['dokumen']) . "' target='_blank'>Download</a></p>";
    } else {
        echo "<p>Data tidak ditemukan.</p>";
    }

    $conn->close();
} else {
    echo "<p>ID tidak valid.</p>";
}
?>