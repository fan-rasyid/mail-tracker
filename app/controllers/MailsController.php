<?php 

require_once __DIR__ . "/../cores/Controller.php";

class MailsController extends Controller
{
    public function index(){
        $this->requireAuth();
        $mails = $this->model('MailsModel')->getAllMails();
        $data = [
            'title' => 'Daftar Mail',
            'mails' => $mails
        ];
        return $this->view('mail/index', $data);
    }

    public function create(){
        $this->requireAuth();
        return $this->view('mail/create');
    }

    public function store(){
        $this->requireAuth();
        // Server-side validation
        $required_fields = ['date', 'sender', 'subject', 'recepient', 'type_mail'];
        $errors = [];

        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $errors[] = ucfirst($field) . ' is required';
            }
        }

        // Check if file is required - if $_FILES is not set or has no name, it's missing
        if (!isset($_FILES['file']) || empty($_FILES['file']['name']) || $_FILES['file']['error'] === UPLOAD_ERR_NO_FILE) {
            $errors[] = 'File is required';
        }
        
        if (!empty($errors)) {
            Flasher::setFlasher(implode(', ', $errors), 'failed', 'danger');
            header('location:' . BASEURL . 'MailsController/incomingMail');
            exit;
        }

        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            // Validate file type
            $allowed_extensions = ['pdf', 'jpg', 'jpeg', 'png'];
            $extension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
            
            if (!in_array($extension, $allowed_extensions)) {
                Flasher::setFlasher('File type not allowed. Only PDF, JPG, JPEG, and PNG files are allowed.', 'failed', 'danger');
                header('location:' . BASEURL . 'MailsController/incomingMail');
                exit;
            }
            
            // Validate file size 
            $max_file_size = 5 * 1024 * 1024; // 5MB in bytes
            if ($_FILES['file']['size'] > $max_file_size) {
                Flasher::setFlasher('File size too large. Maximum allowed size is 5MB.', 'failed', 'danger');
                header('location:' . BASEURL . 'MailsController/incomingMail');
                exit;
            }

            $uploadDir = UPLOAD_FOLDER;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
            }

            // Replace the file name
            $newFileName = 'mail_' . time() . '.' . $extension;
            $uploadFile = $uploadDir . $newFileName; // Full file system path

            // Move the uploaded file
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
                $_POST['file'] = $newFileName;
            } else {
                Flasher::setFlasher('File upload failed', 'failed', 'danger');
                header('location:' . BASEURL . 'MailsController/incomingMail');
                exit;
            }
        } else {
            // This shouldn't be reached now because we check for file presence in validation above
            $_POST['file'] = null; // No file uploaded
        }

        $iduser = $_SESSION['user']['id_user'];
        $_POST['id_user'] = $iduser;
        $_POST['type'] = 'in'; // Default to incoming mail

        if ($this->model('MailsModel')->createNewMail($_POST) > 0) {
            Flasher::setFlasher('Mail added successfully', 'success', 'success');
            header('location:' . BASEURL . 'MailsController/incomingMail');
            exit;
        } else {
            Flasher::setFlasher('Failed to add mail', 'failed', 'danger');
            header('location:' . BASEURL . 'MailsController/incomingMail');
            exit;
        }
    }

    public function createOutgoing(){
        $this->requireAuth();
        return $this->view('mail/create_outgoing');
    }

    public function storeOutgoing(){
        $this->requireAuth();
        // Server-side validation
        $required_fields = ['date', 'sender', 'subject', 'recepient', 'type_mail'];
        $errors = [];
        
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $errors[] = ucfirst($field) . ' is required';
            }
        }

        // Check if file is required - if $_FILES is not set or has no name, it's missing
        if (!isset($_FILES['file']) || empty($_FILES['file']['name']) || $_FILES['file']['error'] === UPLOAD_ERR_NO_FILE) {
            $errors[] = 'File is required';
        }
        
        if (!empty($errors)) {
            Flasher::setFlasher(implode(', ', $errors), 'failed', 'danger');
            header('location:' . BASEURL . 'MailsController/outgoingMail');
            exit;
        }

        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            // Validate file type
            $allowed_extensions = ['pdf', 'jpg', 'jpeg', 'png'];
            $extension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
            
            if (!in_array($extension, $allowed_extensions)) {
                Flasher::setFlasher('File type not allowed. Only PDF, JPG, JPEG, and PNG files are allowed.', 'failed', 'danger');
                header('location:' . BASEURL . 'MailsController/outgoingMail');
                exit;
            }
            
            // Validate file size 
            $max_file_size = 5 * 1024 * 1024; // 5MB in bytes
            if ($_FILES['file']['size'] > $max_file_size) {
                Flasher::setFlasher('File size too large. Maximum allowed size is 5MB.', 'failed', 'danger');
                header('location:' . BASEURL . 'MailsController/outgoingMail');
                exit;
            }

            $uploadDir = UPLOAD_FOLDER;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
            }

            // Replace the file name
            $newFileName = 'mail_' . time() . '.' . $extension;
            $uploadFile = $uploadDir . $newFileName; // Full file system path

            // Move the uploaded file
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
                $_POST['file'] = $newFileName;
            } else {
                Flasher::setFlasher('File upload failed', 'failed', 'danger');
                header('location:' . BASEURL . 'MailsController/outgoingMail');
                exit;
            }
        } else {
            // This shouldn't be reached now because we check for file presence in validation above
            $_POST['file'] = null; // No file uploaded
        }

        $iduser = $_SESSION['user']['id_user'];
        $_POST['id_user'] = $iduser;
        $_POST['type'] = 'out'; // Set type to outgoing mail

        if ($this->model('MailsModel')->createNewMail($_POST) > 0) {
            Flasher::setFlasher('Outgoing mail added successfully', 'success', 'success');
            header('location:' . BASEURL . 'MailsController/outgoingMail');
            exit;
        } else {
            Flasher::setFlasher('Failed to add outgoing mail', 'failed', 'danger');
            header('location:' . BASEURL . 'MailsController/outgoingMail');
            exit;
        }
    }

    public function edit($id, $type = null){
        $this->requireAuth();
        $mail = $this->model('MailsModel')->getDataById($id);
        if ($mail) {
            $data = [
            'title' => 'Edit Mail',
            'mails' => $mail
        ];
        return $this->view('mail/edit', $data);
        } else {
            // Redirect with error if mail not found
            Flasher::setFlasher('Mail not found', 'failed', 'danger');
            if ($type === 'out') {
                header('location:' . BASEURL . 'MailsController/outgoingMail');
            } else {
                header('location:' . BASEURL . 'MailsController/incomingMail');
            }
            exit;
        }
    }

    public function update(){
        $this->requireAuth();
        // Server-side validation for updates - only validate if fields are being changed to empty
        // but allow existing empty values to remain unchanged
        $required_fields = ['date', 'sender', 'subject', 'recepient', 'type_mail'];
        $errors = [];
        
        // Only validate that required fields aren't being emptied if they previously had values
        $mail = $this->model('MailsModel')->getDataById($_POST['id_mail']); // Get current record first
        
        foreach ($required_fields as $field) {
            // Check if field is empty in submission
            if (empty($_POST[$field])) {
                // Only show error if the original record had a value for this field
                if (!empty($mail[$field])) {
                    $errors[] = ucfirst($field) . ' cannot be empty when previously filled';
                }
            }
        }
        
        if (!empty($errors)) {
            Flasher::setFlasher(implode(', ', $errors), 'failed', 'danger');
            // Redirect based on the type of mail being edited
            if ($_POST['type'] === 'out') {
                header('location:' . BASEURL . 'MailsController/outgoingMail');
            } else {
                header('location:' . BASEURL . 'MailsController/incomingMail');
            }
            exit;
        }

        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            // If a new file is being uploaded during update, validate it
            // Validate file type
            $allowed_extensions = ['pdf', 'jpg', 'jpeg', 'png'];
            $extension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
            
            if (!in_array($extension, $allowed_extensions)) {
                Flasher::setFlasher('File type not allowed. Only PDF, JPG, JPEG, and PNG files are allowed.', 'failed', 'danger');
                // Redirect based on the type of mail being edited
                if ($_POST['type'] === 'out') {
                    header('location:' . BASEURL . 'MailsController/outgoingMail');
                } else {
                    header('location:' . BASEURL . 'MailsController/incomingMail');
                }
                exit;
            }
            
            // Validate file size 
            $max_file_size = 5 * 1024 * 1024; // 5MB in bytes
            if ($_FILES['file']['size'] > $max_file_size) {
                Flasher::setFlasher('File size too large. Maximum allowed size is 5MB.', 'failed', 'danger');
                // Redirect based on the type of mail being edited
                if ($_POST['type'] === 'out') {
                    header('location:' . BASEURL . 'MailsController/outgoingMail');
                } else {
                    header('location:' . BASEURL . 'MailsController/incomingMail');
                }
                exit;
            }

            $uploadDir = UPLOAD_FOLDER;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
            }

            // Replace the file name
            $newFileName = 'mail_' . time() . '.' . $extension;
            $uploadFile = $uploadDir . $newFileName; // Full file system path

            // Move the uploaded file
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
                // Remove old file if exists
                if (!empty($mail['file']) && isset($mail['file']) && file_exists($uploadDir . $mail['file'])) {
                    unlink($uploadDir . $mail['file']);
                }
                $_POST['file'] = $newFileName;
            } else {
                Flasher::setFlasher('File upload failed', 'failed', 'danger');
                header('location:' . BASEURL . 'MailsController/incomingMail');
                exit;
            }
        } elseif (!isset($_POST['remove_file']) || $_POST['remove_file'] !== '1') {
            $_POST['file'] = isset($mail['file']) ? $mail['file'] : null; // Keep existing file if no new file uploaded
        } else {
            // Remove existing file
            $uploadDir = UPLOAD_FOLDER;
            if (!empty($mail['file']) && isset($mail['file']) && file_exists($uploadDir . $mail['file'])) {
                unlink($uploadDir . $mail['file']);
            }
            $_POST['file'] = null;
        }

        if ($this->model('MailsModel')->updateMail($_POST) > 0) {
            Flasher::setFlasher('Mail updated successfully', 'success', 'success');
            // Redirect to the appropriate page based on type
            if ($_POST['type'] === 'out') {
                header('location:' . BASEURL . 'MailsController/outgoingMail');
            } else {
                header('location:' . BASEURL . 'MailsController/incomingMail');
            }
            exit;
        } else {
            Flasher::setFlasher('Failed to update mail', 'failed', 'danger');
            // Redirect to the appropriate page based on type
            if ($_POST['type'] === 'out') {
                header('location:' . BASEURL . 'MailsController/outgoingMail');
            } else {
                header('location:' . BASEURL . 'MailsController/incomingMail');
            }
            exit;
        }
    }

    public function delete($id){
        $this->requireAuth();
        // Get the mail to determine its type before deletion
        $mail = $this->model('MailsModel')->getDataById($id);
        
        if ($this->model('MailsModel')->deleteMail($id) > 0) {
            Flasher::setFlasher('Mail deleted successfully', 'success', 'success');
            // Redirect based on the type of the deleted mail
            if ($mail && $mail['type'] === 'out') {
                header('location:' . BASEURL . 'MailsController/outgoingMail');
            } else {
                header('location:' . BASEURL . 'MailsController/incomingMail');
            }
            exit;
        } else {
            Flasher::setFlasher('Failed to delete mail', 'failed', 'danger');
            // Redirect based on the type of the mail if still available
            if ($mail && $mail['type'] === 'out') {
                header('location:' . BASEURL . 'MailsController/outgoingMail');
            } else {
                header('location:' . BASEURL . 'MailsController/incomingMail');
            }
            exit;
        }
    }

    public function incomingMail(){
        $this->requireAuth();
        $mails = $this->model('MailsModel')->getIncomingMails();
        $data = [
            'title' => 'Daftar Mail',
            'mails' => $mails
        ];

        return $this->view('mail/index', $data);
    }

        public function outgoingMail(){
        $this->requireAuth();
        $mails = $this->model('MailsModel')->getOutgoingMails();
        $data = [
            'title' => 'Daftar Mail',
            'mails' => $mails
        ];

        return $this->view('mail/outgoingMails', $data);
    }
}
