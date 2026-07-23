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

    // Scoped Auth (Independent sessions for admin and customer)
    public static function login($user) {
        $scope = ($user['role'] === 'admin') ? 'admin' : 'customer';
        self::set($scope . '_user_id', $user['id']);
        self::set($scope . '_user_name', $user['name']);
        self::set($scope . '_user_email', $user['email']);
        self::set($scope . '_logged_in', true);
    }

    public static function logout($scope = 'customer') {
        self::remove($scope . '_user_id');
        self::remove($scope . '_user_name');
        self::remove($scope . '_user_email');
        self::remove($scope . '_logged_in');
    }

    public static function isLoggedIn($scope = 'customer') {
        return self::get($scope . '_logged_in') === true;
    }

    public static function isAdmin() {
        return self::isLoggedIn('admin');
    }

    public static function getUserId($scope = 'customer') {
        return self::get($scope . '_user_id');
    }

    public static function getUserName($scope = 'customer') {
        return self::get($scope . '_user_name', '');
    }

    public static function getUserRole() {
        // This is mainly for legacy compatibility or UI checks
        if (self::isAdmin()) return 'admin';
        if (self::isLoggedIn('customer')) return 'customer';
        return 'guest';
    }
}
