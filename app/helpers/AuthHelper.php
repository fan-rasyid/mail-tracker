<?php

class AuthHelper
{
    // Cek login dan arahkan ke halaman login jika belum login
    public static function requireAuth()
    {
        if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
            $baseUrl = defined('BASEURL') ? BASEURL : '/mail-tracker/public/';
            header('Location: ' . $baseUrl . 'AuthController');
            exit();
        }
    }

}
