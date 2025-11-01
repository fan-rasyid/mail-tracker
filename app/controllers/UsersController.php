<?php

require_once __DIR__ . "/../cores/Controller.php";

class UsersController extends Controller
{
    public function index(){
        $users = $this->model('UsersModel')->getAllUsers();
        $data = [
            'title' => 'Daftar User',
            'users' => $users
        ];
        return $this->view('user/index', $data);
    }

    public function edit($id){
        $user = $this->model('UsersModel')->getDataById($id);
        $data = [
            'title' => 'Edit User',
            'users' => $user
        ];
        return $this->view('users/edit', $data);
    }

    public function update(){
        if ($this->model('UsersModel')->updateUser($_POST) > 0) {
            Flasher::setFlasher('success', 'updated', 'success');
            header('location:' . BASEURL . '/admstr/category');
            exit;
        } else {
            Flasher::setFlasher('failed', 'updated', 'danger');
            header('location:' . BASEURL . '/admstr/category');
            exit;
        }
    }

    public function delete($id){
        if ($this->model('UsersModel')->deleteUser($id) > 0) {
            Flasher::setFlasher('success', 'updated', 'success');
            header('location:' . BASEURL . '/admstr/category');
            exit;
        } else {
            Flasher::setFlasher('failed', 'updated', 'danger');
            header('location:' . BASEURL . '/admstr/category');
            exit;
        }
    }
}