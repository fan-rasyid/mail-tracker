<?php

class UploadHelper
{
    // Set allowed file types
    private static $allowed_types = ['pdf', 'jpg', 'jpeg', 'png'];
    
    // Set maximum file size (in bytes) - 5MB
    private static $max_file_size = 5242880;

    public static function upload($file_input_name = 'file', $upload_dir = 'uploads')
    {
        // Check if file was uploaded
        if (!isset($_FILES[$file_input_name]) || $_FILES[$file_input_name]['error'] !== UPLOAD_ERR_OK) {
            return [
                'success' => false,
                'message' => 'No file was uploaded or upload error occurred'
            ];
        }

        // Get file information
        $file = $_FILES[$file_input_name];
        $file_name = $file['name'];
        $file_size = $file['size'];
        $file_tmp = $file['tmp_name'];
        
        // Get file extension
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Validate file type
        if (!in_array($file_extension, self::$allowed_types)) {
            return [
                'success' => false,
                'message' => 'File type not allowed. Only ' . implode(', ', self::$allowed_types) . ' files are allowed.'
            ];
        }
        
        // Validate file size
        if ($file_size > self::$max_file_size) {
            $max_size_mb = self::$max_file_size / (1024 * 1024); // Convert to MB for display
            return [
                'success' => false,
                'message' => 'File size too large. Maximum allowed size is ' . $max_size_mb . 'MB.'
            ];
        }
        
        // Create upload directory if it doesn't exist
        $upload_path = $_SERVER['DOCUMENT_ROOT'] . '/mail-tracker/public/' . $upload_dir . '/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }
        
        // Create a unique filename (add timestamp to prevent conflicts)
        $new_file_name = 'file_' . time() . '.' . $file_extension;
        $destination = $upload_path . $new_file_name;
        
        // Move the uploaded file to the destination
        if (move_uploaded_file($file_tmp, $destination)) {
            return [
                'success' => true,
                'message' => 'File uploaded successfully',
                'file_name' => $new_file_name,
                'file_path' => $destination
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to save the uploaded file'
            ];
        }
    }

    public static function hasFile($file_input_name = 'file')
    {
        return isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['name'] !== '';
    }
    
    public static function deleteFile($file_name, $upload_dir = 'uploads')
    {
        $file_path = $_SERVER['DOCUMENT_ROOT'] . '/mail-tracker/public/' . $upload_dir . '/' . $file_name;
        
        if (file_exists($file_path)) {
            return unlink($file_path); // Delete the file
        }
        
        return false; // File doesn't exist
    }
    
    public static function getAllowedTypes()
    {
        return self::$allowed_types;
    }
    
    public static function getMaxFileSize()
    {
        return self::$max_file_size;
    }
}