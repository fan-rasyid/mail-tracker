<?php 

require_once __DIR__ . "/../cores/Controller.php";

class DashboardController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        $admin = $this->model('UsersModel')->getAllUsers();
        $totalAdmin = count($admin);

        $incoming_mails = $this->model('MailsModel')->getIncomingMails();
        $total_incoming_mails = count($incoming_mails);

        $outgoing_mails = $this->model('MailsModel')->getOutgoingMails();
        $total_outgoing_mails = count($outgoing_mails);

        $data = [
            'title' => 'Daftar Mail',
            'total_admin' => $totalAdmin,
            'total_incoming_mails' => $total_incoming_mails,
            'total_outgoing_mails' => $total_outgoing_mails
        ];
        return $this->view('dashboard/dashboard', $data);
    }

}

?>