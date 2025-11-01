<?php

require_once __DIR__ . "/../cores/Controller.php";

class AuthController extends Controller
{
    public function index()
    {
        return $this->view('auth/login');
    }
    public function login()
    {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $result = $this->model('UsersModel')->getUser($username, $password);

            if ($result['success']) {
                $_SESSION['user'] = $result['user'];
                header('Location: '.BASEURL.'DashboardController');
                exit();
            } else {
                $error_message = $result['message'];
                return $this->view('auth/login');
            }
        } else {
           return $this->view('auth/login');
        }
    }

    public function registerView(){
        return $this->view('auth/register');
    }
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $name = $_POST['name'] ?? '';

            $data = [
                'username' => $username,
                'password' => $password,
                'name' => $name
            ];

            $result = $this->model('UsersModel')->createUser($data);
            // var_dump($result);die;
            if ($result['success']) {
                $_SESSION['user'] = $result['user'];
                header('Location: '.BASEURL.'DashboardController');
                exit();
            } else {
                $error_message = $result['message'];
                return $this->view('auth/register');
            }
        } else {
            return $this->view('auth/register');
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location:'.BASEURL);
        exit();
    }
}