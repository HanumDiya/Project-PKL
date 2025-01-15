<?php
require 'functions.php';
check_login(); // Assuming session and login validation are already handled

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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metro_e = $_POST['metro_e'];
    $tipe = $_POST['tipe'];
    $kapasitas_mbps = $_POST['kapasitas_mbps'];
    $ip1 = $_POST['ip1'];
    $ip2 = $_POST['ip2'];
    $vlan = $_POST['vlan'];
    $sfp = $_POST['sfp'];
    $kapasitas_gbps = $_POST['kapasitas_gbps'];
    $jarak = $_POST['jarak'];
    $perangkat = $_POST['perangkat'];
    $port1 = $_POST['port1'];
    $port2 = $_POST['port2'];
    $port3 = $_POST['port3'];
    $status = $_POST['status'];
    $tanggal_aktivasi = $_POST['tanggal_aktivasi'];
    
    // Handle file upload
    $dokumen = $_FILES['dokumen']['name'];
    $dokumen_temp = $_FILES['dokumen']['tmp_name'];
    $upload_dir = 'uploads/';
    
    if (move_uploaded_file($dokumen_temp, $upload_dir . $dokumen)) {
        // Prepare SQL to insert form data
        $sql = "INSERT INTO datta_teknis (metro_e, tipe, kapasitas_mbps, ip1, ip2, vlan, sfp, kapasitas_gbps, jarak, perangkat, port1, port2, port3, status, tanggal_aktivasi, dokumen)
                VALUES ('$metro_e', '$tipe', '$kapasitas_mbps', '$ip1', '$ip2', '$vlan', '$sfp', '$kapasitas_gbps', '$jarak', '$perangkat', '$port1', '$port2', '$port3', '$status', '$tanggal_aktivasi', '$dokumen')";

        if ($conn->query($sql) === TRUE) {
            echo "Data teknis berhasil ditambahkan.";
            header('Location: datateknis.php');
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Gagal mengunggah dokumen.";
    }
}

$conn->close();
?>
