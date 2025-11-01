<?php 

require_once __DIR__ . "/../cores/Controller.php";

class MailsController extends Controller
{
    public function index(){
        $mails = $this->model('MailsModel')->getAllMails();
        $data = [
            'title' => 'Daftar Mail',
            'mails' => $mails
        ];
        return $this->view('mail/index', $data);
    }

    public function create(){
        return $this->view('mail/create');
    }

    public function store(){
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = UPLOAD_FOLDER;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
            }

            // Replace the file name
            $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION); // Get file extension
            $newFileName = 'mail_' . time() . '.' . $extension;
            $uploadFile = $uploadDir . $newFileName; // Full file system path

            // Move the uploaded file
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
                $_POST['file'] = $newFileName;
            } else {
                header('location:' . BASEURL . 'MailsController/incomingMail');
                exit;
            }
        } else {
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
        return $this->view('mail/create_outgoing');
    }

    public function storeOutgoing(){
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = UPLOAD_FOLDER;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
            }

            // Replace the file name
            $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION); // Get file extension
            $newFileName = 'mail_' . time() . '.' . $extension;
            $uploadFile = $uploadDir . $newFileName; // Full file system path

            // Move the uploaded file
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
                $_POST['file'] = $newFileName;
            } else {
                header('location:' . BASEURL . 'MailsController/outgoingMail');
                exit;
            }
        } else {
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
        $mail = $this->model('MailsModel')->getDataById($_POST['id_mail']); // Assuming id_mail is in POST
        
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/mail-tracker/public/uploads/'; // Correct path for uploads
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
            }

            // Replace the file name
            $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION); // Get file extension
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
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/mail-tracker/public/uploads/';
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
        $mails = $this->model('MailsModel')->getIncomingMails();
        $data = [
            'title' => 'Daftar Mail',
            'mails' => $mails
        ];

        return $this->view('mail/index', $data);
    }

        public function outgoingMail(){
        $mails = $this->model('MailsModel')->getOutgoingMails();
        $data = [
            'title' => 'Daftar Mail',
            'mails' => $mails
        ];

        return $this->view('mail/outgoingMails', $data);
    }
}
