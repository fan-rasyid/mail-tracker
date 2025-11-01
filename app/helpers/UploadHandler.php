<?php

class UploadHandler
{
    private $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    public function uploadImage()
    {
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/img/';

        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            return json_encode(['error' => 'File upload error.']);
        }

        $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION); // Get file extension
        $newFileName = 'blog_' . time() . '.' . $extension;

        // Validate file type
        if (!in_array($extension, $this->allowedTypes)) {
            return json_encode(['error' => 'Invalid file type. Only JPG, JPEG, PNG, and GIF allowed.']);
        }

        $targetFilePath = $targetDir . $newFileName;

        // Move the uploaded file
        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)) {
            $absoluteUrl = UPLOAD_DIR . $newFileName;
            return json_encode(['url' => $absoluteUrl]); // Return URL for Summernote
        } else {
            return json_encode(['error' => 'Failed to upload file.']);
        }
    }
}
