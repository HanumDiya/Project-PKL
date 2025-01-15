<?php
// Pastikan file yang diminta diambil dari parameter URL
if (isset($_GET['file'])) {
    // Mengambil nama file dari parameter
    $filename = basename($_GET['file']);
    // Tentukan jalur ke folder uploads
    $filepath = 'uploads/' . $filename;

    // Memeriksa apakah file ada
    if (file_exists($filepath)) {
        // Mengatur header untuk mengunduh file
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf'); // Tipe file PDF
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        
        // Membaca file dan mengeluarkannya untuk diunduh
        readfile($filepath);
        exit;
    } else {
        echo "File not found: " . htmlspecialchars($filepath);
    }
} else {
    echo "No file specified.";
}
?>