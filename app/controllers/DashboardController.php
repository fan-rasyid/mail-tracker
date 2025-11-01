<?php 

require_once __DIR__ . "/../cores/Controller.php";

class DashboardController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        return $this->view('dashboard/dashboard');
    }

}

?>