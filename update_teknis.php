<?php
// Koneksi ke database
include 'connect.php'; // Pastikan pathnya benar

session_start(); // Memulai session

// Ambil data dari form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
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

    // File upload handling
    $targetDir = "uploads/"; // Folder for uploaded files
    $fileName = $_FILES['dokumen']['name']; // Original file name
    $fileTmpName = $_FILES['dokumen']['tmp_name'];
    $targetFilePath = $targetDir . $fileName;

    // If a new file is uploaded, move it to the target directory and update the database
    if (!empty($fileName)) {
        if (move_uploaded_file($fileTmpName, $targetFilePath)) {
            // Include the file name in the update query
            $sql = "UPDATE datta_teknis SET 
                    metro_e = '$metro_e', 
                    tipe = '$tipe', 
                    kapasitas_mbps = '$kapasitas_mbps', 
                    ip1 = '$ip1', 
                    ip2 = '$ip2', 
                    vlan = '$vlan', 
                    sfp = '$sfp', 
                    kapasitas_gbps = '$kapasitas_gbps', 
                    jarak = '$jarak', 
                    perangkat = '$perangkat', 
                    port1 = '$port1', 
                    port2 = '$port2', 
                    port3 = '$port3', 
                    status = '$status', 
                    tanggal_aktivasi = '$tanggal_aktivasi', 
                    dokumen = '$fileName'
                    WHERE id = $id";
        } else {
            echo "Error uploading file.";
            exit();
        }
    } else {
        // No file uploaded, so don't update the `dokumen` field
        $sql = "UPDATE datta_teknis SET 
                metro_e = '$metro_e', 
                tipe = '$tipe', 
                kapasitas_mbps = '$kapasitas_mbps', 
                ip1 = '$ip1', 
                ip2 = '$ip2', 
                vlan = '$vlan', 
                sfp = '$sfp', 
                kapasitas_gbps = '$kapasitas_gbps', 
                jarak = '$jarak', 
                perangkat = '$perangkat', 
                port1 = '$port1', 
                port2 = '$port2', 
                port3 = '$port3', 
                status = '$status', 
                tanggal_aktivasi = '$tanggal_aktivasi'
                WHERE id = $id";
    }

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        // Set session message
        $_SESSION['message'] = "Data berhasil diperbarui.";
        // Redirect to data teknis page
        header("Location: datateknis.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Tutup koneksi
$conn->close();
?>
