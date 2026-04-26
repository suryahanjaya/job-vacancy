<?php
require_once APP_ROOT . '/models/UserModel.php';

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function profile()
    {
        $userId = getUserId();
        $user = $this->userModel->findById($userId);

        if (!$user) {
            setFlash('error', 'User not found.');
            redirect('/logout');
            return;
        }

        view('user/profile', [
            'title' => 'My Profile',
            'user' => $user
        ]);
    }

    public function updateField()
    {
        if (!verifyCsrf()) {
            setFlash('error', 'Invalid request.');
            redirect('/profile');
            return;
        }

        $field = $_POST['field'] ?? '';
        $value = trim($_POST['value'] ?? '');

        $allowed = ['full_name', 'email', 'phone', 'company_name'];

        if (!in_array($field, $allowed)) {
            setFlash('error', 'Invalid field.');
            redirect('/profile');
            return;
        }

        // Validation
        if ($value === '') {
            setFlash('error', 'Value cannot be empty.');
            redirect('/profile');
            return;
        }

        if ($field === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            setFlash('error', 'Invalid email.');
            redirect('/profile');
            return;
        }

        if ($field === 'phone' && !preg_match('/^[0-9+\-\s]{8,15}$/', $value)) {
            setFlash('error', 'Invalid phone.');
            redirect('/profile');
            return;
        }

        if (strlen($value) > 255) {
            setFlash('error', 'Too long.');
            redirect('/profile');
            return;
        }

        // Save
        $result = $this->userModel->updateSingleField(getUserId(), $field, $value);

        if ($result) {
            $user = $this->userModel->findById(getUserId());

            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role'];

            if ($user['role'] === 'employer') {
                $_SESSION['company_name'] = $user['company_name'];
            }

            setFlash('success', 'Profile updated successfully.');
        } else {
            setFlash('error', 'Update failed.');
        }

        redirect('/profile');
    }

    public function deactivateAccount()
    {
        if (!verifyCsrf()) {
            setFlash('error', 'Invalid request.');
            redirect('/profile');
            return;
        }

        $role = getUserRole();

        // Only allow employer & jobseeker
        if (!in_array($role, ['employer', 'jobseeker'])) {
            setFlash('error', 'You are not allowed to deactivate this account.');
            redirect('/profile');
            return;
        }

        $this->userModel->toggleActive(getUserId());

        session_destroy();

        redirect('/');
    }
    
    public function updatePassword()
    {
        if (!verifyCsrf()) {
            setFlash('error', 'Invalid request.');
            redirect('/profile');
            return;
        }

        $userId = getUserId();
        $user = $this->userModel->findById($userId);

        if (!$user) {
            setFlash('error', 'User not found.');
            redirect('/logout');
            return;
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Basic validation
        if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
            setFlash('error', 'All fields are required.');
            redirect('/profile');
            return;
        }

        // Check current password
        if (!password_verify($currentPassword, $user['password'])) {
            setFlash('error', 'Current password is incorrect.');
            redirect('/profile');
            return;
        }

        // Prevent same password reuse
        if (password_verify($newPassword, $user['password'])) {
            setFlash('error', 'New password must be different from current password.');
            redirect('/profile');
            return;
        }

        // Confirm match
        if ($newPassword !== $confirmPassword) {
            setFlash('error', 'Passwords do not match.');
            redirect('/profile');
            return;
        }

        // Strength check (basic)
        if (strlen($newPassword) < 6) {
            setFlash('error', 'Password must be at least 6 characters.');
            redirect('/profile');
            return;
        }

        // Hash password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update DB
        $result = $this->userModel->updatePassword($userId, $hashedPassword);

        if ($result) {
            setFlash('success', 'Password updated successfully.');
        } else {
            setFlash('error', 'Failed to update password.');
        }

        redirect('/profile');
    }
}