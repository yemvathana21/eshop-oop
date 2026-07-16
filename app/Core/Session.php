<?php
namespace App\Core;

class Session {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function has($key) {
        self::start();
        return isset($_SESSION[$key]);
    }

    public static function remove($key) {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy() {
        self::start();
        session_destroy();
        $_SESSION = [];
    }

    // Flash Messages
    public static function setFlash($key, $message) {
        self::start();
        $_SESSION['flash'][$key] = $message;
    }

    public static function getFlash($key) {
        self::start();
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }

    public static function hasFlash($key) {
        self::start();
        return isset($_SESSION['flash'][$key]);
    }

    // Auth specific helpers
    public static function login($user) {
        self::set('user_id', $user['id']);
        self::set('user_name', $user['name']);
        self::set('user_email', $user['email']);
        self::set('user_role', $user['role']);
        self::set('logged_in', true);
    }

    public static function logout() {
        self::destroy();
    }

    public static function isLoggedIn() {
        return self::get('logged_in') === true;
    }

    public static function getUserId() {
        return self::get('user_id');
    }

    public static function getUserRole() {
        return self::get('user_role', 'customer');
    }

    public static function isAdmin() {
        return self::isLoggedIn() && self::getUserRole() === 'admin';
    }
}
