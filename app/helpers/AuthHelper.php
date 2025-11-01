<?php

class AuthHelper
{
    public static function isAuthenticated()
    {
        return isset($_SESSION['user']) && !empty($_SESSION['user']);
    }

    public static function requireAuth()
    {
        if (!self::isAuthenticated()) {
            if (defined('BASEURL')) {
                header('Location: ' . BASEURL . 'AuthController');
            } else {
                header('Location: /mail-tracker/public/AuthController');
            }
            exit();
        }
    }

    public static function getUser()
    {
        if (self::isAuthenticated()) {
            return $_SESSION['user'];
        }
        return null;
    }

    public static function getUserId()
    {
        $user = self::getUser();
        return $user ? $user['id_user'] : null;
    }

    public static function logout()
    {
        if (self::isAuthenticated()) {
            unset($_SESSION['user']);
        }
        session_destroy();
    }
}