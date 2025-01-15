<?php
function handle_file_upload($file, $allowed_types, $max_file_size, &$errors) {
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
        $upload_dir = 'uploads/';
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