<?php
require 'functions.php';
check_login(); // Memastikan pengguna telah login

$servername = "localhost";
$db_user = "root";
$db_passw = "";
$dbname = "isp";

$conn = new mysqli($servername, $db_user, $db_passw, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

function handle_file_upload($file, $allowed_types, $max_file_size, &$errors, $upload_sub_dir) {
    if ($file) {
        $file_type = mime_content_type($file['tmp_name']);
        $file_size = $file['size'];

        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "Error: File tidak diperbolehkan. Jenis file harus jpg, png, atau pdf.";
            return false;
        }

        if ($file_size > $max_file_size) {
            $errors[] = "Error: File terlalu besar. Maksimum ukuran file adalah 10 MB.";
            return false;
        }

        // Generate a unique name for the file and save it
        $upload_dir = 'uploads/' . $upload_sub_dir . '/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Buat folder jika belum ada
        }
        $file_name = uniqid() . '-' . basename($file['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return $file_name; // Return the file name to save in the database
        } else {
            $errors[] = "Error: Gagal menyimpan file.";
            return false;
        }
    } else {
        $errors[] = "Error: File tidak ditemukan.";
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'] ?? ''; // Tambahkan variabel untuk nama
    $domisili = $_POST['domisili'] ?? '';
    $nib = $_POST['nib'] ?? '';
    $kemen_kamham = $_POST['kemenkamhem'] ?? '';
    $biaya_total = $_POST['biaya_total'] ?? '';
    $phone_numbers = $_POST['phone'] ?? [];

    $akta_file = $_FILES['akta'] ?? null;
    $npwp_file = $_FILES['npwp'] ?? null;
    $ktp_file = $_FILES['ktp'] ?? null;

    $file_errors = [];
    $akta_data = handle_file_upload($akta_file, ['image/jpeg', 'image/png', 'application/pdf'], 10 * 1024 * 1024, $file_errors, 'AKTA');
    $npwp_data = handle_file_upload($npwp_file, ['image/jpeg', 'image/png', 'application/pdf'], 10 * 1024 * 1024, $file_errors, 'NPWP');
    $ktp_data = handle_file_upload($ktp_file, ['image/jpeg', 'image/png', 'application/pdf'], 10 * 1024 * 1024, $file_errors, 'KTP');

    if (!empty($file_errors)) {
        foreach ($file_errors as $error) {
            echo $error . "<br>";
        }
        exit();
    }

    // Lakukan penyimpanan ke database
    $stmt = $conn->prepare("INSERT INTO data_pelanggan (nama, Akta, NPWP, KTP, DOMISILI, NIB, KEMEN_KAMHAM, BIAYA_TOTAL) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $nama, $akta_data, $npwp_data, $ktp_data, $domisili, $nib, $kemen_kamham, $biaya_total);
    $stmt->execute();

    // Menyimpan nomor telepon
    $pelanggan_id = $stmt->insert_id;
    $stmt->close();

    if ($pelanggan_id) {
        foreach ($phone_numbers as $phone) {
            $phone_stmt = $conn->prepare("INSERT INTO nomor_telepon (pelanggan_id, phone_number) VALUES (?, ?)");
            $phone_stmt->bind_param("is", $pelanggan_id, $phone);
            $phone_stmt->execute();
            $phone_stmt->close();
        }
    }

    $_SESSION['success_message'] = "Data berhasil disimpan.";
    header("Location: dpelanggan.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Pelanggan</title>
    <link rel="stylesheet" href="css/form.css">
</head>
<body>
    <div class="background-decor"></div>
    <div class="form-container">
        <h3>Input Data Pelanggan</h3>
        <form id="customer-form" method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" id="nama" name="nama" required> <!-- Tambahkan input untuk nama -->
            </div>

            <div class="form-group">
                <label for="domisili">Domisili</label>
                <input type="text" id="domisili" name="domisili" required>
            </div>

            <div class="form-group">
                <label for="nib">NIB</label>
                <input type="text" id="nib" name="nib" required>
            </div>

            <div class="form-group">
                <label for="kemenkamhem">Kemenkamhem</label>
                <input type="text" id="kemenkamhem" name="kemenkamhem" required>
            </div>

            <div class="form-group">
                <label for="biaya_total">Biaya Total</label>
                <input type="text" id="biaya_total" name="biaya_total" required>
            </div>

            <div class="form-group">
                <label for="akta">Foto Akta</label>
                <input type="file" id="akta" name="akta" accept="image/*,application/pdf" required>
            </div>

            <div class="form-group">
                <label for="npwp">Foto NPWP</label>
                <input type="file" id="npwp" name="npwp" accept="image/*,application/pdf" required>
            </div>

            <div class="form-group">
                <label for="ktp">Foto KTP</label>
                <input type="file" id="ktp" name="ktp" accept="image/*,application/pdf" required>
            </div>

            <div class="form-group">
                <label for="phone1">Nomor Telp 1</label>
                <input type="tel" id="phone1" name="phone[]" required>
            </div>

            <div id="additional-phones"></div>

            <div class="button-group">
                <button id="add-phone-btn" type="button">Tambah Nomor Telp</button>
                <br>
                <br>
                <br>
                <button type="submit">Save</button>
            </div>
        </form>
        <a href="dpelanggan.php">Kembali</a>
    </div>

<script src="js/form.js"></script>
</body>
</html>