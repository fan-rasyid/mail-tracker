<?php

require_once __DIR__ . "/../cores/Controller.php";

class UsersController extends Controller
{
    public function index(){
        AuthHelper::requireAuth();
        $users = $this->model('UsersModel')->getAllUsers();
        $data = [
            'title' => 'Daftar User',
            'users' => $users
        ];
        return $this->view('user/index', $data);
    }

    public function edit($id){
        AuthHelper::requireAuth();
        $user = $this->model('UsersModel')->getDataById($id);
        $data = [
            'title' => 'Edit User',
            'users' => $user
        ];
        return $this->view('user/edit', $data);
    }

    public function delete($id){
        AuthHelper::requireAuth();
        if ($this->model('UsersModel')->deleteUser($id) > 0) {
            Flasher::setFlasher('User deleted successfully', 'success', 'success');
            header('location:' . BASEURL . '/UsersController/listUsers');
            exit;
        } else {
            Flasher::setFlasher('Failed to delete User', 'failed', 'danger');
            header('location:' . BASEURL . '/UsersController/listUsers');
            exit;
        }
    }
}