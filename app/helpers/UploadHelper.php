<?php

class UploadHelper
{
    // Jenis file yang boleh diupload
    private static $allowed_types = ['pdf', 'jpg', 'jpeg', 'png'];

    // Ukuran maksimum file (5MB)
    private static $max_file_size = 5242880;

    // Fungsi utama untuk upload file
    public static function upload($input = 'file', $folder = 'uploads')
    {
        // Pastikan ada file yang diupload
        if (empty($_FILES[$input]) || $_FILES[$input]['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Tidak ada file yang diupload atau terjadi error.'];
        }

        $file = $_FILES[$input];
        $name = $file['name'];
        $size = $file['size'];
        $tmp  = $file['tmp_name'];
        $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        // Cek tipe file
        if (!in_array($ext, self::$allowed_types)) {
            return ['success' => false, 'message' => 'Tipe file tidak diperbolehkan. Hanya: ' . implode(', ', self::$allowed_types)];
        }

        // Cek ukuran file
        if ($size > self::$max_file_size) {
            $max_mb = self::$max_file_size / (1024 * 1024);
            return ['success' => false, 'message' => "Ukuran file terlalu besar. Maksimal {$max_mb}MB."];
        }

        // Tentukan folder tujuan
        $path = $_SERVER['DOCUMENT_ROOT'] . '/mail-tracker/public/' . $folder . '/';
        if (!is_dir($path)) mkdir($path, 0777, true);

        // Buat nama file unik
        $new_name = 'file_' . time() . '.' . $ext;
        $target = $path . $new_name;

        // Pindahkan file dari tmp ke folder tujuan
        if (move_uploaded_file($tmp, $target)) {
            return [
                'success' => true,
                'message' => 'File berhasil diupload.',
                'file_name' => $new_name,
                'file_path' => $target
            ];
        }

        return ['success' => false, 'message' => 'Gagal menyimpan file.'];
    }

    // Cek apakah ada file di input
    public static function hasFile($input = 'file')
    {
        return !empty($_FILES[$input]['name']);
    }

    // Hapus file dari folder upload
    public static function deleteFile($file_name, $folder = 'uploads')
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/mail-tracker/public/' . $folder . '/' . $file_name;

        if (file_exists($path)) {
            unlink($path);
            return true;
        }

        return false;
    }
}
