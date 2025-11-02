<?php

require_once __DIR__ . "/../cores/Controller.php";

class MailsController extends Controller
{
    public function index()
    {
        AuthHelper::requireAuth();
        $mails = $this->model('MailsModel')->getAllMails();
        $data = [
            'title' => 'Daftar Mail',
            'mails' => $mails
        ];
        return $this->view('mail/index', $data);
    }

    public function create()
    {
        AuthHelper::requireAuth();
        return $this->view('mail/create');
    }

    public function store()
    {
        AuthHelper::requireAuth();
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

        if (UploadHelper::hasFile('file')) {
            // Use the simplified upload helper
            $upload_result = UploadHelper::upload('file', 'uploads');

            if ($upload_result['success']) {
                $_POST['file'] = $upload_result['file_name'];
            } else {
                Flasher::setFlasher($upload_result['message'], 'failed', 'danger');
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

    public function createOutgoing()
    {
        AuthHelper::requireAuth();
        return $this->view('mail/create_outgoing');
    }

    public function storeOutgoing()
    {
        AuthHelper::requireAuth();
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

        if (UploadHelper::hasFile('file')) {
            // Use the simplified upload helper
            $upload_result = UploadHelper::upload('file', 'uploads');

            if ($upload_result['success']) {
                $_POST['file'] = $upload_result['file_name'];
            } else {
                Flasher::setFlasher($upload_result['message'], 'failed', 'danger');
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

    public function edit($id, $type = null)
    {
        AuthHelper::requireAuth();
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

    public function update()
    {
        AuthHelper::requireAuth();
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

        if (UploadHelper::hasFile('file')) {
            // Use the simplified upload helper
            $upload_result = UploadHelper::upload('file', 'uploads');

            if ($upload_result['success']) {
                $new_file_name = $upload_result['file_name'];
                $uploadDir = UPLOAD_FOLDER;

                // Remove old file if exists
                if (!empty($mail['file']) && isset($mail['file']) && file_exists($uploadDir . $mail['file'])) {
                    unlink($uploadDir . $mail['file']);
                }

                $_POST['file'] = $new_file_name;
            } else {
                Flasher::setFlasher($upload_result['message'], 'failed', 'danger');
                // Redirect based on the type of mail being edited
                if ($_POST['type'] === 'out') {
                    header('location:' . BASEURL . 'MailsController/outgoingMail');
                } else {
                    header('location:' . BASEURL . 'MailsController/incomingMail');
                }
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

    public function delete($id)
    {
        AuthHelper::requireAuth();
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

    public function incomingMail()
    {
        AuthHelper::requireAuth();
        $mails = $this->model('MailsModel')->getIncomingMails();
        $user = $_SESSION['user'];
        $data = [
            'title' => 'Daftar Mail',
            'mails' => $mails,
            'user' => $user
        ];

        return $this->view('mail/index', $data);
    }

    public function outgoingMail()
    {
        AuthHelper::requireAuth();
        $mails = $this->model('MailsModel')->getOutgoingMails();
        $user = $_SESSION['user'];
        $data = [
            'title' => 'Daftar Mail',
            'mails' => $mails,
            'user' => $user
        ];

        return $this->view('mail/outgoingMails', $data);
    }
}
