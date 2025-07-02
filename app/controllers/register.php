<?php

class Register extends Controller {

    public function index() {
        // Show registration form
        $this->view('register/index');
    }

    public function create() {
        // Handle registration form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Validate inputs
            if (empty($username) || empty($password) || empty($confirm_password)) {
                $_SESSION['registration_error'] = "All fields are required.";
                header('Location: /register');
                exit;
            }

            if ($password !== $confirm_password) {
                $_SESSION['registration_error'] = "Passwords do not match.";
                header('Location: /register');
                exit;
            }

            // Create user
            $user = $this->model('User');
            $result = $user->register($username, $password);

            if ($result === true) {
                $_SESSION['registration_success'] = "Account created successfully! Please login.";
                header('Location: /login');
                exit;
            } else {
                $_SESSION['registration_error'] = $result;
                header('Location: /register');
                exit;
            }
        }
        
        // If not POST, redirect to registration form
        header('Location: /register');
        exit;
    }
}
