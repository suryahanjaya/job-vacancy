<?php
/**
 * Authentication Controller
 */

require_once APP_ROOT . '/models/UserModel.php';

class AuthController
{
    private $userModel = null;

    private function getUserModel()
    {
        if ($this->userModel === null) {
            $this->userModel = new UserModel();
        }
        return $this->userModel;
    }

    /**
     * Show login form
     */
    public function showLogin()
    {
        if (isLoggedIn()) {
            redirect('/dashboard');
        }
        view('auth/login', ['title' => 'Login', 'isAuthPage' => true]);
    }

    /**
     * Process login
     */
    public function login()
    {
        if (!verifyCsrf()) {
            setFlash('error', 'Invalid request.');
            redirect('/login');
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $validator = new Validator($_POST);
        $validator->required('email', 'Email')
            ->email('email', 'Email')
            ->required('password', 'Password');

        if ($validator->fails()) {
            setErrors($validator->errors());
            setOldInput(['email' => $email]);
            redirect('/login');
            return;
        }

        $user = $this->getUserModel()->findByEmail($email);

        if (!$user || !$this->getUserModel()->verifyPassword($password, $user['password'])) {
            setFlash('error', 'Invalid email or password.');
            setOldInput(['email' => $email]);
            redirect('/login');
            return;
        }

        if (!$user['is_active']) {
            setFlash('error', 'Your account has been deactivated.');
            redirect('/login');
            return;
        }

        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['company_name'] = $user['company_name'] ?? '';

        clearOldInput();
        setFlash('success', 'Welcome back, ' . h($user['full_name']) . '!');
        redirect('/dashboard');
    }

    /**
     * Show registration form
     */
    public function showRegister()
    {
        if (isLoggedIn()) {
            redirect('/dashboard');
        }
        view('auth/register', ['title' => 'Register', 'isAuthPage' => true]);
    }

    /**
     * Process registration
     */
    public function register()
    {
        if (!verifyCsrf()) {
            setFlash('error', 'Invalid request.');
            redirect('/register');
            return;
        }

        $data = [
            'full_name' => trim($_POST['full_name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'password_confirm' => $_POST['password_confirm'] ?? '',
            'role' => $_POST['role'] ?? 'jobseeker',
            'company_name' => trim($_POST['company_name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
        ];

        $validator = new Validator($data);
        $validator->required('full_name', 'Full name')
            ->minLength('full_name', 2, 'Full name')
            ->maxLength('full_name', 150, 'Full name')
            ->required('email', 'Email')
            ->email('email', 'Email')
            ->required('password', 'Password')
            ->minLength('password', 6, 'Password')
            ->required('password_confirm', 'Password confirmation')
            ->matches('password_confirm', 'password', 'Password confirmation', 'Password')
            ->in('role', ['employer', 'jobseeker'], 'Role');
            
        // Company name required for employers
        if ($data['role'] === 'employer') {
            $validator->required('company_name', 'Company name');
        }

        if ($validator->fails()) {
            setErrors($validator->errors());
            setOldInput($data);
            redirect('/register');
            return;
        }

        // Check duplicate email
        if ($this->getUserModel()->findByEmail($data['email'])) {
            setFlash('error', 'Email already registered.');
            setOldInput($data);
            redirect('/register');
            return;
        }

        try {
            $userId = $this->getUserModel()->create($data);

            // Auto login
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_role'] = $data['role'];
            $_SESSION['user_name'] = $data['full_name'];
            $_SESSION['user_email'] = $data['email'];
            $_SESSION['company_name'] = $data['company_name'];

            clearOldInput();
            setFlash('success', 'Account created successfully! Welcome to ' . APP_NAME . '.');
            redirect('/dashboard');
        } catch (Exception $e) {
            setFlash('error', 'Registration failed. Please try again.');
            setOldInput($data);
            redirect('/register');
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        session_destroy();
        session_start();
        setFlash('success', 'You have been logged out.');
        redirect('/login');
    }
}
