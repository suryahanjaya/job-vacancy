<?php
/**
 * Authentication & RBAC Middleware
 */

class AuthMiddleware
{
    /**
     * Check if user is authenticated
     */
    public static function handle()
    {
        if (!isLoggedIn()) {
            setFlash('error', 'Please log in to access this page.');
            redirect('/login');
            return false;
        }
        return true;
    }

    /**
     * Check if user has specific role
     */
    public static function role($role)
    {
        if (!isLoggedIn()) {
            setFlash('error', 'Please log in to access this page.');
            redirect('/login');
            return false;
        }

        if (getUserRole() !== $role) {
            setFlash('error', 'You do not have permission to access this page.');
            redirect('/');
            return false;
        }
        return true;
    }

    /**
     * Check if user is guest (not logged in)
     */
    public static function guest()
    {
        if (isLoggedIn()) {
            redirect('/dashboard');
            return false;
        }
        return true;
    }
}
