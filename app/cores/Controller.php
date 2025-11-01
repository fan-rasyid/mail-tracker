<?php

class Controller
{
    public function view($view, $data = [])
    {
        foreach ($data as $key => $value) {
            $$key = $value;
        }
        ob_start();
        require_once '../app/views/' . $view . '.php';
        $content = ob_get_clean();

        require_once '../app/views/layouts/main.php';
    }

    public function model($model)
    {
        require_once '../app/models/' . $model . '.php';
        return new $model;
    }

    protected function isAuthenticated()
    {
        return isset($_SESSION['user']) && !empty($_SESSION['user']);
    }

    protected function requireAuth()
    {
        if (!$this->isAuthenticated()) {
            header('Location: ' . BASEURL . 'AuthController');
            exit();
        }
    }
}
