<?php
require 'functions.php';
check_login();

$servername = "localhost";
$db_user = "root";
$db_passw = "";
$dbname = "isp";

$conn = new mysqli($servername, $db_user, $db_passw, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengambil data pelanggan berdasarkan ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM data_pelanggan WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pelanggan = $result->fetch_assoc();
    $stmt->close();
} else {
    header("Location: dpelanggan.php");
    exit();
}


// Fungsi untuk menangani file upload
function handle_file_upload($file, $allowed_types, $max_file_size, &$errors, $upload_sub_dir, $old_file_name = null) {
    if ($file && $file['size'] > 0) {
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
            // Hapus file lama jika ada
            if ($old_file_name && file_exists($upload_dir . $old_file_name)) {
                unlink($upload_dir . $old_file_name);
            }
            return $file_name; // Return the new file name to save in the database
        } else {
            $errors[] = "Error: Gagal menyimpan file.";
            return false;
        }
    } else {
        return $old_file_name; // Menggunakan file lama jika tidak ada file baru yang diunggah
    }
}

// Menangani form submit untuk update data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'] ?? '';
    $domisili = $_POST['domisili'] ?? '';
    $nib = $_POST['nib'] ?? '';
    $kemen_kamham = $_POST['kemenkamhem'] ?? '';
    $biaya_total = $_POST['biaya_total'] ?? '';

    $akta_file = $_FILES['akta'] ?? null;
    $npwp_file = $_FILES['npwp'] ?? null;
    $ktp_file = $_FILES['ktp'] ?? null;

    $file_errors = [];
    $akta_data = handle_file_upload($akta_file, ['image/jpeg', 'image/png', 'application/pdf'], 10 * 1024 * 1024, $file_errors, 'AKTA', $pelanggan['Akta']);
    $npwp_data = handle_file_upload($npwp_file, ['image/jpeg', 'image/png', 'application/pdf'], 10 * 1024 * 1024, $file_errors, 'NPWP', $pelanggan['NPWP']);
    $ktp_data = handle_file_upload($ktp_file, ['image/jpeg', 'image/png', 'application/pdf'], 10 * 1024 * 1024, $file_errors, 'KTP', $pelanggan['KTP']);

    if (!empty($file_errors)) {
        foreach ($file_errors as $error) {
            echo $error . "<br>";
        }
        exit();
    }

    // Update data di database
    $stmt = $conn->prepare("UPDATE data_pelanggan SET nama = ?, Akta = ?, NPWP = ?, KTP = ?, DOMISILI = ?, NIB = ?, KEMEN_KAMHAM = ?, BIAYA_TOTAL = ? WHERE id = ?");
    $stmt->bind_param("ssssssssi", $nama, $akta_data, $npwp_data, $ktp_data, $domisili, $nib, $kemen_kamham, $biaya_total, $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['success_message'] = "Data berhasil diperbarui.";
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
    <title>Edit Data Pelanggan</title>
    <link rel="stylesheet" href="css/form.css">
</head>
<body>
    <div class="background-decor"></div>
    <div class="form-container">
        <h3>Edit Data Pelanggan</h3>
        <form id="customer-form" method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($pelanggan['nama']); ?>" required>
            </div>

            <div class="form-group">
                <label for="domisili">Domisili</label>
                <input type="text" id="domisili" name="domisili" value="<?php echo htmlspecialchars($pelanggan['DOMISILI']); ?>" required>
            </div>

            <div class="form-group">
                <label for="nib">NIB</label>
                <input type="text" id="nib" name="nib" value="<?php echo htmlspecialchars($pelanggan['NIB']); ?>" required>
            </div>

            <div class="form-group">
                <label for="kemenkamhem">Kemenkamhem</label>
                <input type="text" id="kemenkamhem" name="kemenkamhem" value="<?php echo htmlspecialchars($pelanggan['KEMEN_KAMHAM']); ?>" required>
            </div>

            <div class="form-group">
                <label for="biaya_total">Biaya Total</label>
                <input type="text" id="biaya_total" name="biaya_total" value="<?php echo htmlspecialchars($pelanggan['BIAYA_TOTAL']); ?>" required>
            </div>

            <!-- File input untuk Akta -->
            <div class="form-group">
                <label for="akta">Foto Akta</label>
                <input type="file" id="akta" name="akta" accept="image/*,application/pdf">
                <?php if (!empty($pelanggan['Akta'])): ?>
                    <small>File saat ini: <a href="uploads/AKTA/<?php echo htmlspecialchars($pelanggan['Akta']); ?>" target="_blank"><?php echo htmlspecialchars($pelanggan['Akta']); ?></a></small>
                <?php endif; ?>
            </div>

            <!-- File input untuk NPWP -->
            <div class="form-group">
                <label for="npwp">Foto NPWP</label>
                <input type="file" id="npwp" name="npwp" accept="image/*,application/pdf">
                <?php if (!empty($pelanggan['NPWP'])): ?>
                    <small>File saat ini: <a href="uploads/NPWP/<?php echo htmlspecialchars($pelanggan['NPWP']); ?>" target="_blank"><?php echo htmlspecialchars($pelanggan['NPWP']); ?></a></small>
                <?php endif; ?>
            </div>

            <!-- File input untuk KTP -->
            <div class="form-group">
                <label for="ktp">Foto KTP</label>
                <input type="file" id="ktp" name="ktp" accept="image/*,application/pdf">
                <?php if (!empty($pelanggan['KTP'])): ?>
                    <small>File saat ini: <a href="uploads/KTP/<?php echo htmlspecialchars($pelanggan['KTP']); ?>" target="_blank"><?php echo htmlspecialchars($pelanggan['KTP']); ?></a></small>
                <?php endif; ?>
            </div>

            <div class="button-group">
                <button type="submit">Save Changes</button>
            </div>
        </form>
        <a href="dpelanggan.php">Kembali</a>
    </div>
</body>
</html>