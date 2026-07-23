<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\User;

class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function showLogin() {
        if (Session::isLoggedIn('customer')) {
            $this->redirect('');
        }
        $this->render('customer/login', ['title' => 'Login - E-Shop'], 'auth');
    }

    public function showAdminLogin() {
        if (Session::isAdmin()) {
            $this->redirect('admin/dashboard');
        }
        $this->render('admin/login', ['title' => 'Admin Login - E-Shop'], 'admin_auth');
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            Session::setFlash('error', 'Please fill in all fields.');
            $this->redirect('login');
        }

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            if ($user['role'] === 'admin') {
                Session::setFlash('error', 'Administrators must log in through the admin portal.');
                $this->redirect('login');
            }

            Session::login($user);
            Session::setFlash('success', 'Welcome back, ' . $user['name'] . '!');
            $this->redirect('');
        } else {
            Session::setFlash('error', 'Invalid email or password.');
            $this->redirect('login');
        }
    }

    public function adminLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            Session::setFlash('error', 'Please fill in all fields.');
            $this->redirect('admin/login');
        }

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            if ($user['role'] !== 'admin') {
                Session::setFlash('error', 'Access denied. This area is for administrators only.');
                $this->redirect('admin/login');
            }

            Session::login($user);
            Session::setFlash('success', 'Welcome to Admin Panel, ' . $user['name'] . '!');
            $this->redirect('admin/dashboard');
        } else {
            Session::setFlash('error', 'Invalid email or password.');
            $this->redirect('admin/login');
        }
    }

    public function showRegister() {
        if (Session::isLoggedIn()) {
            $this->redirect('');
        }
        $this->render('customer/register', ['title' => 'Register - E-Shop'], 'auth');
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('register');
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm_password = trim($_POST['confirm_password'] ?? '');

        if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
            Session::setFlash('error', 'Please fill in all fields.');
            $this->redirect('register');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::setFlash('error', 'Please enter a valid email address.');
            $this->redirect('register');
        }

        if ($password !== $confirm_password) {
            Session::setFlash('error', 'Passwords do not match.');
            $this->redirect('register');
        }

        if (strlen($password) < 6) {
            Session::setFlash('error', 'Password must be at least 6 characters long.');
            $this->redirect('register');
        }

        if ($this->userModel->emailExists($email)) {
            Session::setFlash('error', 'Email is already registered.');
            $this->redirect('register');
        }

        if ($this->userModel->create($name, $email, $password, 'customer')) {
            Session::setFlash('success', 'Registration successful! Please login.');
            $this->redirect('login');
        } else {
            Session::setFlash('error', 'Registration failed. Please try again.');
            $this->redirect('register');
        }
    }

    public function logout() {
        Session::logout('customer');
        $this->redirect('login');
    }

    public function adminLogout() {
        Session::logout('admin');
        $this->redirect('admin/login');
    }
}
